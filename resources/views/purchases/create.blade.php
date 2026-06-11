@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">New Purchase</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            Please fix the highlighted fields and try again.
        </div>
    @endif

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <select class="form-control" name="customer_id" id="customer_id" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (string) old('customer_id', request('customer')) === (string) $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->account_no }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                    <label for="product_id">Product <span class="text-danger">*</span></label>
                    <select class="form-control" name="product_id" id="product_id" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ (string) old('product_id') === (string) $product->id ? 'selected' : '' }}>
                                {{ $product->company }} {{ $product->model }} - Rs. {{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('purchase_date') ? ' has-error' : '' }}">
                    <label for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    @error('purchase_date')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group{{ $errors->has('total_price') ? ' has-error' : '' }}">
                    <label for="total_price">Total Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total_price" id="total_price" step="0.01" value="{{ old('total_price') }}" readonly required>
                    @error('total_price')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('advance_payment') ? ' has-error' : '' }}">
                    <label for="advance_payment">Advance Payment <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="advance_payment" id="advance_payment" step="0.01" min="0" value="{{ old('advance_payment') }}" required>
                    @error('advance_payment')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group{{ $errors->has('installment_months') ? ' has-error' : '' }}">
                    <label for="installment_months">Installment Period (Months) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="installment_months" id="installment_months" min="1" value="{{ old('installment_months') }}" required>
                    @error('installment_months')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('first_installment_date') ? ' has-error' : '' }}">
                    <label for="first_installment_date">First Installment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="first_installment_date" id="first_installment_date" value="{{ old('first_installment_date') }}" required>
                    @error('first_installment_date')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group{{ $errors->has('recovery_officer_id') ? ' has-error' : '' }}">
                    <label for="recovery_officer_id">Recovery Officer <span class="text-danger">*</span></label>
                    <select class="form-control" name="recovery_officer_id" id="recovery_officer_id" required>
                        <option value="">Select Recovery Officer</option>
                        @foreach($recoveryOfficers as $officer)
                            <option value="{{ $officer->id }}" {{ (string) old('recovery_officer_id') === (string) $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }} ({{ $officer->employee_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('recovery_officer_id')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="monthly_installment">Monthly Installment</label>
                    <input type="number" class="form-control" name="monthly_installment" id="monthly_installment" step="0.01" value="{{ old('monthly_installment') }}" readonly>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save Purchase</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

@push('script')
<script>
$(document).ready(function() {
    const $form = $('#purchaseForm');
    const fields = {
        customer_id: {
            label: 'Customer',
            required: true
        },
        product_id: {
            label: 'Product',
            required: true
        },
        purchase_date: {
            label: 'Purchase date',
            required: true
        },
        total_price: {
            label: 'Total price',
            required: true,
            numeric: true,
            min: 0
        },
        advance_payment: {
            label: 'Advance payment',
            required: true,
            numeric: true,
            min: 0
        },
        installment_months: {
            label: 'Installment period',
            required: true,
            integer: true,
            min: 1
        },
        first_installment_date: {
            label: 'First installment date',
            required: true
        },
        recovery_officer_id: {
            label: 'Recovery officer',
            required: true
        }
    };

    // Auto-fill price when product is selected
    $('#product_id').change(function() {
        const price = $(this).find(':selected').data('price');
        $('#total_price').val(price || '');
        calculateInstallment();
        validateField('product_id');
        validateField('total_price');
    });

    // Calculate installment when advance or months change
    $('#advance_payment, #installment_months').on('input', function() {
        calculateInstallment();
    });

    $form.on('input change', 'input, select', function() {
        validateField(this.name);

        if (this.name === 'purchase_date') {
            validateField('first_installment_date');
        }

        if (this.name === 'total_price') {
            validateField('advance_payment');
        }
    });

    $form.on('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            const $firstInvalid = $form.find('.has-error:first').find('input, select').first();

            if ($firstInvalid.length) {
                $firstInvalid.focus();
            }
        }
    });

    if ($('#product_id').val() && !$('#total_price').val()) {
        $('#total_price').val($('#product_id').find(':selected').data('price') || '');
    }

    calculateInstallment();

    function calculateInstallment() {
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const advance = parseFloat($('#advance_payment').val()) || 0;
        const months = parseInt($('#installment_months').val(), 10) || 0;

        if (!totalPrice || advance > totalPrice || months < 1) {
            $('#monthly_installment').val('');
            return;
        }

        const remaining = totalPrice - advance;
        const monthly = remaining / months;

        $('#monthly_installment').val(monthly.toFixed(2));
    }

    function validateForm() {
        let isValid = true;

        Object.keys(fields).forEach(function(name) {
            if (!validateField(name)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField(name) {
        if (!fields[name]) {
            return true;
        }

        const field = fields[name];
        const $input = $('[name="' + name + '"]');
        const value = $.trim($input.val());
        let message = '';

        if (field.required && !value) {
            message = field.label + ' is required.';
        } else if (field.numeric && value !== '' && isNaN(parseFloat(value))) {
            message = field.label + ' must be a valid number.';
        } else if (field.integer && value !== '' && !Number.isInteger(Number(value))) {
            message = field.label + ' must be a whole number.';
        } else if (field.min !== undefined && value !== '' && parseFloat(value) < field.min) {
            message = field.label + ' must be at least ' + field.min + '.';
        }

        if (!message && name === 'advance_payment') {
            const totalPrice = parseFloat($('#total_price').val()) || 0;
            const advance = parseFloat(value) || 0;

            if (totalPrice > 0 && advance > totalPrice) {
                message = 'Advance payment cannot be greater than total price.';
            }
        }

        if (!message && name === 'first_installment_date') {
            const purchaseDate = $('#purchase_date').val();

            if (purchaseDate && value && value < purchaseDate) {
                message = 'First installment date cannot be before purchase date.';
            }
        }

        setFieldError($input, message);
        return message === '';
    }

    function setFieldError($input, message) {
        const $group = $input.closest('.form-group');
        let $error = $group.find('.runtime-error');

        if (!$error.length) {
            $error = $('<span class="help-block text-danger runtime-error"></span>');
            $group.append($error);
        }

        if (message) {
            $group.addClass('has-error');
            $error.text(message).show();
        } else {
            $group.removeClass('has-error');
            $error.text('').hide();
        }
    }
});
</script>
@endpush
@endsection
