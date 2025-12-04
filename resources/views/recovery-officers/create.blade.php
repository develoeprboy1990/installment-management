@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-12">
                <h2 class="m-b-none">Add New Recovery Officer</h2>
                <small class="text-muted">Create a new recovery officer for installment collections.</small>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Officer Information</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('recovery-officers.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Enter officer's full name" required>
                                        @error('name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('employee_id') is-invalid @enderror" id="employee_id"
                                            name="employee_id" value="{{ old('employee_id') }}" placeholder="e.g., EMP-001"
                                            required>
                                        @error('employee_id')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}"
                                            placeholder="e.g., +92 300 1234567">
                                        @error('phone')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="officer@example.com">
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    placeholder="Enter complete address">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <strong>Active Status</strong>
                                        <small class="text-muted">(Officer can be assigned to installments)</small>
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Save Officer
                                </button>
                                <a href="{{ route('recovery-officers.index') }}" class="btn btn-white">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 4px;
        }

        .is-invalid {
            border-color: #ed5565;
        }

        .text-danger.small {
            display: block;
            margin-top: 5px;
        }
    </style>
@endpush
