@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row no-print">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Monthly Installment Schedule</h5>
                    <div class="ibox-tools">
                        <button class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fa fa-print"></i> Print Schedule
                        </button>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="GET" action="{{ route('installments.monthly_schedule') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="month" class="mr-2">Month:</label>
                            <select name="month" id="month" class="form-control">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="year" class="mr-2 ml-3">Year:</label>
                            <select name="year" id="year" class="form-control">
                                @for($y = date('Y') - 2; $y <= date('Y') + 5; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success ml-3">Show Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content printable-area">
                    <div class="text-center print-header" style="display:none; margin-bottom: 20px;">
                        <h2>{{ getUserSetting('project_name') ?? 'Electronics Corporation' }}</h2>
                        <h4>Monthly Installment Schedule - {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</h4>
                        <p>Printed on: {{ now()->format('d-M-Y h:i A') }}</p>
                        <hr>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="font-size: 13px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th>S.No</th>
                                    <th>Customer Name</th>
                                    <th>A/C No</th>
                                    <th>Phone</th>
                                    <th>Product</th>
                                    <th>Due Date</th>
                                    <th>Total Inst.</th>
                                    <th>Paid (Partial)</th>
                                    <th>Payable Amount</th>
                                    <th width="15%" class="text-center">Signature / Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPayable = 0;
                                @endphp
                                @forelse($installments as $index => $installment)
                                    @php
                                        $payable = $installment->installment_amount - $installment->paid_amount - $installment->discount;
                                        $totalPayable += $payable;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ optional($installment->customer)->name }}</strong></td>
                                        <td>{{ optional($installment->customer)->account_no }}</td>
                                        <td>{{ optional($installment->customer)->mobile_1 }}</td>
                                        <td>
                                            @if($installment->purchase && $installment->purchase->product)
                                                {{ $installment->purchase->product->company }} {{ $installment->purchase->product->model }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $installment->due_date ? $installment->due_date->format('d-M-Y') : 'N/A' }}</td>
                                        <td>Rs. {{ number_format($installment->installment_amount, 0) }}</td>
                                        <td>
                                            @if($installment->paid_amount > 0)
                                                Rs. {{ number_format($installment->paid_amount, 0) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-danger"><strong>Rs. {{ number_format($payable, 0) }}</strong></td>
                                        <td style="border-bottom: 1px dashed #ccc;"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No installments due in this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-right">Total Expected Collection:</th>
                                    <th colspan="2" class="text-danger" style="font-size: 16px;"><strong>Rs. {{ number_format($totalPayable, 0) }}</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print, .sidebar, .navbar, .footer, .btn, .breadcrumb {
            display: none !important;
        }
        body {
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .wrapper, .ibox-content {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
        }
        .print-header {
            display: block !important;
            text-align: center;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 5px !important;
        }
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    }
</style>
@endpush
@endsection
