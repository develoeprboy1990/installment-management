<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'status',
        'subscription_expires_at',
    ];

    protected $casts = [
        'subscription_expires_at' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function recoveryOfficers()
    {
        return $this->hasMany(RecoveryOfficer::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // ==================== HELPERS ====================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Get summary stats for this tenant (used in SuperAdmin dashboard).
     */
    public function getSummary(): array
    {
        return [
            'total_customers'    => $this->customers()->count(),
            'total_products'     => $this->products()->count(),
            'total_purchases'    => $this->purchases()->count(),
            'total_installments' => $this->installments()->count(),
            'total_users'        => $this->users()->count(),
            'pending_installments' => $this->installments()->where('status', 'pending')->count(),
            'paid_installments'    => $this->installments()->where('status', 'paid')->count(),
        ];
    }
}
