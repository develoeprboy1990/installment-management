<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Installment Receipt</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <style>
        body { background-color: #f8f9fa; }
        .receipt-container {
            width: 1000px;
            background: #fff;
            border: 1px solid #000;
            padding: 20px 30px;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        .receipt-header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .receipt-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .table th { width: 25%; }
        .table td { width: 25%; }

        @media print {
            body { background: none; margin: 0; }
            .receipt-container { box-shadow: none; margin: 0; border: none; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    {{-- Header --}}
    <div class="receipt-header text-center">
        <div class="receipt-title">Talal & Niazi Electronics</div>
        <div class="d-flex justify-content-between">
        <div class="mt-1 text-end"><small>PAYMENT RECEIPT</small></div>
        <div class="mt-1 text-end"><small>{{ now()->format('M j ,Y & h:i A') ?? 'N/A' }}</small></div>
        <div class="mt-1 text-start"><small>Contact: 03008183092</small></div>
        </div>

    </div>


    {{-- Financial Details --}}
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <th>Account No:</th>
                <td class="text-end">
                    {{ $installment->customer->account_no ?? 'N/A' }}
                </td>
                <th>Purchase Date:</th>
                <td class="text-end">
                    @php
                    $purchaseDate = optional($installment->purchase)->purchase_date
                        ?? optional($installment->purchase)->created_at
                        ?? $installment->date;
                    @endphp
                    {{ optional($purchaseDate)->format('M j ,Y & h:iA') ?? 'N/A' }}
                </td>
            </tr>
             <tr>
                <th>Customer Name:</th>
                <td class="text-end">
                     {{ $installment->customer->name ?? 'N/A' }}
                </td>
                <th>Father Name:</th>
                <td class="text-end">
                    {{ $installment->customer->father_name ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <th>Installment date:</th>
                <td class="text-end">
                     <span class="text-end">{{ $installment->date->format('M j ,Y') ?? 'N/A' }}</span>
                </td>
                <th>Customer Phone:</th>
                <td class="text-end">
                     {{ $installment->customer->mobile_1 ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td class="text-end">
                    {{ optional(optional($installment->purchase)->product)->company }}
                    {{ optional(optional($installment->purchase)->product)->model }}
                </td>
                <th>Total Price</th>
                <td class="text-end">
                    Rs.{{ number_format((float)($installment->purchase->total_price ?? 0), 2) }}
                </td>
            </tr>
            <tr>
                <th>Previous Balance</th>
                <td class="text-end">{{ 'Rs. ' . number_format((float)($installment->pre_balance ?? 0), 2) }}</td>
                <th>Remaining Balance</th>
                <td class="text-end">{{ 'Rs. ' . number_format((float)($installment->balance ?? 0), 2) }}</td>
            </tr>
            <tr>
                <th>Serial No</th>
                <td class="text-end">{{ optional(optional($installment->purchase)->product)->serial_no ?? 'N/A' }}</td>
                <th>Payment Method</th>
                <td class="text-end">{{ isset($installment->payment_method) ? ucfirst($installment->payment_method) : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Total Paid</th>
                <td class="text-end">Rs.{{ number_format((float)($installment->installment_amount ?? 0), 2) }}</td>
                <th>Total Installments</th>
                <td class="text-end">{{ $installment->purchase->installment_months ?? 'N/A' }}</td>
            </tr>

            @php
                $receivedCount = optional($installment->purchase)
                    ? $installment->purchase->installments()
                        ->where('id', '<=', $installment->id)
                        ->where('status', 'paid')
                        ->count()
                    : null;
            @endphp
            <tr>
                <th>Received Installments</th>
                <td class="text-end">
                    @if(!is_null($receivedCount) && !empty($installment->purchase->installment_months))
                        {{ $receivedCount }}
                    @elseif(!is_null($receivedCount))
                        #{{ $receivedCount }}
                    @else
                        N/A
                    @endif
                </td>
                <th>Received By</th>
                <td class="text-end">{{ $installment->officer->name ?? 'N/A' }}</td>
            </tr>

            @if(($installment->discount ?? 0) > 0 || ($installment->fine_amount ?? 0) > 0)
                <tr>
                    @if(($installment->discount ?? 0) > 0)
                        <th>Discount</th>
                        <td>{{ 'Rs. ' . number_format((float)$installment->discount, 2) }}</td>
                    @else
                        <th>Discount</th><td>Rs. 0.00</td>
                    @endif

                    @if(($installment->fine_amount ?? 0) > 0)
                        <th>Fine Amount</th>
                        <td>{{ 'Rs. ' . number_format((float)$installment->fine_amount, 2) }}</td>
                    @else
                        <th>Fine Amount</th><td>Rs. 0.00</td>
                    @endif
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Urdu Note --}}
    <div class="text-center mt-4">
        <small style="font-weight:800;">
            ضروری نوٹ! قسط ہر ماہ کے 5 تاریخ تک لازمی جمع کروائیں۔ ادارہ کی رسید کے بغیر لین دین نہ کریں،
            ادارہ کسی بھی غیر قانونی استعمال کا ذمہ دار نہ ہوگا۔ ادارہ کا کوئی بھی سٹاف رسید پرنٹ کیے
            بغیر کسی قسم کی تصدیق نہیں دے سکتا۔
        </small>
    </div>
    <div class="receipt-header"></div>
</div>

{{-- Actions --}}
<div class="no-print text-center my-3">
    <button onclick="window.print()" class="btn btn-primary px-4">Print Receipt</button>
    <button onclick="window.close()" class="btn btn-outline-secondary px-4 ms-2">Close</button>
</div>

</body>
</html>
