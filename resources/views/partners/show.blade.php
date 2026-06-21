@extends('layouts.master')

@section('content')
<div class="container-fluid" style="margin-bottom: 60px;">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none"><i class="fa fa-handshake-o text-navy"></i> {{ $partner->name }}</h2>
            <small class="text-muted">Partner profile and their purchase details.</small>
        </div>
        <div class="col-sm-4 text-right" style="margin-top:25px;">
            <a href="{{ route('partners.edit', $partner) }}" class="btn btn-warning m-r-xs">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('partners.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Partner Info --}}
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-user"></i> Partner Info</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-borderless" style="margin-bottom:0;">
                        <tr>
                            <td class="text-muted" style="width:40%;">Name:</td>
                            <td><strong>{{ $partner->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phone:</td>
                            <td>{{ $partner->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>{{ $partner->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @if($partner->is_active)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @if($partner->notes)
                        <tr>
                            <td class="text-muted">Notes:</td>
                            <td>{{ $partner->notes }}</td>
                        </tr>
                        @endif
                    </table>

                    <hr>
                    <div style="text-align:center;">
                        <div style="font-size:11px;color:#888;text-transform:uppercase;">Total Invested</div>
                        <div style="font-size:28px;font-weight:700;color:#1ab394;">
                            Rs. {{ number_format($partner->purchasePartners->sum('share_amount'), 0) }}
                        </div>
                        <div style="font-size:12px;color:#aaa;">in {{ $partner->purchasePartners->count() }} purchases</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Purchases List --}}
        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-shopping-cart"></i> Purchases Associated With This Partner</h5>
                </div>
                <div class="ibox-content">
                    @if($partner->purchasePartners->count() === 0)
                        <p class="text-muted text-center">This partner has not been assigned to any purchase yet.</p>
                    @else
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Total Price</th>
                                    <th>Share Amount</th>
                                    <th>Share %</th>
                                    <th>Received</th>
                                    <th>Pending</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($partner->purchasePartners as $pp)
                                @php
                                    $purchase = $pp->purchase;
                                    $received = $pp->amount_received;
                                    $pending  = $pp->amount_pending;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('purchases.show', $purchase) }}">
                                            {{ $purchase->customer->name ?? '—' }}
                                        </a>
                                    </td>
                                    <td>{{ $purchase->product->company ?? '' }} {{ $purchase->product->model ?? '—' }}</td>
                                    <td>Rs. {{ number_format($purchase->total_price, 0) }}</td>
                                    <td><strong>Rs. {{ number_format($pp->share_amount, 0) }}</strong></td>
                                    <td>{{ $pp->share_percentage }}%</td>
                                    <td class="text-success">Rs. {{ number_format($received, 0) }}</td>
                                    <td class="{{ $pending > 0 ? 'text-danger' : 'text-success' }}">
                                        Rs. {{ number_format($pending, 0) }}
                                    </td>
                                    <td>
                                        @if($purchase->status === 'completed')
                                            <span class="label label-success">Completed</span>
                                        @elseif($purchase->status === 'active')
                                            <span class="label label-primary">Active</span>
                                        @else
                                            <span class="label label-warning">{{ ucfirst($purchase->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
