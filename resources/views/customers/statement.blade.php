@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Customer Account — {{ $customer->name }}</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-default">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                        <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i> New Purchase
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    @php
                        $totalPurchases       = $customer->purchases->count();
                        $totalPurchaseAmount  = $customer->purchases->sum('total_price');
                        $totalAdvance         = $customer->purchases->sum('advance_payment');
                        $totalPaidInst        = $customer->installments()->where('status','paid')->sum('installment_amount');
                        $totalDiscount        = $customer->installments()->where('status','paid')->sum('discount');
                        $totalPaid            = $totalAdvance + $totalPaidInst + $totalDiscount;
                        $totalRemaining       = max(0, $totalPurchaseAmount - $totalPaid);
                        $overdueCount         = $customer->installments()->where('status','pending')->where('due_date','<',now())->count();

                        $accountStatus = 'ACTIVE';
                        if ($totalPurchases == 0)       $accountStatus = 'NO PURCHASES';
                        elseif ($totalRemaining <= 0)   $accountStatus = 'COMPLETED';
                    @endphp

                    {{-- ══ Customer Info ═════════════════════════════════════ --}}
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h3 class="panel-title">Customer Information</h3></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    @if($customer->image)
                                        <img src="{{ asset('backend/img/customers/'.$customer->image) }}"
                                             style="width:100px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ccc;" alt="Photo">
                                    @else
                                        <div style="width:100px;height:120px;background:#ddd;border-radius:8px;line-height:120px;text-align:center;font-size:28px;font-weight:bold;margin:auto;">
                                            {{ strtoupper(substr($customer->name,0,2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-condensed" style="font-size:13px;">
                                        <tr><th width="20%">Account No:</th><td>{{ $customer->account_no }}</td><th width="20%">NIC:</th><td>{{ $customer->nic }}</td></tr>
                                        <tr><th>Customer:</th><td>{{ $customer->name }}</td><th>Gender:</th><td>{{ ucfirst($customer->gender ?? 'N/A') }}</td></tr>
                                        <tr><th>F/H Name:</th><td>{{ $customer->father_name ?? 'N/A' }}</td><th>Occupation:</th><td>{{ $customer->occupation ?? 'N/A' }}</td></tr>
                                        <tr><th>Mobile #1:</th><td>{{ $customer->mobile_1 }}</td><th>Mobile #2:</th><td>{{ $customer->mobile_2 ?? 'N/A' }}</td></tr>
                                        <tr><th>Residence:</th><td colspan="3">{{ $customer->residence ?? 'N/A' }}</td></tr>
                                        <tr><th>Off. Address:</th><td colspan="3">{{ $customer->office_address ?? 'N/A' }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ Overall Account Summary ════════════════════════════ --}}
                    <div class="panel panel-info">
                        <div class="panel-heading"><h3 class="panel-title">Overall Account Summary</h3></div>
                        <div class="panel-body">
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <div class="stat-box">
                                        <div class="stat-num">{{ $totalPurchases }}</div>
                                        <div class="stat-label">Total Purchases</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="stat-box">
                                        <div class="stat-num">Rs. {{ number_format($totalPurchaseAmount, 0) }}</div>
                                        <div class="stat-label">Total Amount</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="stat-box">
                                        <div class="stat-num">Rs. {{ number_format($totalPaid, 0) }}</div>
                                        <div class="stat-label">Total Paid</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="stat-box {{ $totalRemaining > 0 ? 'stat-warning' : 'stat-success' }}">
                                        <div class="stat-num">Rs. {{ number_format($totalRemaining, 0) }}</div>
                                        <div class="stat-label">Remaining</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="stat-box {{ $overdueCount > 0 ? 'stat-danger' : 'stat-success' }}">
                                        <div class="stat-num">{{ $overdueCount }}</div>
                                        <div class="stat-label">Overdue</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="stat-box">
                                        <div class="stat-num">
                                            <span class="label label-{{ $accountStatus === 'COMPLETED' ? 'success' : ($accountStatus === 'NO PURCHASES' ? 'default' : 'primary') }}">
                                                {{ $accountStatus }}
                                            </span>
                                        </div>
                                        <div class="stat-label">Status</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ No Purchases ════════════════════════════════════════ --}}
                    @if($totalPurchases == 0)
                    <div class="alert alert-info text-center">
                        <i class="fa fa-info-circle fa-2x"></i>
                        <p class="mt-2">This customer has no purchases yet.</p>
                        <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Create First Purchase
                        </a>
                    </div>
                    @endif

                    {{-- ══ Per-Purchase Cards ═══════════════════════════════════ --}}
                    @foreach($customer->purchases as $i => $purchase)
                    @php
                        $pPaidInst    = $purchase->installments->where('status','paid');
                        $pCash        = $purchase->advance_payment + $pPaidInst->sum('installment_amount');
                        $pDisc        = $pPaidInst->sum('discount');
                        $pTotalPaid   = $pCash + $pDisc;
                        $pRemaining   = max(0, $purchase->total_price - $pTotalPaid);
                        $pPaidCount   = $pPaidInst->count();
                        $pTotalCount  = $purchase->getTotalInstallmentCount();
                        $pPending     = $purchase->installments->where('status','pending')->count();
                        $pOverdue     = $purchase->installments->where('status','pending')->filter(fn($x) => $x->due_date < now())->count();
                        $pStatus      = $pRemaining <= 0 ? 'COMPLETED' : 'ACTIVE';
                        $progress     = $purchase->total_price > 0 ? min(100, ($pTotalPaid / $purchase->total_price) * 100) : 0;
                    @endphp

                    <div class="panel {{ $pStatus === 'COMPLETED' ? 'panel-success' : 'panel-default' }}">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="display:flex;justify-content:space-between;align-items:center;">
                                <span>
                                    <i class="fa fa-shopping-cart"></i>
                                    Purchase #{{ $i + 1 }} —
                                    {{ $purchase->product->company ?? '' }} {{ $purchase->product->model ?? '' }}
                                    <small class="text-muted">({{ $purchase->purchase_date->toDisplayDate() }})</small>
                                </span>
                                <span>
                                    <span class="label label-{{ $pStatus === 'COMPLETED' ? 'success' : 'primary' }}">{{ $pStatus }}</span>
                                    @if($pOverdue > 0)
                                        <span class="label label-danger">{{ $pOverdue }} Overdue</span>
                                    @endif
                                </span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                {{-- Left: Quick financials --}}
                                <div class="col-md-7">
                                    <table class="table table-condensed table-bordered" style="font-size:12px;">
                                        <tr>
                                            <th>Purchase Price</th><td>Rs. {{ number_format($purchase->total_price, 0) }}</td>
                                            <th>Type</th><td>{{ $purchase->getInstallmentTypeLabel() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Advance Paid</th><td>Rs. {{ number_format($purchase->advance_payment, 0) }}</td>
                                            <th>Per Installment</th><td>Rs. {{ number_format($purchase->monthly_installment, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Paid</th><td class="text-success"><strong>Rs. {{ number_format($pTotalPaid, 0) }}</strong></td>
                                            <th>Installments</th><td>{{ $pPaidCount }} / {{ $pTotalCount }} paid</td>
                                        </tr>
                                        <tr>
                                            <th>Remaining</th>
                                            <td class="{{ $pRemaining > 0 ? 'text-warning' : 'text-success' }}">
                                                <strong>Rs. {{ number_format($pRemaining, 0) }}</strong>
                                            </td>
                                            <th>Pending</th><td>{{ $pPending }}</td>
                                        </tr>
                                        <tr>
                                            <th>First Due</th><td>{{ $purchase->first_installment_date->toDisplayDate() }}</td>
                                            <th>Last Due</th><td>{{ $purchase->last_installment_date->toDisplayDate() }}</td>
                                        </tr>
                                    </table>
                                    {{-- Progress bar --}}
                                    <div class="progress" style="margin-bottom:5px;">
                                        <div class="progress-bar progress-bar-{{ $progress >= 100 ? 'success' : 'info' }}"
                                             style="width:{{ $progress }}%">
                                            {{ number_format($progress, 0) }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $pPaidCount }}/{{ $pTotalCount }} installments paid</small>
                                </div>

                                {{-- Right: Product + Actions --}}
                                <div class="col-md-5">
                                    <div class="well well-sm" style="font-size:12px;">
                                        <strong>Product Details</strong><hr style="margin:5px 0;">
                                        <p><strong>Company:</strong> {{ $purchase->product->company ?? 'N/A' }}</p>
                                        <p><strong>Model:</strong> {{ $purchase->product->model ?? 'N/A' }}</p>
                                        <p><strong>Serial No:</strong> {{ $purchase->product->serial_no ?? 'N/A' }}</p>
                                        <p><strong>Price:</strong> Rs. {{ number_format($purchase->product->price ?? 0, 0) }}</p>
                                    </div>
                                    {{-- Action buttons --}}
                                    <div class="btn-group-vertical btn-block">
                                        <a href="{{ route('purchases.statement', $purchase->id) }}"
                                           class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fa fa-print"></i> Print This Statement
                                        </a>
                                        <a href="{{ route('purchases.show', $purchase->id) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i> View Full Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>{{-- /ibox-content --}}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-box { padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px; }
    .stat-num { font-size: 18px; font-weight: bold; }
    .stat-label { font-size: 11px; color: #888; }
    .stat-warning .stat-num { color: #e67e22; }
    .stat-success .stat-num { color: #27ae60; }
    .stat-danger .stat-num  { color: #e74c3c; }
    .btn-group-vertical.btn-block .btn { margin-bottom: 5px; }
</style>
@endpush
@endsection
