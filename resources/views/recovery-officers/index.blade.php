@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-8">
                <h2 class="m-b-none">Recovery Officers</h2>
                <small class="text-muted">Manage recovery officers responsible for installment collections.</small>
            </div>
            <div class="col-sm-4 text-right" style="margin-top: 30px;">
                @can('create-recovery-officers')
                    <a href="{{ route('recovery-officers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New
                        Officer</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>List</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                            <table id="officersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Total Collected</th>
                                        <th>Collections</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($officers as $officer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><code>{{ $officer->employee_id }}</code></td>
                                            <td>{{ $officer->name }}</td>
                                            <td>{{ $officer->phone ?? '-' }}</td>
                                            <td>{{ $officer->email ?? '-' }}</td>
                                            <td>Rs. {{ number_format($officer->getTotalCollected(), 2) }}</td>
                                            <td><span class="badge badge-info">{{ $officer->getInstallmentsCount() }}</span>
                                            </td>
                                            <td>
                                                <span class="label label-{{ $officer->is_active ? 'success' : 'danger' }}">
                                                    {{ $officer->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    @can('view-recovery-officers')
                                                        <a href="{{ route('recovery-officers.show', $officer) }}"
                                                            class="btn btn-xs btn-info" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit-recovery-officers')
                                                        <a href="{{ route('recovery-officers.edit', $officer) }}"
                                                            class="btn btn-xs btn-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete-recovery-officers')
                                                        <form action="{{ route('recovery-officers.destroy', $officer) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Are you sure you want to delete this recovery officer?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-xs btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center" style="padding: 24px 0;">
                                                <p class="text-muted">No recovery officers found.</p>
                                                @can('create-recovery-officers')
                                                    <a href="{{ route('recovery-officers.create') }}"
                                                        class="btn btn-primary btn-sm">Add First Officer</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-group .btn {
            margin-right: 2px;
        }

        code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.9em;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            var dt = $('#officersTable').DataTable({
                paging: true,
                pageLength: 10,
                lengthChange: false,
                info: true,
                ordering: true,
                searching: true,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });

            $('#officerSearch').on('keyup change', function() {
                dt.search(this.value).draw();
            });
        });
    </script>
@endpush
