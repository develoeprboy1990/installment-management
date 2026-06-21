@extends('layouts.master')

@section('content')
<div class="container-fluid" style="margin-bottom: 60px;">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none"><i class="fa fa-edit text-navy"></i> Edit Partner</h2>
            <small class="text-muted">Update details for {{ $partner->name }}.</small>
        </div>
        <div class="col-sm-4 text-right" style="margin-top:25px;">
            <a href="{{ route('partners.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul class="m-b-none">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-handshake-o"></i> Partner Details</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('partners.update', $partner) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fa fa-user"></i> Partner Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ old('name', $partner->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">
                                        <i class="fa fa-phone"></i> Phone Number
                                    </label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           value="{{ old('phone', $partner->phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fa fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{ old('email', $partner->email) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">
                                        <i class="fa fa-sticky-note"></i> Notes
                                    </label>
                                    <textarea class="form-control" name="notes" id="notes"
                                              rows="2">{{ old('notes', $partner->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1"
                                       {{ $partner->is_active ? 'checked' : '' }}>
                                Active Partner
                            </label>
                        </div>

                        <div class="form-group m-b-none">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update
                            </button>
                            <a href="{{ route('partners.index') }}" class="btn btn-default m-l-sm">
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
