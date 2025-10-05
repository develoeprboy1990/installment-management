@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h2 class="m-b-none">Products</h2>
            <small class="text-muted">Manage your catalog of products available for purchases.</small>
        </div>
        <div class="col-sm-4 text-right" style="margin-top: 30px;">
            <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Product</a>
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
                        <table id="productsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Model</th>
                                    <th>Serial No</th>
                                    <th>Cost Price</th>
                                    <th>Sell Price</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->company }}</td>
                                        <td>{{ $product->model }}</td>
                                        <td><code>{{ $product->serial_no }}</code></td>
                                        <td>Rs. {{ number_format($product->cost_price, 2) }}</td>
                                        <td>
                                            <span class="label label-info">Rs. {{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-xs btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-xs btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center" style="padding: 24px 0;">
                                            <p class="text-muted">No products found.</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Add First Product</a>
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
.btn-group .btn { margin-right: 2px; }
code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-size: 0.9em; }
</style>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        var dt = $('#productsTable').DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            info: true,
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [-1] }
            ]
        });

        $('#productSearch').on('keyup change', function(){
            dt.search(this.value).draw();
        });
    });
    </script>
@endpush
