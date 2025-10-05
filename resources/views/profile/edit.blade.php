{{-- <x-app-layout> --}}
@extends('layouts.master')

@section('content')
<div class="container-fluid" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-user"></i> {{ __('Profile Information') }}</h3>
                </div>
                <div class="panel-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-lock"></i> {{ __('Update Password') }}</h3>
                </div>
                <div class="panel-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @can('delete_account')
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> {{ __('Delete Account') }}</h3>
                </div>
                <div class="panel-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
{{-- </x-app-layout> --}}
