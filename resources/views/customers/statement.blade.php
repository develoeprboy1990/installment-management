@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title no-print">
                    <h5>Customer Statement</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary">Back to Customers</a>
                        <button class="btn btn-sm btn-info" onclick="window.print()">
                            <i class="fa fa-print"></i> Print
                        </button>
                        @if($customer->purchases->count() > 0)
                            <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-sm btn-success">New Purchase</a>
                        @endif
                    </div>
                </div>
                <div class="ibox-content">
                    @php
                        // Calculate all financial values from relationships
                        $totalPurchases = $customer->purchases->count();
                        $totalPurchaseAmount = $customer->purchases->sum('total_price');
                        $totalAdvancePayments = $customer->purchases->sum('advance_payment');
                        $totalPaidInstallments = $customer->installments()->where('status', 'paid')->sum('installment_amount');
                        $totalInstallmentDiscount = $customer->installments()->where('status', 'paid')->sum('discount');
                        $totalPaidAmount = $totalAdvancePayments + $totalPaidInstallments + $totalInstallmentDiscount;
                        $currentBalance = max(0, $totalPurchaseAmount - $totalPaidAmount);
                        $totalMonthlyInstallments = $customer->purchases()->where('status', 'active')->sum('monthly_installment');
                        $pendingInstallments = $customer->installments()->where('status', 'pending')->count();
                        $overdueInstallments = $customer->installments()->where('status', 'pending')->where('due_date', '<', now())->count();

                        // Status calculation
                        $customerStatus = 'ACTIVE';
                        if ($totalPurchases == 0) {
                            $customerStatus = 'NO PURCHASES';
                        } elseif ($currentBalance <= 0) {
                            $customerStatus = 'COMPLETED';
                        }
                        // elseif ($overdueInstallments > 0) {
                        //     $customerStatus = 'DEFAULTER';
                        // }
                        // Determine purchase date from the first purchase if available
                        $purchaseDate = optional($customer->purchases->first())->purchase_date;
                    @endphp

                    <!-- Header with Company Info -->
                    <div class="statement-header">
                        <div class="company-info">
                            <h3> <strong>{{ getUserSetting('project_name') ?? 'Electronics Corporation' }} </strong></h3>
                            <div class="print-info">
                                <div>Print Date: {{ now()->format('M j ,Y & h:i A') ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info with Photos Section -->
                    <div class="customer-section">
                        <div class="customer-basic-info">
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Account No:</strong> {{ $customer->account_no }}
                                </div>
                                <div class="info-item">
                                    <strong>Purchase Date:</strong> {{ $purchaseDate ? $purchaseDate->format('d-M-Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Customer:</strong> {{ $customer->name }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>F/H Name:</strong> {{ $customer->father_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Occupation:</strong> {{ $customer->occupation ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item full-width">
                                    <strong>Residence:</strong> {{ $customer->residence ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item full-width">
                                    <strong>Off. Address:</strong> {{ $customer->office_address ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Customer Photo -->
                        <div class="customer-photo-section">
                            @if($customer->image)
                                <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer" class="customer-img">
                            @else
                                <div class="customer-placeholder">{{ strtoupper(substr($customer->name, 0, 2)) }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Financial and Product Summary -->
                    @if($totalPurchases > 0)
                    @php $firstPurchase = $customer->purchases->first(); @endphp
                    <div class="financial-section">
                        <div class="financial-panel financial-left">
                            <div class="summary-panel-title">Financial Summary</div>
                            <div class="info-group">
                                <div class="info-row">
                                    <div class="info-item"><span>Mobile #1</span><strong>{{ $customer->mobile_1 }}</strong></div>
                                    <div class="info-item"><span>Company</span><strong>{{ $firstPurchase->product->company ?? 'N/A' }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><span>Mobile #2</span><strong>{{ $customer->mobile_2 ?? 'N/A' }}</strong></div>
                                    <div class="info-item"><span>Product</span><strong>{{ $firstPurchase->product->model ?? 'N/A' }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><span>NIC</span><strong>{{ $customer->nic }}</strong></div>
                                    <div class="info-item"><span>Model</span><strong>{{ $firstPurchase->product->model ?? 'N/A' }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><span>Gender</span><strong>{{ ucfirst($customer->gender ?? 'N/A') }}</strong></div>
                                    <div class="info-item"><span>Serial #</span><strong>{{ $firstPurchase->product->serial_no ?? 'N/A' }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item amount-item"><span>Purchase Price</span><strong>{{ number_format($totalPurchaseAmount, 0) }}</strong></div>
                                    <div class="info-item amount-item"><span>Monthly Installment</span><strong>{{ number_format($totalMonthlyInstallments, 0) }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item amount-item"><span>Advance Payment</span><strong>{{ number_format($totalAdvancePayments, 0) }}</strong></div>
                                    <div class="info-item"><span>Duration (Months)</span><strong>{{ $firstPurchase->installment_months ?? 0 }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item amount-item positive-item"><span>Total Paid</span><strong>{{ number_format($totalPaidAmount, 0) }}</strong></div>
                                    <div class="info-item"><span>Paid Installments</span><strong>{{ $customer->installments()->where('status', 'paid')->count() }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item amount-item balance-item"><span>Remaining Balance</span><strong>{{ number_format($currentBalance, 0) }}</strong></div>
                                    <div class="info-item"><span>Pending Installments</span><strong>{{ $pendingInstallments }}</strong></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item status-item"><span>Status</span><strong>{{ $customerStatus }}</strong></div>
                                    @if($overdueInstallments > 0)
                                        <div class="info-item overdue-item"><span>Overdue</span><strong>{{ $overdueInstallments }} installments</strong></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="financial-panel financial-right">
                            <div class="summary-panel-title">Product Details</div>
                            <div class="product-info">
                                @if($firstPurchase)
                                    <div class="info-item"><span>Company</span><strong>{{ $firstPurchase->product->company ?? 'N/A' }}</strong></div>
                                    <div class="info-item"><span>Model</span><strong>{{ $firstPurchase->product->model ?? 'N/A' }}</strong></div>
                                    <div class="info-item"><span>Serial No</span><strong>{{ $firstPurchase->product->serial_no ?? 'N/A' }}</strong></div>
                                    <div class="info-item amount-item"><span>Product Price</span><strong>Rs. {{ number_format($firstPurchase->product->price ?? 0, 0) }}</strong></div>
                                @else
                                    <div class="info-item"><span>Product</span><strong>No purchase yet</strong></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guarantors Section -->
                    @if($customer->guarantors->count() > 0)
                    <div class="guarantors-section">
                        <div class="table-scroll-mobile">
                            <table class="guarantor-table">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <th>
                                                Guarantor # {{ $guarantor->guarantor_no }}
                                                <div class="guarantor-photo-in-header">
                                                    @if($guarantor->image)
                                                        <img src="{{ asset($guarantor->image) }}" alt="Guarantor {{ $guarantor->guarantor_no }}" class="guarantor-img-small">
                                                    @else
                                                        <div class="guarantor-placeholder-small">G{{ $guarantor->guarantor_no }}</div>
                                                    @endif
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->name }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>F/H Name:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->father_name }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->phone }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>NIC:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->nic }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Residence:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ substr($guarantor->residence_address, 0, 40) }}{{ strlen($guarantor->residence_address) > 40 ? '...' : '' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Office:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->office_address ? substr($guarantor->office_address, 0, 40) . (strlen($guarantor->office_address) > 40 ? '...' : '') : 'N/A' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Occupation:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->occupation ?? 'N/A' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td><strong>Relation:</strong></td>
                                        @foreach($customer->guarantors->take(4) as $guarantor)
                                            <td>{{ $guarantor->relation }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Payment History Table (Compact) -->
                    @if($customer->installments->count() > 0)
                    <div class="payment-history">
                        <div class="table-scroll-mobile">
                            <table class="payment-table">
                                <thead>
                                    <tr>
                                        <th>S.#</th>
                                        <th>Date</th>
                                        <th>Rcv. #</th>
                                        <th>Pre-Bal</th>
                                        <th>Install.</th>
                                        <th>Disc</th>
                                        <th>Balance</th>
                                        {{-- <th>Fine</th> --}}
                                        {{-- <th>F-Type</th> --}}
                                        <th>Recovery Officer</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->installments()->where('status' , 'paid')->orderBy('due_date')->get() as $index => $installment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $installment->date ? $installment->date->format('d/m/Y') : $installment->due_date->format('d/m/Y') }}</td>
                                        <td>{{ $installment->receipt_no ?? substr($installment->id, -6) }}</td>
                                        <td>{{ number_format($installment->pre_balance, 0) }}</td>
                                        <td>{{ number_format($installment->installment_amount, 0) }}</td>
                                        <td>{{ $installment->discount ?? 0 }}</td>
                                        <td>{{ number_format($installment->balance, 0) }}</td>
                                        {{-- <td>{{ $installment->fine_amount ?? 0 }}</td> --}}
                                        {{-- <td>{{ $installment->status == 'paid' ? 'Nothing' : 'Pending' }}</td> --}}
                                        <td>{{ $installment->officer?->name ?? 'N/A' }}</td>
                                        <td>{{ $installment->status == 'paid' ? 'Paid' : 'P' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($totalPurchases == 0)
                    <div class="no-purchase-alert">
                        <h4>No Purchase History</h4>
                        <p>This customer has not made any purchases yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Print optimized styles */
    @page {
        size: A4;
        margin: 0.5in;
    }

    .statement-header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .statement-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .print-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        margin-top: 5px;
    }

    .customer-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        border: 1px solid #000;
        padding: 10px;
    }

    .customer-basic-info {
        flex: 1;
        font-size: 12px;
    }

    .customer-photo-section {
        width: 120px;
        text-align: center;
    }

    .customer-img, .customer-placeholder {
        width: 100px;
        height: 120px;
        border: 1px solid #000;
        object-fit: cover;
        border-radius: 10px;
    }

    .customer-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 24px;
    }

    .info-row {
        display: flex;
        margin-bottom: 3px;
    }

    .info-item {
        flex: 1;
        font-size: 11px;
        margin-right: 10px;
    }

    .info-item.full-width {
        flex: 3;
    }

    .financial-section {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 11px;
    }

    .financial-panel {
        border: 1px solid #222;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
    }

    .financial-left {
        flex: 2;
    }

    .financial-right {
        flex: 1;
    }

    .summary-panel-title {
        padding: 6px 10px;
        border-bottom: 1px solid #222;
        background: #f0f0f0;
        color: #111;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .financial-panel .info-group,
    .financial-panel .product-info {
        padding: 8px 10px;
    }

    .financial-panel .info-row {
        gap: 8px;
        margin-bottom: 0;
        border-bottom: 1px solid #e2e2e2;
    }

    .financial-panel .info-row:last-child,
    .financial-panel .product-info .info-item:last-child {
        border-bottom: none;
    }

    .financial-panel .info-item {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        align-items: center;
        min-height: 25px;
        margin-right: 0;
        padding: 4px 0;
        border-bottom: none;
    }

    .financial-panel .product-info .info-item {
        border-bottom: 1px solid #e2e2e2;
    }

    .financial-panel .info-item span {
        color: #555;
        font-weight: 600;
    }

    .financial-panel .info-item strong {
        color: #111;
        text-align: right;
        word-break: break-word;
    }

    .amount-item strong,
    .positive-item strong,
    .balance-item strong {
        font-weight: 800;
    }

    .positive-item strong {
        color: #1f7a3f;
    }

    .balance-item strong,
    .overdue-item strong {
        color: #b42318;
    }

    .status-item strong {
        display: inline-block;
        padding: 2px 8px;
        border: 1px solid #222;
        border-radius: 999px;
        font-size: 10px;
        letter-spacing: 0;
    }

    .guarantors-section {
        margin-bottom: 15px;
        position: relative;
    }

    .guarantor-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-bottom: 10px;
    }

    .guarantor-table th,
    .guarantor-table td {
        border: 1px solid #000;
        padding: 3px;
        text-align: left;
    }

    .guarantor-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .guarantor-photo-in-header {
        margin-top: 5px;
    }

    .guarantor-img-small, .guarantor-placeholder-small {
        width: 50px;
        height: 60px;
        border: 1px solid #000;
        border-radius: 5px;
        object-fit: cover;
    }

    .guarantor-placeholder-small {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 12px;
    }

    .payment-history {
        margin-bottom: 15px;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }

    .payment-table th,
    .payment-table td {
        border: 1px solid #000;
        padding: 2px;
        text-align: center;
    }

    .payment-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .table-scroll-mobile {
        width: 100%;
    }

    .no-purchase-alert {
        text-align: center;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    /* Hide elements when printing */
    @media print {
        .no-print,
        .ibox-tools,
        .btn,
        .sidebar,
        .navbar,
        .footer {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .wrapper {
            margin: 0;
            padding: 0;
        }

        .ibox {
            box-shadow: none;
            border: none;
        }

        .ibox-content {
            padding: 0;
        }

        /* Ensure single page */
        .customer-section,
        .financial-section,
        .guarantors-section,
        .payment-history {
            page-break-inside: avoid;
        }

        /* Adjust font sizes for print */
        .statement-header h3 {
            font-size: 16px;
        }

        .info-item {
            font-size: 10px;
        }

        .financial-panel {
            border-radius: 0;
        }

        .status-item strong {
            border-radius: 0;
        }

        .guarantor-table {
            font-size: 9px;
        }

        .payment-table {
            font-size: 8px;
        }
    }

    /* Screen view styles */
    @media screen {
        .ibox-content {
            padding: 20px;
        }

        .customer-section {
            background-color: #f8f9fa;
        }

        .guarantor-table th {
            background-color: #e9ecef;
        }

        .payment-table th {
            background-color: #e9ecef;
        }
    }

    @media screen and (max-width: 768px) {
        .ibox-tools {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .customer-section,
        .financial-section,
        .info-row {
            flex-direction: column;
        }

        .financial-section {
            gap: 12px;
        }

        .customer-photo-section,
        .financial-left,
        .financial-right,
        .info-item {
            width: 100%;
            margin-right: 0;
        }

        .financial-panel .info-row {
            gap: 0;
        }

        .customer-photo-section {
            margin-top: 12px;
            text-align: left;
        }

        .print-info {
            flex-direction: column;
            gap: 4px;
        }

        .table-scroll-mobile {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .guarantor-table,
        .payment-table {
            min-width: 760px;
        }

        .guarantor-table th,
        .guarantor-table td,
        .payment-table th,
        .payment-table td {
            white-space: nowrap;
        }
    }
</style>
@endpush
@endsection
