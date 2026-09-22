<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasePartner extends Model
{
    use HasFactory;

    protected $table = 'purchase_partners';

    protected $fillable = [
        'purchase_id',
        'partner_id',
        'share_amount',
        'share_percentage',
        'notes',
    ];

    protected $casts = [
        'share_amount'     => 'float',
        'share_percentage' => 'float',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Calculate how much this partner has received so far
     * based on their percentage of total payments received.
     */
    public function getAmountReceivedAttribute(): float
    {
        $purchase  = $this->purchase;
        if (!$purchase) return 0;

        $totalPaid = (float) $purchase->total_paid_amount;
        return round($totalPaid * ($this->share_percentage / 100), 2);
    }

    /**
     * How much is still pending for this partner.
     */
    public function getAmountPendingAttribute(): float
    {
        return max(0, $this->share_amount - $this->getAmountReceivedAttribute());
    }
}
