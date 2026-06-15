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
    /* Display styles */
    .table-responsive {
        overflow-x: auto;
    }
    
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        
        /* Hide unnecessary elements */
        .no-print, .sidebar, .navbar, .footer, .btn, .breadcrumb, form {
            display: none !important;
        }
        
        /* Reset margins and backgrounds */
        body, .wrapper, .ibox, .ibox-content {
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }
        
        /* Header styling */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 15px !important;
        }
        .print-header h2 {
            font-size: 20px !important;
            margin: 0 0 5px 0 !important;
            font-weight: bold !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .print-header h4 {
            font-size: 14px !important;
            margin: 0 0 5px 0 !important;
            color: #333 !important;
        }
        .print-header p {
            font-size: 10px !important;
            color: #777 !important;
            margin: 0 0 10px 0 !important;
        }
        .print-header hr {
            border-top: 1px solid #ccc !important;
            margin: 10px 0 !important;
        }
        
        /* Table styling */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important; /* Smaller text */
            margin-bottom: 0 !important;
        }
        .table th, .table td {
            border: 1px solid #ddd !important;
            padding: 4px 6px !important;
            vertical-align: middle !important;
            color: #333 !important;
        }
        .table th {
            background-color: #f3f3f4 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-weight: bold !important;
            text-align: left !important;
        }
        .table th.text-center, .table td.text-center {
            text-align: center !important;
        }
        .table th.text-right, .table td.text-right {
            text-align: right !important;
        }
        
        /* Text emphasis */
        strong {
            font-weight: 600 !important;
        }
        .text-danger {
            color: #d62d3a !important; /* A slightly darker red for better print visibility */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Signature column line */
        td[style*="dashed"] {
            border-bottom: 1px dashed #999 !important;
        }
        
        /* Tfoot styling */
        .table tfoot th {
            background-color: #f9f9f9 !important;
            font-size: 12px !important;
            padding: 8px 6px !important;
        }
        .table tfoot th.text-danger {
            font-size: 14px !important;
        }
    }
</style>
@endpush
@endsection
