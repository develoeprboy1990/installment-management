<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, HasTenant, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'company',
        'model',
        'serial_no',
        'cost_price',
        'price',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Optional: Get all customers who have purchased this product
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Purchase::class);
    }
}
