@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-8">
                <h1 class="m-b-none">Edit Recovery Officer</h1>
                <small class="text-muted">Update recovery officer details and status.</small>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('recovery-officers.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back
                    to Officers</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</strong>
                <ul class="m-t-xs m-b-none">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Officer Information</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('recovery-officers.update', $recoveryOfficer) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                                        <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id"
                                            placeholder="e.g., EMP-001"
                                            value="{{ old('employee_id', $recoveryOfficer->employee_id) }}" required>
                                        @if ($errors->has('employee_id'))
                                            <span class="help-block">{{ $errors->first('employee_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="e.g., John Doe" value="{{ old('name', $recoveryOfficer->name) }}"
                                            required>
                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            placeholder="e.g., 03001234567"
                                            value="{{ old('phone', $recoveryOfficer->phone) }}">
                                        @if ($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="e.g., officer@example.com"
                                            value="{{ old('email', $recoveryOfficer->email) }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter full address">{{ old('address', $recoveryOfficer->address) }}</textarea>
                                        @if ($errors->has('address'))
                                            <span class="help-block">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select class="form-control" id="is_active" name="is_active">
                                            <option value="1"
                                                {{ old('is_active', $recoveryOfficer->is_active) == 1 ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0"
                                                {{ old('is_active', $recoveryOfficer->is_active) == 0 ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        <small class="text-muted">Active officers can be assigned to collect
                                            installments.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="m-t-sm">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update
                                    Officer</button>
                                <a href="{{ route('recovery-officers.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Meta</h5>
                    </div>
                    <div class="ibox-content">
                        <p class="m-b-xs"><strong>Officer ID:</strong> #{{ $recoveryOfficer->id }}</p>
                        <p class="m-b-xs"><strong>Created:</strong>
                            {{ $recoveryOfficer->created_at?->format('d/m/Y H:i') }}</p>
                        <p class="m-b-none"><strong>Last Updated:</strong>
                            {{ $recoveryOfficer->updated_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Statistics</h5>
                    </div>
                    <div class="ibox-content">
                        <p class="m-b-xs"><strong>Total Collections:</strong>
                            {{ $recoveryOfficer->getInstallmentsCount() }}</p>
                        <p class="m-b-none"><strong>Total Collected:</strong> Rs.
                            {{ number_format($recoveryOfficer->getTotalCollected(), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
