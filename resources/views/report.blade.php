@extends('layouts.master')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            {{-- Time Range (AJAX) - matches ibox card UI --}}
            <div class="row m-b-md" style="padding-left: 15px; padding-right: 15px;">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Time Range</h5>
                            <div class="ibox-tools">
                                <small id="rangeLabel" class="text-muted">Showing: This Month</small>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                {{-- Range buttons --}}
                                <div class="col-md-8 col-sm-12 m-b-sm">
                                    <div class="btn-group" id="rangeSwitcher">
                                        <button type="button" class="btn btn-default active" data-range="month">This
                                            Month</button>
                                        <button type="button" class="btn btn-default" data-range="day">Today</button>
                                        <button type="button" class="btn btn-default" data-range="week">This Week</button>
                                        <button type="button" class="btn btn-default" data-range="six_months">Last 6
                                            Months</button>
                                        <button type="button" class="btn btn-default" data-range="year">This Year</button>
                                        <button type="button" class="btn btn-default" data-range="all">All Time</button>
                                    </div>
                                </div>

                                {{-- Custom date range --}}
                                <div class="col-md-4 col-sm-12 text-right">
                                    <form class="form-inline" onsubmit="return false;">
                                        <label class="mr-2">Custom:</label>
                                        <input type="date" id="custom_start" class="form-control">
                                        <span class="mx-2">to</span>
                                        <input type="date" id="custom_end" class="form-control">
                                        <button id="applyCustom" class="btn btn-primary ml-2">Apply</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Range KPI Row (same ibox card style as your old boxes) --}}
            <div class="row" id="rangeKpis" style="padding-left: 15px; padding-right: 15px;">
                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget navy-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-check-circle fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Paid Installments</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiPaidInst">0</h2>
                                @else
                                    <h2 class="font-bold">****</h2>
                                @endif
                                <small>Installments paid in range</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget lazur-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-money fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Collected</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiCollected">Rs. 0</h2>
                                @else
                                    <h2 class="font-bold">Rs. ****</h2>
                                @endif
                                <small>Amount received</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget yellow-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-clock-o fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Pending Amount</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiPendingRange">Rs. 0</h2>
                                @else
                                    <h2 class="font-bold">Rs. ****</h2>
                                @endif
                                <small>Due within range (all shown on hover)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget blue-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-line-chart fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Total Revenue</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiRevenue">Rs. 0</h2>
                                @else
                                    <h2 class="font-bold">Rs. ****</h2>
                                @endif
                                <small>Purchases in range</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget teal-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-area-chart fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Total Profit</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiProfit">Rs. 0</h2>
                                @else
                                    <h2 class="font-bold">Rs. ****</h2>
                                @endif
                                <small>Per-installment profit sum</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget green-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-users fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>New Customers</span>
                                @if (getUserSetting('show_total_revenue') == '1')
                                    <h2 class="font-bold" id="kpiNewCustomers">0</h2>
                                @else
                                    <h2 class="font-bold">****</h2>
                                @endif
                                <small>Joined in range</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 m-b-sm">
                    <div class="widget style1 report-widget red-bg">
                        <div class="report-widget-body">
                            <div class="report-widget-icon">
                                <i class="fa fa-exclamation-triangle fa-3x"></i>
                            </div>
                            <div class="report-widget-metrics text-right">
                                <span>Defaulters</span>
                                <h2 class="font-bold">{{ $data['defaulters_count'] ?? 0 }}</h2>
                                <div class="stat-percent font-bold text-white">{{ number_format($data['defaulters_amount'] ?? 0, 2) }} <i class="fa fa-level-down"></i></div>
                                <small>Total amount due</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Summary Cards -->
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Total Customers</h5>
                        <div class="ibox-tools">
                            <span class="label label-primary pull-right">{{ $data['customers_count'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $data['customers_count'] ?? 0 }}</h1>
                        <div class="stat-percent font-bold text-navy">{{ $data['new_customers_this_month'] ?? 0 }} <i
                                class="fa fa-level-up"></i></div>
                        <small>New customers this month</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Active Purchases</h5>
                        <div class="ibox-tools">
                            <span class="label label-info pull-right">{{ $data['active_purchases'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $data['active_purchases'] ?? 0 }}</h1>
                        <div class="stat-percent font-bold text-info">{{ $data['completed_purchases'] ?? 0 }} <i
                                class="fa fa-check"></i></div>
                        <small>Completed purchases</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Total Revenue</h5>
                    </div>
                    <div class="ibox-content">
                        @if (getUserSetting('show_total_revenue') == '1')
                            <h1 class="no-margins">Rs. {{ number_format($data['total_revenue'] ?? 0, 2) }}</h1>
                        @else
                            <h4>Rs. ****</h4>
                        @endif
                        <div class="stat-percent font-bold text-success">
                            {{ number_format($data['collected_this_month'] ?? 0, 2) }} <i class="fa fa-level-up"></i></div>
                        <small>Collected this month</small>
                    </div>
                </div>
            </div>


            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Total Profit</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            Rs. {{ number_format($data['total_profit'] ?? 0, 2) }}
                        </h1>
                        <small>
                            Last Month Profit: Rs. {{ number_format($data['last_month_profit'] ?? 0, 2) }}
                        </small>
                    </div>
                </div>
            </div>


        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Defaulters</h5>
                    <div class="ibox-tools">
                        <span class="label label-danger pull-right">{{ $data['defaulters_count'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $data['defaulters_count'] ?? 0 }}</h1>
                    <div class="stat-percent font-bold text-danger">{{ number_format($data['defaulters_amount'] ?? 0, 2) }} <i class="fa fa-level-down"></i></div>
                    <small>Total amount due</small>
                </div>
            </div>
        </div>
    </div>

        {{-- <div class="row">
            <!-- Payment Collection Chart -->
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Monthly Collection Trend</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="flot-line-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <!-- Recent Payments -->
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recent Payments</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('installments.index') }}">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data['recent_payments']) && $data['recent_payments']->count() > 0)
                                        @foreach ($data['recent_payments'] as $payment)
                                            <tr>
                                                <td>{{ $payment->date ? Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>{{ $payment->customer->name ?? '-' }}</td>
                                                <td class="text-left">
                                                    {{ number_format($payment->installment_amount ?? 0, 2) }}</td>
                                                <td>{{ $payment->receipt_no ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No recent payments</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <div class="ibox-content">
                    <!-- Date Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="filter_date">Filter by Date:</label>
                            <input type="date" id="filter_date" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" id="clear_filter" class="btn btn-default btn-block">Clear</button>
                        </div>
                    </div>

                    <!-- Scrollable Table Container -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped" id="payments_table">
                            <thead style="position: sticky; top: 0; background-color: #fff; z-index: 10;">
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($data['recent_payments']) && $data['recent_payments']->count() > 0)
                                    @foreach ($data['recent_payments'] as $payment)
                                        <tr data-date="{{ $payment->date ? Carbon\Carbon::parse($payment->date)->format('Y-m-d') : '' }}">
                                            <td>{{ $payment->date ? Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $payment->customer->name ?? '-' }}</td>
                                            <td class="text-left">{{ number_format($payment->installment_amount ?? 0, 2) }}</td>
                                            <td>{{ $payment->receipt_no ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No recent payments</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
            </div>

            <!-- Pending Payments -->
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Due Today</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('installments.index') }}">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data['due_today']) && $data['due_today']->count() > 0)
                                        @foreach ($data['due_today'] as $due)
                                            <tr>
                                                <td>{{ $due->customer->name ?? '-' }}</td>
                                                <td>{{ $due->due_date ? Carbon\Carbon::parse($due->due_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($due->installment_amount ?? 0, 2) }}</td>
                                                <td>
                                                    <span
                                                        class="label label-{{ $due->status == 'paid' ? 'primary' : 'warning' }}">
                                                        {{ ucfirst($due->status ?? 'pending') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No payments due today</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Product Performance -->
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Top Products</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-hover no-margins">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Company</th>
                                    <th>Sales</th>
                                    <th class="text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($data['top_products']) && $data['top_products']->count() > 0)
                                    @foreach ($data['top_products'] as $product)
                                        <tr>
                                            <td>{{ $product->model ?? '-' }}</td>
                                            <td>{{ $product->company ?? '-' }}</td>
                                            <td>{{ $product->sales_count ?? 0 }}</td>
                                            <td class="text-right">Rs.
                                                {{ number_format($product->total_revenue ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No products yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Distribution -->
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Customer Status</h5>
                    </div>
                    <div class="ibox-content">
                        <div>
                            <canvas id="doughnutChart" height="140"></canvas>
                        </div>
                        <div class="text-center">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">Active</small>
                                    <h4>{{ $data['active_customers'] ?? 0 }}</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">Completed</small>
                                    <h4>{{ $data['completed_customers'] ?? 0 }}</h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">Defaulted</small>
                                    <h4>{{ $data['defaulters_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quick Actions -->
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('customers.create') }}" class="btn btn-primary btn-block">
                                    <i class="fa fa-user-plus"></i> Add New Customer
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('products.create') }}" class="btn btn-info btn-block">
                                    <i class="fa fa-cube"></i> Add New Product
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('purchases.create') }}" class="btn btn-success btn-block">
                                    <i class="fa fa-shopping-cart"></i> New Purchase
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('installments.index') }}" class="btn btn-warning btn-block">
                                    <i class="fa fa-credit-card"></i> Process Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #rangeKpis .report-widget {
            border-radius: 6px;
            padding: 20px 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #rangeKpis .report-widget .row {
            align-items: center;
        }

        #rangeKpis .report-widget .report-widget-body {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        #rangeKpis .report-widget .report-widget-icon {
            flex: 0 0 auto;
        }

        #rangeKpis .report-widget .report-widget-metrics {
            flex: 1 1 auto;
        }

        #rangeKpis .report-widget i {
            opacity: 0.4;
            transition: opacity 0.3s ease;
        }

        #rangeKpis .report-widget:hover i {
            opacity: 0.65;
        }

        #rangeKpis .report-widget span {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        #rangeKpis .report-widget small {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            opacity: 0.85;
        }

        #rangeKpis .report-widget .stat-percent {
            margin-top: 6px;
        }

        #rangeKpis .report-widget .font-bold {
            margin: 0;
        }

        #rangeKpis .navy-bg {
            background: linear-gradient(135deg, #1c84c6 0%, #1ab394 100%);
        }

        #rangeKpis .lazur-bg {
            background: linear-gradient(135deg, #23c6c8 0%, #1ab394 100%);
        }

        #rangeKpis .yellow-bg {
            background: linear-gradient(135deg, #f8ac59 0%, #f7aa30 100%);
            color: #3d2f1b;
        }

        #rangeKpis .blue-bg {
            background: linear-gradient(135deg, #1c84c6 0%, #23c6c8 100%);
        }

        #rangeKpis .teal-bg {
            background: linear-gradient(135deg, #1ab394 0%, #00a07d 100%);
        }

        #rangeKpis .green-bg {
            background: linear-gradient(135deg, #1cbb90 0%, #1ab394 100%);
        }

        #rangeKpis .red-bg {
            background: linear-gradient(135deg, #ed5565 0%, #d62d3a 100%);
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Flot Chart
            var data1 = [
                @if (isset($data['monthly_collections']))
                    @foreach ($data['monthly_collections'] as $month => $amount)
                        ["{{ $month }}", {{ $amount ?? 0 }}],
                    @endforeach
                @endif
            ];

            if (data1.length > 0 && typeof $.plot === 'function') {
                $.plot($("#flot-line-chart"), [{
                    data: data1,
                    label: "Monthly Collections",
                    color: "#1ab394"
                }], {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 2,
                            fill: true,
                            fillColor: {
                                colors: [{ opacity: 0.0 }, { opacity: 0.2 }]
                            }
                        },
                        points: { radius: 4, show: true }
                    },
                    grid: {
                        borderWidth: 0,
                        borderColor: '#f0f0f0',
                        labelMargin: 10,
                        hoverable: true,
                        clickable: true,
                        mouseActiveRadius: 6
                    },
                    xaxis: {
                        ticks: data1.map(function(item, index) { return [index, item[0]]; }),
                        color: "transparent"
                    },
                    yaxis: {
                        color: "transparent",
                        tickFormatter: function(val) { return "Rs. " + val.toFixed(0); }
                    },
                    legend: { show: false }
                });
            }

            // Doughnut Chart
            var doughnutData = {
                labels: ["Active", "Completed", "Defaulted"],
                datasets: [{
                    data: [{{ $data['active_customers'] ?? 0 }},
                        {{ $data['completed_customers'] ?? 0 }},
                        {{ $data['defaulters_count'] ?? 0 }}
                    ],
                    backgroundColor: ["#1ab394", "#23c6c8", "#f8ac59"]
                }]
            };

            var doughnutOptions = {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                }
            };

            function isChartV3() {
                try { return parseInt((Chart.version || '3').split('.')[0]) >= 3; } catch(e) { return true; }
            }

            function initReportCharts() {
                var doughnutEl = document.getElementById("doughnutChart");
                if (doughnutEl) {
                    var ctx = doughnutEl.getContext("2d");
                    var doughnutOpts = isChartV3() ? {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    } : {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false }
                    };
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: doughnutData,
                        options: doughnutOpts
                    });
                }

                // 4-slice Active Data Pie (if present)
                var activePieCanvas = document.getElementById('activePie');
                if (activePieCanvas) {
                    var activePieCtx = activePieCanvas.getContext('2d');
                    var a = {{ (int)($data['active_customers'] ?? 0) }};
                    var c = {{ (int)($data['completed_customers'] ?? 0) }};
                    var d = {{ (int)($data['defaulters_count'] ?? 0) }};
                    var n = {{ (int)($data['new_customers_this_month'] ?? 0) }};
                    var sum = a + c + d + n;
                    if (sum <= 0) {
                        // Avoid rendering empty pie; show a friendly message
                        activePieCanvas.insertAdjacentHTML('afterend', '<div class="text-center text-muted" style="padding-top:10px;">No data to display</div>');
                        return;
                    }
                    var pieData = {
                        labels: ['Active', 'Completed', 'Defaulters', 'New'],
                        datasets: [{
                            data: [a, c, d, n],
                            backgroundColor: ['#1ab394', '#23c6c8', '#f8ac59', '#ed5565']
                        }]
                    };
                    var pieOpts = isChartV3() ? {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    } : {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { position: 'bottom' }
                    };
                    new Chart(activePieCtx, {
                        type: 'pie',
                        data: pieData,
                        options: pieOpts
                    });
                }
            }

            // Kickoff (Chart.js loaded from master layout)
            if (window.Chart) { initReportCharts(); } else { console.error('Chart.js not found. Ensure it is included in master layout.'); }
        });
    </script>
@endpush
@push('script')
    <script>
        function fmt(n) {
            n = Number(n || 0);
            return n.toLocaleString(undefined, {
                maximumFractionDigits: 0
            });
        }

        function money(n) {
            n = Number(n || 0);
            return 'Rs. ' + n.toLocaleString(undefined, {
                minimumFractionDigits: 0
            });
        }

        function humanRange(r, meta) {
            switch (r) {
                case 'day':
                    return 'Today';
                case 'week':
                    return 'This Week';
                case 'month':
                    return 'This Month';
                case 'six_months':
                    return 'Last 6 Months';
                case 'year':
                    return 'This Year';
                case 'all':
                    return 'All Time';
                case 'custom':
                    return 'Custom: ' + (meta.start || '') + ' to ' + (meta.end || '');
                default:
                    return 'This Month';
            }
        }

        function plotCollections(series) {
            if (!series || !series.length || typeof $.plot !== 'function') {
                $("#flot-line-chart").empty();
                return;
            }
            // Convert ["YYYY-MM"/"YYYY-MM-DD", value] into index ticks for Flot
            var ticks = [],
                data = [];
            for (var i = 0; i < series.length; i++) {
                ticks.push([i, series[i][0]]);
                data.push([i, parseFloat(series[i][1])]);
            }

            $.plot($("#flot-line-chart"), [{
                data: data,
                label: "Collections",
                color: "#1ab394"
            }], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.0
                            }, {
                                opacity: 0.2
                            }]
                        }
                    },
                    points: {
                        show: true,
                        radius: 4
                    }
                },
                grid: {
                    borderWidth: 0,
                    labelMargin: 10,
                    hoverable: true,
                    clickable: true,
                    mouseActiveRadius: 6
                },
                xaxis: {
                    ticks: ticks,
                    color: "transparent"
                },
                yaxis: {
                    color: "transparent",
                    tickFormatter: function(val) {
                        return "Rs. " + Math.round(val);
                    }
                },
                legend: {
                    show: false
                }
            });
        }

        function loadMetrics(params) {
            $.get("{{ route('admin.report.metrics') }}", params, function(resp) {
                // KPIs
                $("#kpiPaidInst").text(fmt(resp.kpis.paid_installments_count));
                $("#kpiCollected").text(money(resp.kpis.collected));
                $("#kpiPendingRange").text(money(resp.kpis.pending_in_range) + " (all: " + money(resp.kpis
                    .pending_all) + ")");
                $("#kpiRevenue").text(money(resp.kpis.total_revenue));
                $("#kpiProfit").text(money(resp.kpis.total_profit));
                $("#kpiNewCustomers").text(fmt(resp.kpis.customers_new));

                $("#rangeLabel").text("Showing: " + humanRange(params.range || 'month', resp.meta));

                // Chart
                plotCollections(resp.series.collections);

                // Update the Range Pie chart (4 segments)
                updateRangePie(resp.kpis);
            });
        }

        // ---- Range Pie (4 segments) using KPIs per selected range ----
        var rangePieInstance = null;
        function updateRangePie(kpis) {
            var canvas = document.getElementById('rangePie');
            if (!canvas || !window.Chart) return;

            var ctx = canvas.getContext('2d');
            var labels = ['Collected', 'Pending (Range)', 'Revenue', 'Profit'];
            var values = [
                Number(kpis.collected || 0),
                Number(kpis.pending_in_range || 0),
                Number(kpis.total_revenue || 0),
                Number(kpis.total_profit || 0)
            ];

            // If all zeros, show message once
            if (values.reduce((a,b)=>a+b,0) <= 0) {
                if (!canvas.dataset.msgShown) {
                    canvas.insertAdjacentHTML('afterend', '<div class="text-center text-muted" style="padding-top:10px;">No data to display for selected range</div>');
                    canvas.dataset.msgShown = '1';
                }
                if (rangePieInstance) { rangePieInstance.destroy(); rangePieInstance = null; }
                return;
            }

            var data = {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#1ab394', '#f8ac59', '#23c6c8', '#ed5565']
                }]
            };

            var opts = (function(){
                var v = (window.Chart && Chart.version) ? parseInt(Chart.version.split('.')[0]) : 3;
                if (v >= 3) {
                    return { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } };
                }
                return { responsive: true, maintainAspectRatio: false, legend: { position: 'bottom' } };
            })();

            if (rangePieInstance) {
                rangePieInstance.data = data;
                rangePieInstance.options = opts;
                rangePieInstance.update();
            } else {
                rangePieInstance = new Chart(ctx, { type: 'pie', data: data, options: opts });
            }
        }

        $(document).ready(function() {
            // Default load: month
            loadMetrics({
                range: 'month'
            });

            // Range buttons
            $("#rangeSwitcher").on('click', 'button[data-range]', function() {
                $("#rangeSwitcher button").removeClass('active');
                $(this).addClass('active');
                var r = $(this).data('range');
                loadMetrics({
                    range: r
                });
            });

            // Custom range
            $("#applyCustom").on('click', function(e) {
                e.preventDefault();
                var s = $("#custom_start").val();
                var eDate = $("#custom_end").val();
                if (!s || !eDate) {
                    alert('Please select both start and end date');
                    return;
                }
                $("#rangeSwitcher button").removeClass('active');
                loadMetrics({
                    range: 'custom',
                    start_date: s,
                    end_date: eDate
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const filterDate = document.getElementById('filter_date');
            const clearFilter = document.getElementById('clear_filter');
            const tableRows = document.querySelectorAll('#payments_table tbody tr[data-date]');

            filterDate.addEventListener('change', function() {
                const selectedDate = this.value;
                
                tableRows.forEach(row => {
                    const rowDate = row.getAttribute('data-date');
                    if (selectedDate === '' || rowDate === selectedDate) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            clearFilter.addEventListener('click', function() {
                filterDate.value = '';
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            });
        });
</script>
@endpush
