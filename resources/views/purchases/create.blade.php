@extends('layouts.master')

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none"><i class="fa fa-shopping-cart text-navy"></i> New Purchase</h2>
            <small class="text-muted">Fill in the details below to create a new installment purchase.</small>
        </div>
        <div class="col-sm-4 text-right" style="margin-top:25px;">
            <a href="{{ route('purchases.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    {{-- ── Validation Errors ─────────────────────────────────────────────── --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</strong>
        <ul class="m-t-xs m-b-none">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="row">

            {{-- ════════════════════════════════════════════════════════════
                 LEFT COLUMN — Main Form
            ════════════════════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- ── Panel 1: Customer & Product ─────────────────────── --}}
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-user text-navy"></i> Customer & Product</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">
                                        <i class="fa fa-user-circle-o"></i> Customer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="customer_id" id="customer_id" required>
                                        <option value="">— Select Customer —</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->account_no }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">
                                        <i class="fa fa-cube"></i> Product
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="product_id" id="product_id" required>
                                        <option value="">— Select Product —</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->price }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->company }} {{ $product->model }}
                                                — Rs. {{ number_format($product->price, 0) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_date">
                                        <i class="fa fa-calendar"></i> Purchase Date
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="purchase_date"
                                           id="purchase_date"
                                           value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recovery_officer_id">
                                        <i class="fa fa-id-badge"></i> Recovery Officer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="recovery_officer_id"
                                            id="recovery_officer_id" required>
                                        <option value="">— Select Officer —</option>
                                        @foreach($recoveryOfficers as $officer)
                                            <option value="{{ $officer->id }}"
                                                {{ old('recovery_officer_id') == $officer->id ? 'selected' : '' }}>
                                                {{ $officer->name }} ({{ $officer->employee_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Panel 2: Financial Details ───────────────────────── --}}
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-money text-navy"></i> Financial Details</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_price">
                                        <i class="fa fa-tag"></i> Total Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><strong>Rs.</strong></span>
                                        <input type="number" class="form-control" name="total_price"
                                               id="total_price" step="0.01" min="0"
                                               value="{{ old('total_price') }}"
                                               placeholder="Auto-filled from product" readonly
                                               style="background:#f8f8f8;">
                                    </div>
                                    <small class="text-muted"><i class="fa fa-info-circle"></i> Auto-filled when product is selected</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="advance_payment">
                                        <i class="fa fa-credit-card"></i> Advance Payment
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><strong>Rs.</strong></span>
                                        <input type="number" class="form-control" name="advance_payment"
                                               id="advance_payment" step="0.01" min="0"
                                               value="{{ old('advance_payment', 0) }}"
                                               placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Panel 3: Installment Schedule ────────────────────── --}}
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar-check-o text-navy"></i> Installment Schedule</h5>
                    </div>
                    <div class="ibox-content">

                        {{-- Mode Toggle --}}
                        <div class="form-group">
                            <label><i class="fa fa-sliders"></i> Calculation Mode</label>
                            <div class="row" style="margin-top:8px;">
                                <div class="col-xs-6">
                                    <label id="mode_btn_count" style="display:block;padding:10px 14px;border:2px solid #1ab394;border-radius:6px;cursor:pointer;text-align:center;background:#1ab394;color:#fff;font-weight:600;font-size:13px;">
                                        <input type="radio" name="calc_mode" value="by_count" checked style="display:none;">
                                        <i class="fa fa-list-ol"></i> By No. of Installments
                                    </label>
                                </div>
                                <div class="col-xs-6">
                                    <label id="mode_btn_amount" style="display:block;padding:10px 14px;border:2px solid #ccc;border-radius:6px;cursor:pointer;text-align:center;background:#fff;color:#555;font-weight:600;font-size:13px;">
                                        <input type="radio" name="calc_mode" value="by_amount" style="display:none;">
                                        <i class="fa fa-money"></i> By Per Installment Amount
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="installment_type">
                                        <i class="fa fa-refresh"></i> Installment Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="installment_type"
                                            id="installment_type" required>
                                        <option value="monthly" {{ old('installment_type','monthly') === 'monthly' ? 'selected' : '' }}>
                                            Monthly
                                        </option>
                                        <option value="weekly" {{ old('installment_type') === 'weekly' ? 'selected' : '' }}>
                                            Weekly
                                        </option>
                                        <option value="daily" {{ old('installment_type') === 'daily' ? 'selected' : '' }}>
                                            Daily
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Mode A: By Count --}}
                            <div class="col-md-4" id="field_count">
                                <div class="form-group">
                                    <label for="installment_count">
                                        <i class="fa fa-list-ol"></i> No. of Installments
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" name="installment_count"
                                           id="installment_count" min="1"
                                           value="{{ old('installment_count') }}"
                                           placeholder="e.g. 12">
                                    <small class="text-muted" id="installment_count_hint">e.g. 12 months</small>
                                </div>
                            </div>

                            {{-- Mode B: By Per Installment Amount --}}
                            <div class="col-md-4" id="field_per_amount" style="display:none;">
                                <div class="form-group">
                                    <label for="per_installment_input">
                                        <i class="fa fa-money"></i> Per Installment Amount
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><strong>Rs.</strong></span>
                                        <input type="number" class="form-control" id="per_installment_input"
                                               step="1" min="1" placeholder="e.g. 7000">
                                    </div>
                                    <small class="text-muted">Count will be auto-calculated</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_installment_date">
                                        <i class="fa fa-calendar-o"></i> First Installment Date
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="first_installment_date"
                                           id="first_installment_date"
                                           value="{{ old('first_installment_date') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Last installment adjustment alert --}}
                        <div id="last_installment_note" class="alert alert-info" style="display:none;margin-bottom:0;">
                            <i class="fa fa-info-circle"></i>
                            <span id="last_installment_note_text"></span>
                        </div>

                    </div>
                </div>

            </div>{{-- /col-lg-8 --}}

            {{-- ════════════════════════════════════════════════════════════
                 RIGHT COLUMN — Live Summary
            ════════════════════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- ── Live Calculation Summary ─────────────────────────── --}}
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calculator text-navy"></i> Live Summary</h5>
                        <div class="ibox-tools">
                            <small class="text-muted">Auto-calculated</small>
                        </div>
                    </div>
                    <div class="ibox-content">

                        {{-- Remaining Balance --}}
                        <div class="summary-card" style="background:#fff3cd;border-left:4px solid #ffc107;padding:12px;border-radius:4px;margin-bottom:12px;">
                            <div style="font-size:11px;color:#856404;text-transform:uppercase;letter-spacing:.5px;">Remaining Balance</div>
                            <div style="font-size:22px;font-weight:700;color:#856404;" id="summary_remaining">
                                Rs. 0
                            </div>
                        </div>

                        {{-- Per Installment --}}
                        <div class="summary-card" style="background:#d1ecf1;border-left:4px solid #17a2b8;padding:12px;border-radius:4px;margin-bottom:12px;">
                            <div style="font-size:11px;color:#0c5460;text-transform:uppercase;letter-spacing:.5px;">Per Installment</div>
                            <div style="font-size:22px;font-weight:700;color:#0c5460;" id="summary_per_installment">
                                Rs. 0
                            </div>
                        </div>

                        {{-- Schedule Dates --}}
                        <div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:4px;padding:12px;margin-bottom:12px;">
                            <table style="width:100%;font-size:12px;">
                                <tr>
                                    <td style="color:#666;padding:3px 0;"><i class="fa fa-calendar-o"></i> First Due:</td>
                                    <td class="text-right" id="summary_first_date" style="font-weight:600;">—</td>
                                </tr>
                                <tr>
                                    <td style="color:#666;padding:3px 0;"><i class="fa fa-calendar-check-o"></i> Last Due:</td>
                                    <td class="text-right" id="summary_last_date" style="font-weight:600;">—</td>
                                </tr>
                                <tr>
                                    <td style="color:#666;padding:3px 0;"><i class="fa fa-list-ol"></i> Total:</td>
                                    <td class="text-right" id="summary_count" style="font-weight:600;">—</td>
                                </tr>
                                <tr>
                                    <td style="color:#666;padding:3px 0;"><i class="fa fa-refresh"></i> Type:</td>
                                    <td class="text-right" id="summary_type" style="font-weight:600;">Monthly</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Hidden fields for form submission --}}
                        <div class="form-group">
                            <label style="font-size:11px;color:#888;">Last Installment Date (Calculated)</label>
                            <input type="date" class="form-control input-sm" id="last_installment_date_preview" readonly
                                   style="background:#f8f8f8;font-size:12px;">
                        </div>

                        {{-- Hidden: actual per-installment amount to pass to controller --}}
                        <input type="hidden" name="per_installment_override" id="per_installment_override" value="0">

                    </div>
                </div>

                {{-- ── Submit Box ───────────────────────────────────────── --}}
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="form-group m-b-none">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-save"></i> Save Purchase
                            </button>
                            <a href="{{ route('purchases.index') }}"
                               class="btn btn-default btn-block m-t-xs">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}

        </div>{{-- /row --}}
    </form>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {

    // ── Helpers ────────────────────────────────────────────────────────────
    function addPeriod(dateStr, type, count) {
        if (!dateStr || !count) return '';
        const d = new Date(dateStr);
        const n = parseInt(count) - 1;
        if      (type === 'daily')  d.setDate(d.getDate() + n);
        else if (type === 'weekly') d.setDate(d.getDate() + (n * 7));
        else                        d.setMonth(d.getMonth() + n);
        return d.toISOString().split('T')[0];
    }

    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const [y, m, dd] = dateStr.split('-');
        const months = ['January','February','March','April','May','June',
                        'July','August','September','October','November','December'];
        return `${parseInt(dd)} ${months[parseInt(m)-1]} ${y}`;
    }

    function numFmt(n) {
        return 'Rs. ' + parseFloat(n || 0).toLocaleString('en-PK', { minimumFractionDigits: 0 });
    }

    const typeLabels = { daily: 'Daily', weekly: 'Weekly', monthly: 'Monthly' };
    const hints = { daily: 'e.g. 30 days', weekly: 'e.g. 52 weeks', monthly: 'e.g. 12 months' };

    let currentMode = 'by_count'; // default mode

    // ── Mode Toggle ────────────────────────────────────────────────────────
    $('input[name="calc_mode"]').on('change', function () {
        currentMode = $(this).val();

        if (currentMode === 'by_count') {
            $('#field_count').show();
            $('#field_per_amount').hide();
            $('#installment_count').prop('required', true);
            // Mode A active style
            $('#mode_btn_count').css({ background: '#1ab394', color: '#fff', 'border-color': '#1ab394' });
            $('#mode_btn_amount').css({ background: '#fff', color: '#555', 'border-color': '#ccc' });
        } else {
            $('#field_count').hide();
            $('#field_per_amount').show();
            $('#installment_count').prop('required', false);
            // Mode B active style
            $('#mode_btn_amount').css({ background: '#1ab394', color: '#fff', 'border-color': '#1ab394' });
            $('#mode_btn_count').css({ background: '#fff', color: '#555', 'border-color': '#ccc' });
        }
        recalculate();
    });

    // ── Main Recalculate ───────────────────────────────────────────────────
    function recalculate() {
        const total     = parseFloat($('#total_price').val())     || 0;
        const advance   = parseFloat($('#advance_payment').val()) || 0;
        const type      = $('#installment_type').val();
        const firstDate = $('#first_installment_date').val();
        const remaining = Math.max(0, total - advance);

        let count = 0;
        let perInstallment = 0;
        let lastInstallmentAmt = 0;

        if (currentMode === 'by_count') {
            // Mode A: user enters count, we calc per amount
            count = parseInt($('#installment_count').val()) || 0;
            perInstallment = count > 0 ? Math.round((remaining / count) * 100) / 100 : 0;
            $('#last_installment_note').hide();
            // Pass calculated per-installment to controller
            $('#per_installment_override').val(perInstallment);

        } else {
            // Mode B: user enters per amount, we calc count
            const perAmt = parseFloat($('#per_installment_input').val()) || 0;
            if (perAmt > 0 && remaining > 0) {

                const fullCount = Math.floor(remaining / perAmt);
                // Floating point safe: round to 2 decimals
                const lastAmt   = Math.round((remaining - (fullCount * perAmt)) * 100) / 100;

                if (lastAmt > 0.01) {
                    // Not evenly divisible
                    count = fullCount + 1;
                    perInstallment = perAmt;
                    // Pass user-entered per-installment to controller
                    $('#per_installment_override').val(perAmt);
                    $('#last_installment_note').show();
                    $('#last_installment_note_text').html(
                        `Total <strong>${count} installments</strong> &mdash; ` +
                        `First <strong>${fullCount}</strong> installments = <strong>${numFmt(perAmt)}</strong> each &mdash; ` +
                        `Last (<strong>${count}th</strong>) installment = <strong>${numFmt(lastAmt)}</strong> (baqi amount).`
                    );
                } else {
                    // Evenly divisible
                    count = fullCount;
                    perInstallment = perAmt;
                    // Pass user-entered per-installment to controller
                    $('#per_installment_override').val(perAmt);
                    $('#last_installment_note').show();
                    $('#last_installment_note_text').html(
                        `Total <strong>${count} installments</strong> of <strong>${numFmt(perAmt)}</strong> each &mdash; Balance evenly divides.`
                    );
                }

            } else {
                $('#last_installment_note').hide();
            }

            // Auto-fill hidden installment_count for form submission
            $('#installment_count').val(count > 0 ? count : '');
        }

        const lastDate = addPeriod(firstDate, type, count);

        // Update summary cards
        $('#summary_remaining').text(numFmt(remaining));
        $('#summary_per_installment').text(count > 0 ? numFmt(perInstallment) : 'Rs. 0');
        $('#summary_first_date').text(firstDate ? formatDate(firstDate) : '—');
        $('#summary_last_date').text(lastDate  ? formatDate(lastDate)  : '—');
        $('#summary_count').text(count > 0 ? count + ' installments' : '—');
        $('#summary_type').text(typeLabels[type] || 'Monthly');
        $('#last_installment_date_preview').val(lastDate);
        $('#installment_count_hint').text(hints[type] || hints.monthly);
    }

    // ── Events ─────────────────────────────────────────────────────────────
    $('#product_id').on('change', function () {
        const price = $(this).find(':selected').data('price') || '';
        $('#total_price').val(price);
        recalculate();
    });

    $('#installment_type').on('change', recalculate);
    $('#advance_payment, #installment_count, #first_installment_date').on('input change', recalculate);
    $('#per_installment_input').on('input', recalculate);

    // ── Init ───────────────────────────────────────────────────────────────
    recalculate();
});
</script>
@endpush