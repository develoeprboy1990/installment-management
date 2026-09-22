<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'purchase_id',
        'customer_id',
        'recovery_officer_id',
        'receipt_no',
        'amount_paid',
        'discount',
        'fine_amount',
        'payment_method',
        'payment_type',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid'  => 'decimal:2',
        'discount'     => 'decimal:2',
        'fine_amount'  => 'decimal:2',
    ];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function officer()
    {
        return $this->belongsTo(RecoveryOfficer::class, 'recovery_officer_id');
    }
}
