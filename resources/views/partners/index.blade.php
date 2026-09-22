@extends('layouts.master')

@section('content')
<div class="container-fluid" style="margin-bottom: 60px;">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none"><i class="fa fa-handshake-o text-navy"></i> Partners</h2>
            <small class="text-muted">Business partners who invest in purchases.</small>
        </div>
        <div class="col-sm-4 text-right" style="margin-top:25px;">
            <a href="{{ route('partners.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New Partner
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="ibox">
        <div class="ibox-title">
            <h5><i class="fa fa-list"></i> Partners List</h5>
        </div>
        <div class="ibox-content">
            @if($partners->count() === 0)
                <div class="text-center" style="padding:40px;">
                    <i class="fa fa-handshake-o" style="font-size:48px;color:#ddd;"></i>
                    <h4 style="color:#aaa;margin-top:10px;">No Partner Found</h4>
                    <a href="{{ route('partners.create') }}" class="btn btn-primary m-t-sm">
                        <i class="fa fa-plus"></i> Add First Partner
                    </a>
                </div>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Total Purchases</th>
                            <th>Total Invest</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partners as $partner)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $partner->name }}</strong></td>
                            <td>{{ $partner->phone ?? '—' }}</td>
                            <td>{{ $partner->email ?? '—' }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $partner->purchase_partners_count }}</span>
                            </td>
                            <td>
                                <strong>Rs. {{ number_format($partner->purchase_partners_sum_share_amount ?? 0, 0) }}</strong>
                            </td>
                            <td>
                                @if($partner->is_active)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('partners.show', $partner) }}" class="btn btn-xs btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('partners.edit', $partner) }}" class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('partners.destroy', $partner) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
