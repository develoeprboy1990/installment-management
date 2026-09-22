<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Traits\LogsActivity;

class Purchase extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'product_id',
        'purchase_date',
        'total_price',
        'advance_payment',
        'remaining_balance',
        'installment_type',      // 'daily' | 'weekly' | 'monthly' | '3months' | '6months' | '1year'
        'installment_count',     // total number of installments (generic)
        'installment_months',    // kept for backward compatibility (monthly)
        'monthly_installment',   // kept for backward compatibility (monthly)
        'first_installment_date',
        'last_installment_date',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'first_installment_date' => 'date',
        'last_installment_date' => 'date',
    ];

    /**
     * Attributes that should trigger activity logs on update.
     */
    public static function activityWatchedAttributes(): array
    {
        return [
            'status',
            'remaining_balance',
            'advance_payment',
            'monthly_installment',
            'installment_type',
            'installment_count',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function purchasePartners()
    {
        return $this->hasMany(PurchasePartner::class);
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'purchase_partners')
                    ->withPivot('share_amount', 'share_percentage', 'notes')
                    ->withTimestamps();
    }

    public function getPaidInstallmentsCashAmountAttribute()
    {
        return (float) $this->installments()
            ->whereIn('status', ['paid', 'partial'])  // sirf actual paid aur partial — waived exclude
            ->sum('paid_amount');
    }

    public function getPaidInstallmentsDiscountAmountAttribute()
    {
        return (float) $this->installments()
            ->whereIn('status', ['paid', 'partial'])  // sirf actual paid aur partial — waived exclude
            ->sum('discount');
    }

    public function getTotalPaidAmountAttribute()
    {
        return (float) $this->advance_payment
            + $this->paid_installments_cash_amount
            + $this->paid_installments_discount_amount;
    }

    // ─── Installment Calculation ─────────────────────────────────────────────

    /**
     * Calculate per-installment amount for any type (daily / weekly / monthly).
     */
    public static function calculateInstallmentAmount(float $totalPrice, float $advancePayment, int $count): float
    {
        if ($count <= 0) return 0;
        $remaining = $totalPrice - $advancePayment;
        return round($remaining / $count, 2);
    }

    /**
     * Backward-compatible alias (used in existing monthly code).
     */
    public static function calculateMonthlyInstallment($totalPrice, $advancePayment, $months): float
    {
        return self::calculateInstallmentAmount($totalPrice, $advancePayment, $months);
    }

    /**
     * Return the effective total installment count regardless of type.
     * - daily/weekly       → uses installment_count
     * - monthly            → uses installment_months (backward compat)
     * - 3months/6months/1year → always 1 (single lump-sum payment)
     */
    public function getTotalInstallmentCount(): int
    {
        if (in_array($this->installment_type, ['3months', '6months', '1year'])) {
            return 1;
        }
        if ($this->installment_type !== 'monthly' && $this->installment_count) {
            return (int) $this->installment_count;
        }
        return (int) $this->installment_months;
    }

    /**
     * Check if this is a lump-sum / fixed-duration plan.
     */
    public function isLumpSumPlan(): bool
    {
        return in_array($this->installment_type, ['3months', '6months', '1year']);
    }

    /**
     * Human-readable label for the installment type.
     */
    public function getInstallmentTypeLabel(): string
    {
        return match($this->installment_type ?? 'monthly') {
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            '3months' => '3 Months Plan',
            '6months' => '6 Months Plan',
            '1year'   => '1 Year Plan',
            default   => 'Monthly',
        };
    }

    // Calculate remaining balance
    public function getRemainingBalance()
    {
        $totalPaid = $this->total_paid_amount;
        // Never return negative remaining due to overpayments or reconciliation
        return max(0, $this->total_price - $totalPaid);
    }

    // Check if purchase is defaulted (missed payments)
    public function isDefaulted()
    {
        $overdueInstallments = $this->installments()
            ->whereIn('status', ['pending', 'overdue'])
            ->where('due_date', '<', now())
            ->exists();

        return $overdueInstallments;
    }
}
