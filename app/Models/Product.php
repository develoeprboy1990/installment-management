<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        // Removed 'customer_id'
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
