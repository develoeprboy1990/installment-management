<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Installment;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    function report(){
        // Initialize data array with default values
        $data = [
            'customers_count' => 0,
            'new_customers_this_month' => 0,
            'active_purchases' => 0,
            'completed_purchases' => 0,
            'total_revenue' => 0,
            'collected_this_month' => 0,
            'defaulters_count' => 0,
            'defaulters_amount' => 0,
            'recent_payments' => collect([]),
            'due_today' => collect([]),
            'top_products' => collect([]),
            'active_customers' => 0,
            'completed_customers' => 0,
            'monthly_collections' => collect([])
        ];

        try {
            // Customer Statistics
            $data['customers_count'] = Customer::count();
            $data['new_customers_this_month'] = Customer::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Purchase Statistics
            $data['active_purchases'] = Purchase::where('status', 'active')->count();
            $data['completed_purchases'] = Purchase::where('status', 'completed')->count();

            // Revenue Statistics
            $data['total_revenue'] = Purchase::sum('total_price') ?? 0;
            $data['collected_this_month'] = Installment::where('status', 'paid')
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('installment_amount') ?? 0;

            // Defaulter Statistics
            $data['defaulters_count'] = Customer::where('is_defaulter', true)->count();
            $data['defaulters_amount'] = 0; // Since status 'overdue' might not exist yet
            try {
                $data['defaulters_amount'] = Installment::where('status', 'overdue')
                    ->sum('installment_amount') ?? 0;
            } catch (\Exception $e) {
                // Keep default value of 0 if there's an error
            }

            // Recent Payments (last 5)
            $data['recent_payments'] = Installment::where('status', 'paid')
                ->with('customer')
                ->orderBy('date', 'desc')
                ->limit(100)
                ->get();

            // Due Today
            $data['due_today'] = Installment::whereDate('due_date', today())
                ->with('customer')
                ->orderBy('due_date')
                ->limit(5)
                ->get();

            // Top Products
            $data['top_products'] = Product::select('products.*')
                ->selectRaw('COUNT(purchases.id) as sales_count')
                ->selectRaw('COALESCE(SUM(purchases.total_price), 0) as total_revenue')
                ->leftJoin('purchases', 'products.id', '=', 'purchases.product_id')
                ->groupBy('products.id', 'products.company', 'products.model', 'products.serial_no', 'products.price')
                ->orderBy('sales_count', 'desc')
                ->limit(5)
                ->get();

            // Customer Distribution
            $data['active_customers'] = Customer::whereHas('purchases', function($query) {
                $query->where('status', 'active');
            })->count();

            $data['completed_customers'] = Customer::whereDoesntHave('purchases', function($query) {
                $query->where('status', 'active');
            })->where('is_defaulter', false)->count();

            // Monthly Collections for chart (last 6 months)
            $data['monthly_collections'] = Installment::where('status', 'paid')
                ->where('date', '>=', now()->subMonths(6))
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month')
                ->selectRaw('COALESCE(SUM(installment_amount), 0) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $data['total_profit'] = Installment::where('status', 'paid')
                ->with('purchase.product')
                ->get()
                ->reduce(function($carry, $inst) {
                    $purchase = $inst->purchase;
                    $product  = $purchase->product;

                    // total profit for this purchase
                    $totalProfit     = $product->price - $product->cost_price;
                    $installmentsQty = $purchase->installment_months ?: 1;

                    // profit per paid installment
                    $profitPerInst = $totalProfit / $installmentsQty;

                    return $carry + $profitPerInst;
                }, 0);

            $data['last_month_profit'] = Installment::where('status','paid')
                ->whereYear('date', now()->subMonth()->year)
                ->whereMonth('date', now()->subMonth()->month)
                ->with('purchase.product')
                ->get()
                ->reduce(function($carry, $inst) {
                    $purchase = $inst->purchase;
                    $product  = $purchase->product;

                    $totalProfit     = $product->price - $product->cost_price;
                    $installmentsQty = $purchase->installment_months ?: 1;

                    $profitPerInst = $totalProfit / $installmentsQty;

                    return $carry + $profitPerInst;
                }, 0);

        } catch (\Exception $e) {
            // Log the error and return default values
            \Log::error('Dashboard Error: ' . $e->getMessage());
            // Data is already initialized with default values
        }

        return view('report', compact('data'));
    }

    public function metrics(Request $request)
{
    $range     = $request->input('range', 'month'); // day|week|month|six_months|year|all|custom
    $startDate = $request->input('start_date');
    $endDate   = $request->input('end_date');

    // --- Resolve start/end for the chosen range ---
    [$start, $end, $groupBy] = $this->resolveRange($range, $startDate, $endDate);

    // Paid installments (count + sum)
    $paidBase = Installment::where('status', 'paid');
    if ($start && $end) {
        $paidBase->whereBetween('date', [$start, $end]);
    }
    $paid_installments_count = (clone $paidBase)->count();
    $collected_amount        = (clone $paidBase)->sum('installment_amount') ?? 0;

    // Pending revenue (due in range) and all-time pending
    $pendingInRange = Installment::where('status', 'pending');
    if ($start && $end) {
        $pendingInRange->whereBetween('due_date', [$start, $end]);
    }
    $pending_revenue_in_range = $pendingInRange->sum('installment_amount') ?? 0;
    $pending_revenue_all      = Installment::where('status','pending')->sum('installment_amount') ?? 0;

    // Customers (new in range) + total customers (all time)
    $customers_count_in_range = Customer::when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))->count();
    $total_customers          = Customer::count();

    // Total revenue (purchases in range) — using purchase_date if present, fallback to created_at
    $revenueBase = Purchase::query();
    if ($start && $end) {
        $revenueBase->where(function ($q) use ($start, $end) {
            $q->whereBetween('purchase_date', [$start, $end])
              ->orWhere(function ($qq) use ($start, $end) {
                  $qq->whereNull('purchase_date')->whereBetween('created_at', [$start, $end]);
              });
        });
    }
    $total_revenue_in_range = $revenueBase->sum('total_price') ?? 0;

    // Total profit (range) — SQL sum of per-installment profit
    $profitQuery = Installment::query()
        ->join('purchases', 'installments.purchase_id', '=', 'purchases.id')
        ->join('products', 'products.id', '=', 'purchases.product_id')
        ->where('installments.status', 'paid');

    if ($start && $end) {
        $profitQuery->whereBetween('installments.date', [$start, $end]);
    }

    $total_profit_in_range = (float) $profitQuery->value(DB::raw(
        'SUM( (products.price - products.cost_price) / IFNULL(NULLIF(purchases.installment_months,0),1) )'
    )) ?? 0.0;// sum over (price - cost_price) / installment_months (default 1 if null/0)
        $total_profit_in_range = (float) $profitQuery
    ->selectRaw('
        SUM(
            (COALESCE(products.price, 0) - COALESCE(products.cost_price, 0))
            / NULLIF(COALESCE(purchases.installment_months, 1), 0)
        ) AS profit_total
    ')
    ->value('profit_total') ?? 0.0;

    // Time-series for chart (collections by day/month depending on range)
    $series = $this->collectionsSeries($start, $end, $groupBy);

    return response()->json([
        'meta' => [
            'range' => $range,
            'start' => $start ? $start->toDateTimeString() : null,
            'end'   => $end ? $end->toDateTimeString() : null,
            'group' => $groupBy, // day|month
        ],
        'kpis' => [
            'paid_installments_count' => (int) $paid_installments_count,
            'customers_new'           => (int) $customers_count_in_range,
            'customers_total'         => (int) $total_customers,
            'collected'               => (float) $collected_amount,
            'pending_in_range'        => (float) $pending_revenue_in_range,
            'pending_all'             => (float) $pending_revenue_all,
            'total_revenue'           => (float) $total_revenue_in_range,
           'total_profit'             => $total_profit_in_range,
        ],
        'series' => [
            'collections' => $series, // [["2025-09-01", 12345], ...]
        ],
    ]);
}

/**
 * Resolve date range and grouping unit for charts
 */
private function resolveRange(string $range, $startDate = null, $endDate = null): array
{
    $now = now();
    $start = $end = null;
    $groupBy = 'day';

    switch ($range) {
        case 'day':
            $start = $now->copy()->startOfDay();
            $end   = $now->copy()->endOfDay();
            $groupBy = 'day';
            break;
        case 'week':
            $start = $now->copy()->startOfWeek(); // Mon by default
            $end   = $now->copy()->endOfWeek();
            $groupBy = 'day';
            break;
        case 'month':
            $start = $now->copy()->startOfMonth();
            $end   = $now->copy()->endOfMonth();
            $groupBy = 'day';
            break;
        case 'six_months':
            $start = $now->copy()->subMonths(6)->startOfDay();
            $end   = $now->copy()->endOfDay();
            $groupBy = 'month';
            break;
        case 'year':
            $start = $now->copy()->startOfYear();
            $end   = $now->copy()->endOfYear();
            $groupBy = 'month';
            break;
        case 'custom':
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfDay();
                $end   = Carbon::parse($endDate)->endOfDay();
                // choose grouping based on span
                $groupBy = $start->diffInDays($end) > 92 ? 'month' : 'day';
            }
            break;
        case 'all':
        default:
            // no start/end => whole history
            $start = null;
            $end   = null;
            $groupBy = 'month';
            break;
    }
    return [$start, $end, $groupBy];
}

/**
 * Build collection time-series for the chart
 */
private function collectionsSeries(?Carbon $start, ?Carbon $end, string $groupBy): array
{
    $q = Installment::where('status', 'paid');

    if ($start && $end) {
        $q->whereBetween('date', [$start, $end]);
    }

    if ($groupBy === 'day') {
        $rows = $q->selectRaw('DATE(date) as d, COALESCE(SUM(installment_amount),0) as total')
                  ->groupBy('d')
                  ->orderBy('d')
                  ->get();
        return $rows->map(fn($r) => [ (string)$r->d, (float)$r->total ])->all();
    }

    // month grouping
    $rows = $q->selectRaw('DATE_FORMAT(date, "%Y-%m") as m, COALESCE(SUM(installment_amount),0) as total')
              ->groupBy('m')
              ->orderBy('m')
              ->get();

    return $rows->map(fn($r) => [ (string)$r->m, (float)$r->total ])->all();
}

}
