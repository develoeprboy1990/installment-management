<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function purchasePartners()
    {
        return $this->hasMany(PurchasePartner::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_partners')
                    ->withPivot('share_amount', 'share_percentage', 'notes')
                    ->withTimestamps();
    }

    /**
     * Total amount invested by this partner across all purchases.
     */
    public function getTotalInvestedAttribute(): float
    {
        return (float) $this->purchasePartners()->sum('share_amount');
    }
}
