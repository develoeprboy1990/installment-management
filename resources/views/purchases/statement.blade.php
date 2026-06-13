@extends('layouts.master')

@section('content')
@php
    $customer   = $purchase->customer;
    $product    = $purchase->product;
    $paidInst   = $purchase->installments->where('status', 'paid')->sortBy('due_date');
    $pendingInst = $purchase->installments->where('status', 'pending')->sortBy('due_date');

    $paidCash    = $purchase->advance_payment + $paidInst->sum('installment_amount');
    $pDiscount   = $paidInst->sum('discount');
    $pTotalPaid  = $paidCash + $pDiscount;
    $pRemaining  = max(0, $purchase->total_price - $pTotalPaid);
    $pPaidCount  = $paidInst->count();
    $pTotalCount = $purchase->getTotalInstallmentCount();
    $pPending    = $pendingInst->count();
    $pOverdue    = $pendingInst->where('due_date', '<', now())->count();
    $pStatus     = $pRemaining <= 0 ? 'COMPLETED' : 'ACTIVE';
@endphp

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                {{-- ── Toolbar (hidden on print) ────────────────────────── --}}
                <div class="ibox-title no-print">
                    <h5>Purchase Statement — {{ $customer->name }}</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('customers.statement', $customer->id) }}" class="btn btn-sm btn-default">
                            <i class="fa fa-arrow-left"></i> All Statements
                        </a>
                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i> View Purchase
                        </a>
                        <button class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                </div>

                <div class="ibox-content">

                    {{-- ══════════════════════════════════════════════════════
                         COMPANY HEADER
                    ═══════════════════════════════════════════════════════ --}}
                    <div class="stmt-header">
                        <h3>{{ getUserSetting('project_name') ?? 'Electronics Corporation' }}</h3>
                        <div class="stmt-meta">
                            <span>Print Date: {{ now()->format('d-M-Y h:i A') }}</span>
                            <span>Purchase #{{ $purchase->id }}</span>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════════════
                         CUSTOMER INFO + PHOTO
                    ═══════════════════════════════════════════════════════ --}}
                    <div class="stmt-customer-row">
                        <div class="stmt-customer-info">
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell"><strong>Account No:</strong> {{ $customer->account_no }}</div>
                                <div class="stmt-info-cell"><strong>Purchase Date:</strong> {{ $purchase->purchase_date->toDisplayDate() }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell"><strong>Customer:</strong> {{ $customer->name }}</div>
                                <div class="stmt-info-cell"><strong>NIC:</strong> {{ $customer->nic }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell"><strong>F/H Name:</strong> {{ $customer->father_name ?? 'N/A' }}</div>
                                <div class="stmt-info-cell"><strong>Gender:</strong> {{ ucfirst($customer->gender ?? 'N/A') }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell"><strong>Mobile #1:</strong> {{ $customer->mobile_1 }}</div>
                                <div class="stmt-info-cell"><strong>Mobile #2:</strong> {{ $customer->mobile_2 ?? 'N/A' }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell stmt-full"><strong>Occupation:</strong> {{ $customer->occupation ?? 'N/A' }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell stmt-full"><strong>Residence:</strong> {{ $customer->residence ?? 'N/A' }}</div>
                            </div>
                            <div class="stmt-info-row">
                                <div class="stmt-info-cell stmt-full"><strong>Off. Address:</strong> {{ $customer->office_address ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="stmt-photo">
                            @if($customer->image)
                                <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer">
                            @else
                                <div class="stmt-photo-placeholder">{{ strtoupper(substr($customer->name, 0, 2)) }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════════════
                         FINANCIAL SUMMARY + PRODUCT DETAILS
                    ═══════════════════════════════════════════════════════ --}}
                    <div class="stmt-financial-row">

                        {{-- Left: Financial --}}
                        <div class="stmt-card stmt-card-wide">
                            <div class="stmt-card-title">Financial Summary</div>
                            <div class="stmt-card-body">
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Purchase Price:</strong> Rs. {{ number_format($purchase->total_price, 0) }}</div>
                                    <div class="stmt-info-cell"><strong>Installment Type:</strong> {{ $purchase->getInstallmentTypeLabel() }}</div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Advance Payment:</strong> Rs. {{ number_format($purchase->advance_payment, 0) }}</div>
                                    <div class="stmt-info-cell"><strong>Per Installment:</strong> Rs. {{ number_format($purchase->monthly_installment, 0) }}</div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Total Paid:</strong> Rs. {{ number_format($pTotalPaid, 0) }}</div>
                                    <div class="stmt-info-cell"><strong>Total Installments:</strong> {{ $pTotalCount }}</div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Remaining Balance:</strong> Rs. {{ number_format($pRemaining, 0) }}</div>
                                    <div class="stmt-info-cell"><strong>Paid Installments:</strong> {{ $pPaidCount }}</div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>First Due:</strong> {{ $purchase->first_installment_date->toDisplayDate() }}</div>
                                    <div class="stmt-info-cell"><strong>Pending Installments:</strong> {{ $pPending }}</div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Last Due:</strong> {{ $purchase->last_installment_date->toDisplayDate() }}</div>
                                    <div class="stmt-info-cell">
                                        <strong>Overdue:</strong>
                                        @if($pOverdue > 0)
                                            <span style="color:red;">{{ $pOverdue }} installments</span>
                                        @else
                                            None
                                        @endif
                                    </div>
                                </div>
                                <div class="stmt-info-row">
                                    <div class="stmt-info-cell"><strong>Status:</strong> {{ $pStatus }}</div>
                                    <div class="stmt-info-cell"><strong>Total Discount:</strong> Rs. {{ number_format($pDiscount, 0) }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Product --}}
                        <div class="stmt-card stmt-card-narrow">
                            <div class="stmt-card-title">Product Details</div>
                            <div class="stmt-card-body">
                                <div class="stmt-product-row"><strong>Company:</strong> {{ $product->company ?? 'N/A' }}</div>
                                <div class="stmt-product-row"><strong>Model:</strong> {{ $product->model ?? 'N/A' }}</div>
                                <div class="stmt-product-row"><strong>Serial No:</strong> {{ $product->serial_no ?? 'N/A' }}</div>
                                <div class="stmt-product-row"><strong>Product Price:</strong> Rs. {{ number_format($product->price ?? 0, 0) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════════════
                         GUARANTORS
                    ═══════════════════════════════════════════════════════ --}}
                    @if($customer->guarantors->count() > 0)
                    <div class="stmt-guarantors">
                        <table class="stmt-table">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    @foreach($customer->guarantors->take(4) as $g)
                                        <th>
                                            Guarantor #{{ $g->guarantor_no }}
                                            <div class="g-photo-wrap">
                                                @if($g->image)
                                                    <img src="{{ asset($g->image) }}" class="g-photo" alt="G">
                                                @else
                                                    <div class="g-photo-placeholder">G{{ $g->guarantor_no }}</div>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Name'=>'name','F/H Name'=>'father_name','Phone'=>'phone','NIC'=>'nic','Residence'=>'residence_address','Office'=>'office_address','Occupation'=>'occupation','Relation'=>'relation'] as $label => $col)
                                <tr>
                                    <td><strong>{{ $label }}:</strong></td>
                                    @foreach($customer->guarantors->take(4) as $g)
                                        <td>{{ $g->$col ? Str::limit($g->$col, 40) : 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- ══════════════════════════════════════════════════════
                         PAID INSTALLMENTS TABLE
                    ═══════════════════════════════════════════════════════ --}}
                    @if($paidInst->count() > 0)
                    <div class="stmt-payments">
                        <table class="stmt-pay-table">
                            <thead>
                                <tr>
                                    <th>S.#</th>
                                    <th>Date</th>
                                    <th>Rcv. #</th>
                                    <th>Pre-Balance</th>
                                    <th>Installment</th>
                                    <th>Discount</th>
                                    <th>Balance</th>
                                    <th>Recovery Officer</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paidInst->values() as $idx => $inst)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $inst->date ? $inst->date->toDisplayDate() : $inst->due_date->toDisplayDate() }}</td>
                                    <td>{{ $inst->receipt_no ?? '-' }}</td>
                                    <td>{{ number_format($inst->pre_balance, 0) }}</td>
                                    <td>{{ number_format($inst->installment_amount, 0) }}</td>
                                    <td>{{ number_format($inst->discount ?? 0, 0) }}</td>
                                    <td>{{ number_format($inst->balance, 0) }}</td>
                                    <td>{{ $inst->officer?->name ?? 'N/A' }}</td>
                                    <td>Paid</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="stmt-no-payments">No payments made yet for this purchase.</p>
                    @endif

                </div>{{-- /ibox-content --}}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @page { size: A4; margin: 0.5in; }

    /* ── Company Header ──────────────────────────────────────────────── */
    .stmt-header {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }
    .stmt-header h3 { margin: 0; font-size: 17px; font-weight: bold; }
    .stmt-meta { display: flex; justify-content: space-between; font-size: 11px; margin-top: 4px; }

    /* ── Customer Row ────────────────────────────────────────────────── */
    .stmt-customer-row {
        display: flex;
        justify-content: space-between;
        border: 1px solid #000;
        padding: 10px;
        margin-bottom: 12px;
    }
    .stmt-customer-info { flex: 1; font-size: 11px; }
    .stmt-photo { width: 110px; text-align: center; }
    .stmt-photo img, .stmt-photo-placeholder {
        width: 95px; height: 115px;
        border: 1px solid #000; object-fit: cover; border-radius: 8px;
    }
    .stmt-photo-placeholder {
        display: flex; align-items: center; justify-content: center;
        background: #f0f0f0; font-weight: bold; font-size: 22px;
    }

    /* ── Info Rows ───────────────────────────────────────────────────── */
    .stmt-info-row { display: flex; margin-bottom: 3px; }
    .stmt-info-cell { flex: 1; font-size: 11px; margin-right: 8px; }
    .stmt-info-cell.stmt-full { flex: 2; }

    /* ── Financial Cards ─────────────────────────────────────────────── */
    .stmt-financial-row { display: flex; gap: 10px; margin-bottom: 12px; }
    .stmt-card { border: 1px solid #000; border-radius: 3px; overflow: hidden; }
    .stmt-card-wide { flex: 2; }
    .stmt-card-narrow { flex: 1; }
    .stmt-card-title { background: #f0f0f0; border-bottom: 1px solid #000; font-size: 11px; font-weight: bold; padding: 4px 8px; }
    .stmt-card-body { padding: 7px; font-size: 11px; }
    .stmt-card-body .stmt-info-row { border-bottom: 1px solid #eee; padding: 2px 0; }
    .stmt-card-body .stmt-info-row:last-child { border-bottom: none; }
    .stmt-product-row { font-size: 11px; border-bottom: 1px solid #eee; padding: 3px 0; }
    .stmt-product-row:last-child { border-bottom: none; }

    /* ── Guarantors ──────────────────────────────────────────────────── */
    .stmt-guarantors { margin-bottom: 12px; overflow-x: auto; }
    .stmt-table { width: 100%; border-collapse: collapse; font-size: 10px; }
    .stmt-table th, .stmt-table td { border: 1px solid #000; padding: 3px; text-align: left; }
    .stmt-table th { background: #f0f0f0; font-weight: bold; }
    .g-photo-wrap { margin-top: 4px; }
    .g-photo, .g-photo-placeholder {
        width: 45px; height: 55px;
        border: 1px solid #000; border-radius: 4px; object-fit: cover;
    }
    .g-photo-placeholder {
        display: flex; align-items: center; justify-content: center;
        background: #f0f0f0; font-weight: bold; font-size: 11px;
    }

    /* ── Payment Table ───────────────────────────────────────────────── */
    .stmt-payments { margin-bottom: 10px; overflow-x: auto; }
    .stmt-pay-table { width: 100%; border-collapse: collapse; font-size: 9px; }
    .stmt-pay-table th, .stmt-pay-table td { border: 1px solid #000; padding: 2px 3px; text-align: center; }
    .stmt-pay-table th { background: #f0f0f0; font-weight: bold; }
    .stmt-no-payments { font-size: 11px; color: #666; font-style: italic; margin: 5px 0; }

    /* ── Print ───────────────────────────────────────────────────────── */
    @media print {
        .no-print, .ibox-title, .sidebar, .navbar, .footer, .btn { display: none !important; }
        body { font-size: 12px; }
        .wrapper, .ibox-content { margin: 0; padding: 0; }
        .ibox { box-shadow: none; border: none; }
        .stmt-customer-row, .stmt-financial-row, .stmt-guarantors, .stmt-payments { page-break-inside: avoid; }
    }

    /* ── Mobile ──────────────────────────────────────────────────────── */
    @media screen and (max-width: 768px) {
        .stmt-customer-row, .stmt-financial-row, .stmt-info-row { flex-direction: column; }
        .stmt-photo, .stmt-card-wide, .stmt-card-narrow { width: 100%; }
        .stmt-financial-row { gap: 8px; }
        .stmt-photo { margin-top: 10px; }
    }
</style>
@endpush
@endsection
