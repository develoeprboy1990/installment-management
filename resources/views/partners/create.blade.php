@extends('layouts.master')

@section('content')
<div class="container-fluid" style="margin-bottom: 60px;">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none"><i class="fa fa-user-plus text-navy"></i> Add New Partner</h2>
            <small class="text-muted">Fill in the business partner details.</small>
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
                    <form action="{{ route('partners.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fa fa-user"></i> Partner Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ old('name') }}" placeholder="e.g. Ahmed Ali" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">
                                        <i class="fa fa-phone"></i> Phone Number
                                    </label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           value="{{ old('phone') }}" placeholder="e.g. 03001234567">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fa fa-envelope"></i> Email (Optional)
                                    </label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{ old('email') }}" placeholder="e.g. partner@email.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">
                                        <i class="fa fa-sticky-note"></i> Notes (Optional)
                                    </label>
                                    <textarea class="form-control" name="notes" id="notes"
                                              rows="2" placeholder="Any note or remark...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-b-none">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Partner
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
