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
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-bar-chart"></i> Statistics</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="widget style1 navy-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-users fa-3x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Total Customers</span>
                                            <h2 class="font-bold">{{ $statistics['total_customers'] }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-list-alt fa-3x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Collections</span>
                                            <h2 class="font-bold">{{ $statistics['total_installments'] }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="widget style1 yellow-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-money fa-3x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span>Collected</span>
                                            <h2 class="font-bold" style="font-size:18px;">Rs. {{ number_format($statistics['total_collected'], 0) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Customers Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-users"></i> My Customers
                            <span class="badge badge-primary m-l-xs">{{ $customers->count() }}</span>
                        </h5>
                        <div class="ibox-tools">
                            @can('edit-recovery-officers')
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#assignCustomersModal">
                                    <i class="fa fa-plus"></i> Assign Customers
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="ibox-content">
                        @if ($customers->count() > 0)
                            <div class="table-responsive">
                                <table id="customersTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Account No</th>
                                            <th>Customer Name</th>
                                            <th>Phone</th>
                                            <th>Total Amount</th>
                                            <th>Paid</th>
                                            <th>Remaining</th>
                                            <th>Installments</th>
                                            <th>Assigned On</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><code>{{ $customer->account_no }}</code></td>
                                                <td>
                                                    <strong>{{ $customer->name }}</strong>
                                                    @if ($customer->is_defaulter)
                                                        <span class="label label-danger m-l-xs">Defaulter</span>
                                                    @endif
                                                </td>
                                                <td>{{ $customer->mobile_1 ?? '-' }}</td>
                                                <td class="text-right">
                                                    <strong>Rs. {{ number_format($customer->stats['total_amount'], 0) }}</strong>
                                                </td>
                                                <td class="text-right text-navy">
                                                    Rs. {{ number_format($customer->stats['paid_amount'], 0) }}
                                                </td>
                                                <td class="text-right text-danger">
                                                    Rs. {{ number_format($customer->stats['remaining_balance'], 0) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-success" title="Paid">
                                                        {{ $customer->stats['paid_installments'] }} Paid
                                                    </span>
                                                    <span class="badge badge-warning" title="Pending">
                                                        {{ $customer->stats['pending_installments'] }} Pending
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $customer->pivot->assigned_at ? \Carbon\Carbon::parse($customer->pivot->assigned_at)->format('d M, Y') : 'N/A' }}
                                                    </small>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="{{ route('customers.statement', $customer) }}"
                                                           class="btn btn-xs btn-info" title="View Customer">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        @can('edit-recovery-officers')
                                                            <button class="btn btn-xs btn-danger btn-remove-customer"
                                                                    title="Remove from this officer"
                                                                    data-customer-id="{{ $customer->id }}"
                                                                    data-customer-name="{{ $customer->name }}"
                                                                    data-officer-id="{{ $recoveryOfficer->id }}">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center" style="padding: 50px 0;">
                                <i class="fa fa-users fa-4x text-muted"></i>
                                <h4 class="text-muted m-t-md">No Customers Assigned Yet</h4>
                                <p class="text-muted">Click "Assign Customers" button to assign customers to this officer.</p>
                                @can('edit-recovery-officers')
                                    <button class="btn btn-primary m-t-sm" data-toggle="modal" data-target="#assignCustomersModal">
                                        <i class="fa fa-plus"></i> Assign Customers
                                    </button>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Installments Collected -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-history"></i> Recent Installments Collected</h5>
                    </div>
                    <div class="ibox-content">
                        @php
                            $recentInstallments = $recoveryOfficer->installments()
                                ->where('status', 'paid')
                                ->with(['purchase.customer', 'purchase.product'])
                                ->latest('date')
                                ->take(10)
                                ->get();
                        @endphp
                        @if ($recentInstallments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Paid Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentInstallments as $installment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $installment->purchase->customer->name ?? '-' }}</strong><br>
                                                    <small class="text-muted">{{ $installment->purchase->customer->account_no ?? '' }}</small>
                                                </td>
                                                <td>{{ $installment->purchase->product->name ?? '-' }}</td>
                                                <td><strong>Rs. {{ number_format($installment->installment_amount, 0) }}</strong></td>
                                                <td>{{ $installment->date ? \Carbon\Carbon::parse($installment->date)->format('d M, Y') : '-' }}</td>
                                                <td>
                                                    <span class="label label-success">Paid</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center" style="padding: 30px 0;">
                                <i class="fa fa-inbox fa-3x text-muted"></i>
                                <p class="text-muted m-t-md">No installments collected yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Customers Modal -->
    <div class="modal fade" id="assignCustomersModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-users"></i> Assign Customers to <strong>{{ $recoveryOfficer->name }}</strong>
                    </h4>
                </div>
                <div class="modal-body">
                    @if ($allCustomers->count() > 0)
                        <div class="form-group">
                            <label>Select Customers <small class="text-muted">(Multiple select kar sakte hain)</small></label>
                            <select id="customerSelect" multiple class="form-control" style="width:100%;">
                                @foreach ($allCustomers as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }} — {{ $c->account_no }} ({{ $c->mobile_1 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="assignAlert" class="alert" style="display:none;"></div>
                    @else
                        <div class="text-center" style="padding: 30px 0;">
                            <i class="fa fa-check-circle fa-3x text-navy"></i>
                            <p class="m-t-md text-muted">Sab customers already assign ho chuke hain ya koi customer nahi hai.</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                    @if ($allCustomers->count() > 0)
                        <button type="button" id="btnAssign" class="btn btn-primary">
                            <i class="fa fa-check"></i> Assign Selected
                        </button>
                    @endif
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
        .widget h2 { margin: 5px 0 0 0; }
        .widget span { font-size: 12px; text-transform: uppercase; }
        .widget i { opacity: 0.5; }
        .yellow-bg { background-color: #f8ac59 !important; color: white; }

        /* Select2 dropdown modal ke andar theek dikhane ke liye */
        #assignCustomersModal .select2-container {
            z-index: 99999 !important;
        }
        #assignCustomersModal .select2-dropdown {
            z-index: 99999 !important;
        }
        .select2-container--open {
            z-index: 99999 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {

            // DataTable for customers
            @if ($customers->count() > 0)
            $('#customersTable').DataTable({
                paging: true,
                pageLength: 15,
                lengthChange: false,
                info: true,
                ordering: true,
                searching: true,
                responsive: true,
                columnDefs: [{ orderable: false, targets: [-1] }]
            });
            @endif

            // Select2 — modal khulne ke baad initialize karo (dropdown peeche jane se bachne ke liye)
            $('#assignCustomersModal').on('shown.bs.modal', function () {
                $('#customerSelect').select2({
                    placeholder: 'Customer search karein...',
                    allowClear: true,
                    dropdownParent: $('#assignCustomersModal'),
                    width: '100%'
                });
            });

            // Modal band hone pe Select2 destroy karo (dobara open pe fresh start)
            $('#assignCustomersModal').on('hidden.bs.modal', function () {
                if ($('#customerSelect').data('select2')) {
                    $('#customerSelect').select2('destroy');
                }
            });

            // Assign customers button
            $('#btnAssign').on('click', function () {
                var selectedIds = $('#customerSelect').val();
                if (!selectedIds || selectedIds.length === 0) {
                    showAssignAlert('warning', 'Pehle koi customer select karein.');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Assigning...');

                $.ajax({
                    url: '{{ route("recovery-officers.assign-customers", $recoveryOfficer) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customer_ids: selectedIds
                    },
                    success: function (res) {
                        if (res.success) {
                            showAssignAlert('success', res.message + ' Page reload ho raha hai...');
                            setTimeout(function () { location.reload(); }, 1500);
                        }
                    },
                    error: function (xhr) {
                        showAssignAlert('danger', 'Error: ' + (xhr.responseJSON?.message || 'Kuch masla ho gaya.'));
                        btn.prop('disabled', false).html('<i class="fa fa-check"></i> Assign Selected');
                    }
                });
            });

            // Remove customer button
            $(document).on('click', '.btn-remove-customer', function () {
                var customerId  = $(this).data('customer-id');
                var customerName = $(this).data('customer-name');
                var officerId   = $(this).data('officer-id');
                var row         = $(this).closest('tr');

                if (!confirm(customerName + ' ko is officer se remove karna chahte hain?')) return;

                $.ajax({
                    url: '/admin/recovery-officers/' + officerId + '/remove-customer/' + customerId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            row.fadeOut(400, function () { $(this).remove(); });
                        }
                    },
                    error: function () {
                        toastr.error('Remove karne mein masla hua.');
                    }
                });
            });

            function showAssignAlert(type, msg) {
                $('#assignAlert')
                    .removeClass('alert-success alert-danger alert-warning')
                    .addClass('alert-' + type)
                    .html(msg)
                    .show();
            }
        });
    </script>
@endpush
