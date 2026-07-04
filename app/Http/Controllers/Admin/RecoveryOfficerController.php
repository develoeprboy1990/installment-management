<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecoveryOfficer;
use App\Models\Customer;
use Illuminate\Http\Request;

class RecoveryOfficerController extends Controller
{
    public function index()
    {
        $officers = RecoveryOfficer::withCount('customers')->latest()->get();
        return view('recovery-officers.index', compact('officers'));
    }

    public function create()
    {
        return view('recovery-officers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:recovery_officers',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string',
        ]);

        $data = $request->only(['name', 'employee_id', 'phone', 'email', 'address']);
        $data['is_active'] = $request->has('is_active') && $request->is_active == '1' ? true : false;

        RecoveryOfficer::create($data);

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer added successfully.');
    }

    public function show(RecoveryOfficer $recoveryOfficer)
    {
        // Officer ke customers with their purchase stats
        $customers = $recoveryOfficer->customers()
            ->with(['purchases', 'installments'])
            ->get()
            ->map(function ($customer) {
                $totalInstallments   = $customer->installments->count();
                $paidInstallments    = $customer->installments->whereIn('status', ['paid', 'partial'])->count();
                $pendingInstallments = $customer->installments->where('status', 'pending')->count();
                $totalAmount         = $customer->purchases->sum('total_price');
                $paidAmount          = $customer->installments->whereIn('status', ['paid', 'partial'])->sum('paid_amount')
                                       + $customer->purchases->sum('advance_payment');
                $remainingBalance    = max(0, $totalAmount - $paidAmount);

                $customer->stats = [
                    'total_installments'   => $totalInstallments,
                    'paid_installments'    => $paidInstallments,
                    'pending_installments' => $pendingInstallments,
                    'total_amount'         => $totalAmount,
                    'paid_amount'          => $paidAmount,
                    'remaining_balance'    => $remainingBalance,
                ];
                return $customer;
            });

        $statistics = [
            'total_customers'   => $customers->count(),
            'total_installments'=> $recoveryOfficer->getInstallmentsCount(),
            'total_collected'   => $recoveryOfficer->getTotalCollected(),
        ];

        // Assign modal ke liye: woh customers jo is officer ke under nahi hain
        $assignedCustomerIds = $recoveryOfficer->customers()->pluck('customers.id')->toArray();
        $allCustomers = Customer::whereNotIn('id', $assignedCustomerIds)->orderBy('name')->get();

        return view('recovery-officers.show', compact('recoveryOfficer', 'statistics', 'customers', 'allCustomers'));
    }

    public function edit(RecoveryOfficer $recoveryOfficer)
    {
        return view('recovery-officers.edit', compact('recoveryOfficer'));
    }

    public function update(Request $request, RecoveryOfficer $recoveryOfficer)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:recovery_officers,employee_id,' . $recoveryOfficer->id,
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string',
        ]);

        $data = $request->only(['name', 'employee_id', 'phone', 'email', 'address']);
        $data['is_active'] = $request->has('is_active') && $request->is_active == '1' ? true : false;

        $recoveryOfficer->update($data);

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer updated successfully.');
    }

    public function destroy(RecoveryOfficer $recoveryOfficer)
    {
        if ($recoveryOfficer->installments()->exists()) {
            return redirect()->route('recovery-officers.index')
                ->with('error', 'Cannot delete Recovery Officer with existing installments.');
        }

        $recoveryOfficer->delete();

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer deleted successfully.');
    }

    /**
     * Customers assign karo officer ke under (AJAX)
     */
    public function assignCustomers(Request $request, RecoveryOfficer $recoveryOfficer)
    {
        $request->validate([
            'customer_ids'   => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
        ]);

        $pivotData = [];
        foreach ($request->customer_ids as $customerId) {
            $pivotData[$customerId] = ['assigned_at' => now()];
        }

        // syncWithoutDetaching — pehle se assigned customers remove nahi hote
        $recoveryOfficer->customers()->syncWithoutDetaching($pivotData);

        return response()->json([
            'success' => true,
            'message' => count($request->customer_ids) . ' customer(s) assigned successfully.',
        ]);
    }

    /**
     * Customer remove karo officer se (AJAX)
     */
    public function removeCustomer(RecoveryOfficer $recoveryOfficer, Customer $customer)
    {
        $recoveryOfficer->customers()->detach($customer->id);

        return response()->json([
            'success' => true,
            'message' => $customer->name . ' removed from this officer.',
        ]);
    }
}