<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses
     */
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'expense_type' => 'required|in:rent,salary,utilities,maintenance,other',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,cancelled',
            'payment_method' => 'nullable|string|max:255',
        ]);

        Expense::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully!'
        ]);
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense)
    {
        return response()->json([
            'success' => true,
            'expense' => [
                'id' => $expense->id,
                'name' => $expense->name,
                'email' => $expense->email,
                'phone' => $expense->phone,
                'expense_type' => $expense->expense_type,
                'formatted_type' => $expense->formatted_type,
                'amount' => number_format($expense->amount, 2),
                'expense_date' => $expense->expense_date->format('d M, Y'),
                'description' => $expense->description,
                'status' => $expense->status,
                'formatted_status' => $expense->formatted_status,
                'payment_method' => $expense->payment_method,
                'created_at' => $expense->created_at->format('d M, Y h:i A'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified expense
     */
    public function edit(Expense $expense)
    {
        return response()->json([
            'success' => true,
            'expense' => [
                'id' => $expense->id,
                'name' => $expense->name,
                'email' => $expense->email,
                'phone' => $expense->phone,
                'expense_type' => $expense->expense_type,
                'amount' => $expense->amount,
                'expense_date' => $expense->expense_date->format('Y-m-d'),
                'description' => $expense->description,
                'status' => $expense->status,
                'payment_method' => $expense->payment_method,
            ]
        ]);
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'expense_type' => 'required|in:rent,salary,utilities,maintenance,other',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,cancelled',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $expense->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully!'
        ]);
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}
