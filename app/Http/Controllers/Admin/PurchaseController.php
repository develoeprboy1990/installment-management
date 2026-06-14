<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use App\Models\RecoveryOfficer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['customer', 'product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $purchases = $query->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $recoveryOfficers = RecoveryOfficer::where('is_active', true)->get();
        return view('purchases.create', compact('customers', 'products', 'recoveryOfficers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'            => 'required|exists:customers,id',
            'product_id'             => 'required|exists:products,id',
            'purchase_date'          => 'required|date',
            'total_price'            => 'required|numeric|min:0',
            'advance_payment'        => 'required|numeric|min:0',
            'installment_type'       => 'required|in:daily,weekly,monthly',
            'installment_count'      => 'required|integer|min:1',
            'first_installment_date' => 'required|date|after_or_equal:purchase_date',
            'recovery_officer_id'    => 'required|exists:recovery_officers,id',
        ]);

        $type            = $request->installment_type;
        $count           = (int) $request->installment_count;
        $remainingBalance = $request->total_price - $request->advance_payment;

        // Use user-specified per-installment amount if provided (Mode B)
        // Otherwise auto-calculate from count (Mode A)
        $overrideAmt = (float) $request->per_installment_override;
        $installmentAmount = ($overrideAmt > 0)
            ? $overrideAmt
            : Purchase::calculateInstallmentAmount(
                $request->total_price,
                $request->advance_payment,
                $count
              );

        // Calculate last installment date based on type
        $lastInstallmentDate = $this->calculateLastInstallmentDate(
            $request->first_installment_date,
            $type,
            $count
        );

        $purchase = Purchase::create([
            'customer_id'            => $request->customer_id,
            'product_id'             => $request->product_id,
            'purchase_date'          => $request->purchase_date,
            'total_price'            => $request->total_price,
            'advance_payment'        => $request->advance_payment,
            'remaining_balance'      => $remainingBalance,
            'installment_type'       => $type,
            'installment_count'      => $count,
            'installment_months'     => $count,
            'monthly_installment'    => $installmentAmount,  // actual per-installment
            'first_installment_date' => $request->first_installment_date,
            'last_installment_date'  => $lastInstallmentDate,
        ]);

        $this->createInstallmentSchedule($purchase, $request->recovery_officer_id);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully');
    }

    public function show(Purchase $purchase)
    {
        // Ensure installments reflect fully-paid state if applicable
        $this->reconcileIfFullyPaid($purchase);

        $purchase->load(['customer', 'product', 'installments' => function($query) {
            $query->with('officer');
        }]);
        return view('purchases.show', compact('purchase'));
    }

     public function edit(Purchase $purchase)
    {
        $customers = Customer::all();
        $products = Product::all();
        $recoveryOfficers = RecoveryOfficer::where('is_active', true)->get();

        return view('purchases.edit', compact('purchase', 'customers', 'products', 'recoveryOfficers'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'customer_id'            => 'required|exists:customers,id',
            'product_id'             => 'required|exists:products,id',
            'purchase_date'          => 'required|date',
            'total_price'            => 'required|numeric|min:0',
            'advance_payment'        => 'required|numeric|min:0',
            'installment_type'       => 'required|in:daily,weekly,monthly',
            'installment_count'      => 'required|integer|min:1',
            'first_installment_date' => 'required|date|after_or_equal:purchase_date',
            'recovery_officer_id'    => 'required|exists:recovery_officers,id',
        ]);

        try {
            \DB::beginTransaction();

            $type              = $request->installment_type;
            $count             = (int) $request->installment_count;
            $remainingBalance  = $request->total_price - $request->advance_payment;
            $installmentAmount = Purchase::calculateInstallmentAmount(
                $request->total_price,
                $request->advance_payment,
                $count
            );

            $lastInstallmentDate = $this->calculateLastInstallmentDate(
                $request->first_installment_date,
                $type,
                $count
            );

            $purchase->update([
                'customer_id'            => $request->customer_id,
                'product_id'             => $request->product_id,
                'purchase_date'          => $request->purchase_date,
                'total_price'            => $request->total_price,
                'advance_payment'        => $request->advance_payment,
                'remaining_balance'      => $remainingBalance,
                'installment_type'       => $type,
                'installment_count'      => $count,
                // Always populate — installment_months is NOT NULL in DB
                'installment_months'     => $count,
                'monthly_installment'    => $installmentAmount,
                'first_installment_date' => $request->first_installment_date,
                'last_installment_date'  => $lastInstallmentDate,
                'status'                 => 'active',
            ]);

            $purchase->installments()->delete();
            $this->createInstallmentSchedule($purchase, $request->recovery_officer_id);

            \DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase updated successfully');

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Error updating purchase: ' . $e->getMessage())
                ->withInput();
        }
    }

    // NEW: Destroy method
    public function destroy(Purchase $purchase)
    {
        try {
            \DB::beginTransaction();

            // Check if any installments are paid
            $paidInstallments = $purchase->installments()->where('status', 'paid')->count();

            // Delete all pending installments first
            $purchase->installments()->delete();

            // Delete the purchase
            $purchase->delete();

            \DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase deleted successfully.'
                ]);
            }

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully.');

        } catch (\Exception $e) {
            \DB::rollback();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting purchase: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('purchases.index')
                ->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    /**
     * Dedicated printable statement for a single purchase.
     * URL: /admin/purchases/{purchase}/statement
     */
    public function purchaseStatement(Purchase $purchase)
    {
        $purchase->load([
            'customer.guarantors',
            'product',
            'installments.officer',
        ]);

        return view('purchases.statement', compact('purchase'));
    }

    /**
     * Build the full installment schedule for a purchase.
     * Supports daily, weekly, and monthly types.
     */
    private function createInstallmentSchedule(Purchase $purchase, int $recoveryOfficerId): void
    {
        $type             = $purchase->installment_type ?? 'monthly';
        $totalCount       = $purchase->getTotalInstallmentCount();
        $startDate        = Carbon::parse($purchase->first_installment_date);
        $remainingBalance = (float) $purchase->remaining_balance;

        // Use the stored monthly_installment (which may be user-specified via Mode B)
        // Fall back to auto-calculation only if not set
        $fixedInstallmentAmt = (float) $purchase->monthly_installment;
        if ($fixedInstallmentAmt <= 0) {
            $fixedInstallmentAmt = Purchase::calculateInstallmentAmount(
                $purchase->total_price,
                $purchase->advance_payment,
                $totalCount
            );
        }

        for ($i = 1; $i <= $totalCount; $i++) {
            // Calculate due date based on installment type
            $dueDate = match($type) {
                'daily'  => $startDate->copy()->addDays($i - 1),
                'weekly' => $startDate->copy()->addWeeks($i - 1),
                default  => $startDate->copy()->addMonths($i - 1),
            };

            if ($i === $totalCount) {
                // Last installment: use whatever is left (handles remainder)
                $thisAmount = round($remainingBalance, 2);
                $newBalance = 0;
            } else {
                // All other installments: use fixed amount
                $thisAmount = $fixedInstallmentAmt;
                $newBalance = round($remainingBalance - $fixedInstallmentAmt, 2);
            }

            Installment::create([
                'customer_id'         => $purchase->customer_id,
                'purchase_id'         => $purchase->id,
                'date'                => null,
                'due_date'            => $dueDate,
                'receipt_no'          => null,
                'pre_balance'         => $remainingBalance,
                'installment_amount'  => $thisAmount,
                'discount'            => 0,
                'balance'             => $newBalance,
                'fine_amount'         => 0,
                'status'              => 'pending',
                'recovery_officer_id' => $recoveryOfficerId,
                'remarks'             => "Installment $i of $totalCount ($type)",
            ]);

            $remainingBalance = $newBalance;
        }
    }

    /**
     * Calculate the last installment date based on type and count.
     */
    private function calculateLastInstallmentDate(string $firstDate, string $type, int $count): Carbon
    {
        $start = Carbon::parse($firstDate);
        return match($type) {
            'daily'  => $start->copy()->addDays($count - 1),
            'weekly' => $start->copy()->addWeeks($count - 1),
            default  => $start->copy()->addMonths($count - 1),
        };
    }

    public function getInstallmentDetails($installmentId)
    {
        $installment = Installment::with(['customer', 'officer', 'purchase'])->findOrFail($installmentId);
        $remainingBalance = $installment->purchase ? $installment->purchase->getRemainingBalance() : $installment->pre_balance;
        $payableAmount = min((float) $installment->installment_amount, (float) $remainingBalance);

        // Generate next receipt number
        $lastReceipt = Installment::where('receipt_no', '!=', null)
            ->orderBy('id', 'desc')
            ->first();

        $nextReceiptNumber = 'R-' . str_pad(
            ($lastReceipt ? intval(substr($lastReceipt->receipt_no, 2)) + 1 : 1001),
            4, '0', STR_PAD_LEFT
        );

        return response()->json([
            'receipt_no' => $nextReceiptNumber,
            'installment_amount' => $payableAmount,
            'scheduled_installment_amount' => $installment->installment_amount,
            'remaining_balance' => $remainingBalance,
            'recovery_officer_id' => $installment->recovery_officer_id,
            'recovery_officer_name' => $installment->officer?->name ?? 'N/A',
            'customer_name' => $installment->customer->name,
            'due_date' => $installment->due_date->format('d/m/Y'),
            'remarks' => "Payment for installment due on " . $installment->due_date->format('d/m/Y')
        ]);
    }

    public function processPayment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'payment_date' => 'required|date',
            // 'receipt_no' => 'required|string|unique:installments,receipt_no',
            'payment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $installment = Installment::findOrFail($request->installment_id);

        // Calculate fine if overdue
        $fine = $installment->calculateFine();

        // Calculate new balance after payment
        // Total reduction in balance is the sum of cash paid and discount given
        $totalPayment = $request->payment_amount + ($request->discount ?? 0);
        $remainingBalance = $purchase->getRemainingBalance();

        if ($totalPayment > $remainingBalance + 0.0001) {
            return back()
                ->withErrors([
                    'payment_amount' => 'Remaining balance is Rs. ' . number_format($remainingBalance, 2) . '. Payment + Discount cannot exceed the remaining amount.',
                ])
                ->withInput();
        }

        $newBalance = max(0, $remainingBalance - $totalPayment);

        // Update installment
        $installment->update([
            'date' => $request->payment_date,
            'receipt_no' => $request->receipt_no,
            'installment_amount' => $request->payment_amount,
            'discount' => $request->discount ?? 0,
            'balance' => $newBalance,
            'fine_amount' => $fine,
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'recovery_officer_id' => $request->recovery_officer_id,
            'remarks' => $request->remarks,
        ]);

        // Update subsequent installments' pre_balance
        $this->updateSubsequentInstallments($purchase, $installment, $newBalance);

        // Reconcile if the purchase is now fully paid
        $this->reconcileIfFullyPaid($purchase);

        // Update customer defaulter status
        $customer = $purchase->customer;
        $isDefaulter = $customer->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();

        $customer->update(['is_defaulter' => $isDefaulter]);

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Payment processed successfully');
    }

    // Helper method to update subsequent installments
    private function updateSubsequentInstallments(Purchase $purchase, Installment $paidInstallment, $newBalance)
    {
        $subsequentInstallments = $purchase->installments()
            ->where('due_date', '>', $paidInstallment->due_date)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->get();

        $currentBalance = $newBalance;

        foreach ($subsequentInstallments as $installment) {
            $installment->update(['pre_balance' => $currentBalance]);
            $currentBalance = max(0, $currentBalance - $installment->installment_amount);
        }
    }

    /**
     * If total paid (advance + paid installments) covers total price,
     * mark all remaining installments as 'waived' (not paid) and complete the purchase.
     */
    private function reconcileIfFullyPaid(Purchase $purchase): void
    {
        // Fresh sums to avoid stale relations
        $totalPaid = (float) $purchase->total_paid_amount;

        if ($totalPaid + 0.0001 >= (float) $purchase->total_price) {
            // Mark remaining pending installments as 'waived' — NOT 'paid'
            $pendingInstallments = $purchase->installments()
                ->whereIn('status', ['pending', 'overdue'])
                ->get();

            foreach ($pendingInstallments as $inst) {
                $inst->update([
                    'status'             => 'waived',
                    'balance'            => 0,
                    'fine_amount'        => 0,
                ]);
            }

            // Update purchase status and remaining balance
            $purchase->update([
                'status'            => 'completed',
                'remaining_balance' => 0,
            ]);

            // Update customer's defaulter status
            $customer = $purchase->customer;
            if ($customer) {
                $isDefaulter = $customer->installments()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->where('due_date', '<', now())
                    ->exists();
                $customer->update(['is_defaulter' => $isDefaulter]);
            }
        }
    }

    public function printReceipt($installmentId)
    {
        $installment = Installment::with(['customer', 'purchase.product', 'officer'])->findOrFail($installmentId);

        // Check if installment is paid
        if ($installment->status !== 'paid') {
            return redirect()->back()->with('error', 'Receipt can only be printed for paid installments.');
        }

        return view('purchases.receipt', compact('installment'));
    }

    public function updateInstallStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:paid,pending,waived',
        ]);

        $installment = Installment::findOrFail($id);
        $installment->status = $request->status;
        $installment->save();

        return redirect()->back()->with('success', 'Installment status updated successfully.');
    }
}
