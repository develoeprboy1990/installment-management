@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Recovery Officer</h1>

    <form action="{{ route('recoveryOfficer.update', $recoveryOfficer->id) }}" method="POST" class="p-4 shadow rounded bg-white">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Employee ID -->
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employee CNIC <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-primary" id="employee_id" name="employee_id" value="{{ $recoveryOfficer->employee_id }}" required>
            </div>

            <!-- Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control border-primary" id="name" name="name" value="{{ $recoveryOfficer->name }}" required>
            </div>
        </div>

        <div class="row">
            <!-- Phone -->
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control border-primary" id="phone" name="phone" value="{{ $recoveryOfficer->phone }}">
            </div>

            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control border-primary" id="email" name="email" value="{{ $recoveryOfficer->email }}">
            </div>
        </div>

        <div class="row">
            <!-- Status -->
            <div class="col-md-6 mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-control border-primary" id="is_active" name="is_active">
                    <option value="1" {{ $recoveryOfficer->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$recoveryOfficer->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success px-4 py-2 shadow-sm" style="margin-top: 20px;">
                <i class="fa fa-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection