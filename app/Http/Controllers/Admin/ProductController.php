<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();  // Removed 'with('customer')'
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // No need to pass customers anymore
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Removed 'customer_id' validation
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function show(Product $product)
    {
        // Optional: Show product details with purchase history
        $product->load('purchases.customer');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // No need to pass customers anymore
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            // Removed 'customer_id' validation
            'company' => 'required',
            'model' => 'required',
            'serial_no' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Check if product has been purchased before deleting
        if ($product->purchases()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product. It has purchase history.');
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Get all customers who purchased this product
     */
    public function getCustomers(Product $product)
    {
        $purchases = $product->purchases()
            ->with('customer')
            ->get();

        $customersData = $purchases->map(function ($purchase) use ($product) {
            return [
                'customer_id' => $purchase->customer->id,
                'customer_name' => $purchase->customer->name,
                'customer_nic' => $purchase->customer->nic,
                'customer_mobile_1' => $purchase->customer->mobile_1,
                'customer_mobile_2' => $purchase->customer->mobile_2,
                'customer_father_name' => $purchase->customer->father_name,
                'customer_residence' => $purchase->customer->residence,
                'customer_occupation' => $purchase->customer->occupation,
                'purchase_id' => $purchase->id,
                'purchase_date' => $purchase->purchase_date->format('d M, Y'),
                'total_price' => number_format($purchase->total_price, 2),
                'advance_payment' => number_format($purchase->advance_payment, 2),
                'remaining_balance' => number_format($purchase->remaining_balance, 2),
                'installment_months' => $purchase->installment_months,
                'monthly_installment' => number_format($purchase->monthly_installment, 2),
                'status' => $purchase->status,
            ];
        });

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'company' => $product->company,
                'model' => $product->model,
                'serial_no' => $product->serial_no,
                'cost_price' => number_format($product->cost_price, 2),
                'sell_price' => number_format($product->price, 2),
            ],
            'customers' => $customersData,
            'total_customers' => $customersData->count(),
        ]);
    }
}