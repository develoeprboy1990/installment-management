@extends('layouts.master')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Recovery Officer Details</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('recovery-officers.index') }}">Recovery Officers</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>{{ $recoveryOfficer->name }}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right" style="margin-top: 30px;">
            <a href="{{ route('recovery-officers.index') }}" class="btn btn-white btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <!-- Officer Information Card -->
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-user-circle-o"></i> Officer Information</h5>
                        <div class="ibox-tools">
                            @can('edit-recovery-officers')
                                <a href="{{ route('recovery-officers.edit', $recoveryOfficer) }}" class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Employee ID:</th>
                                <td><code>{{ $recoveryOfficer->employee_id }}</code></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td><strong>{{ $recoveryOfficer->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    @if ($recoveryOfficer->phone)
                                        <a href="tel:{{ $recoveryOfficer->phone }}" class="text-navy">
                                            <i class="fa fa-phone"></i> {{ $recoveryOfficer->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    @if ($recoveryOfficer->email)
                                        <a href="mailto:{{ $recoveryOfficer->email }}" class="text-navy">
                                            <i class="fa fa-envelope"></i> {{ $recoveryOfficer->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $recoveryOfficer->address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="label label-{{ $recoveryOfficer->is_active ? 'success' : 'danger' }}">
                                        {{ $recoveryOfficer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar"></i> {{ $recoveryOfficer->created_at->format('d M, Y h:i A') }}
                                    </small>
                                </td>
                            </tr>
                            @if ($recoveryOfficer->updated_at != $recoveryOfficer->created_at)
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa fa-edit"></i> {{ $recoveryOfficer->updated_at->format('d M, Y h:i A') }}
                                        </small>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-bar-chart"></i> Collection Statistics</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="widget style1 navy-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-list-alt fa-3x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Total Collections</span>
                                            <h2 class="font-bold">{{ $recoveryOfficer->getInstallmentsCount() }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-money fa-3x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Total Collected</span>
                                            <h2 class="font-bold">Rs. {{ number_format($recoveryOfficer->getTotalCollected(), 2) }}
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <h5 class="m-t-md"><i class="fa fa-info-circle"></i> Quick Info</h5>
                        <p class="small text-muted">
                            This recovery officer has collected <strong>{{ $recoveryOfficer->getInstallmentsCount() }}</strong>
                            installments
                            with a total amount of <strong>Rs.
                                {{ number_format($recoveryOfficer->getTotalCollected(), 2) }}</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Installments Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-history"></i> Recent Installments Collected</h5>
                    </div>
                    <div class="ibox-content">
                        @if ($recoveryOfficer->installments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recoveryOfficer->installments()->latest()->take(10)->get() as $installment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $installment->purchase->customer->name }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $installment->purchase->customer->account_no }}</small>
                                                </td>
                                                <td><strong>Rs.
                                                        {{ number_format($installment->installment_amount, 2) }}</strong>
                                                </td>
                                                <td>{{ $installment->due_date->format('d M, Y') }}</td>
                                                <td>
                                                    @if ($installment->paid_date)
                                                        {{ $installment->paid_date->format('d M, Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="label label-{{ $installment->status === 'paid' ? 'success' : ($installment->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($installment->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <a href="{{ route('installments.index') }}" class="btn btn-xs btn-info"
                                                        title="View Installments">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($recoveryOfficer->installments->count() > 10)
                                <div class="text-center m-t-md">
                                    <a href="{{ route('installments.index') }}" class="btn btn-white btn-sm">
                                        <i class="fa fa-list"></i> View All Installments
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center" style="padding: 40px 0;">
                                <i class="fa fa-inbox fa-3x text-muted"></i>
                                <p class="text-muted m-t-md">No installments collected yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-borderless td,
        .table-borderless th {
            border: none;
            padding: 8px 0;
        }

        .table-borderless th {
            color: #676a6c;
            font-weight: 600;
        }

        code {
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
            color: #1ab394;
        }

        .widget {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .widget h2 {
            margin: 5px 0 0 0;
        }

        .widget span {
            font-size: 12px;
            text-transform: uppercase;
        }

        .widget i {
            opacity: 0.5;
        }
    </style>
@endpush
