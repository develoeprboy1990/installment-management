@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-8">
                <h2 class="m-b-none">Products</h2>
                <small class="text-muted">Manage your catalog of products available for purchases.</small>
            </div>
            <div class="col-sm-4 text-right" style="margin-top: 30px;">
                @can('create-products')
                    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Product</a>
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
                                                <span class="label label-info">Rs.
                                                    {{ number_format($product->price, 2) }}</span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-xs btn-info view-customers-btn"
                                                        data-product-id="{{ $product->id }}" title="View Customers">
                                                        <i class="fa fa-users"></i>
                                                    </button>
                                                    @can('edit-products')
                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                            class="btn btn-xs btn-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete-products')
                                                        <form action="{{ route('products.destroy', $product->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                                            <td colspan="7" class="text-center" style="padding: 24px 0;">
                                                <p class="text-muted">No products found.</p>
                                                @can('create-products')
                                                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Add
                                                        First Product</a>
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

        <!-- Product Customers Modal -->
        <div class="modal fade" id="customersModal" tabindex="-1" role="dialog" aria-labelledby="customersModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="customersModalLabel">Product Customers</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Product Details -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5 class="panel-title"><i class="fa fa-cube"></i> Product Details</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Company:</strong> <span id="modal-product-company"></span></p>
                                        <p><strong>Model:</strong> <span id="modal-product-model"></span></p>
                                        <p><strong>Serial No:</strong> <code id="modal-product-serial"></code></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Cost Price:</strong> Rs. <span id="modal-product-cost"></span></p>
                                        <p><strong>Sell Price:</strong> <span class="label label-info">Rs. <span
                                                    id="modal-product-price"></span></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customers List -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <i class="fa fa-users"></i> Customers
                                    <span class="badge" id="total-customers-badge">0</span>
                                </h5>
                            </div>
                            <div class="panel-body" style="max-height: 400px; overflow-y: auto;">
                                <div id="customers-list">
                                    <!-- Customers will be loaded here via AJAX -->
                                    <div class="text-center">
                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                        <p>Loading customers...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

        /* Modal Styles */
        #customersModal .modal-dialog {
            width: 90%;
            max-width: 1000px;
        }

        #customersModal .panel {
            margin-bottom: 15px;
        }

        #customersModal .table-borderless td {
            border: none;
            padding: 4px 8px;
        }

        #customersModal .table-borderless tr:hover {
            background-color: #f9f9f9;
        }

        #customersModal .panel-body h5 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 600;
        }

        #customersModal .badge {
            background-color: #fff;
            color: #1ab394;
            font-size: 14px;
            padding: 5px 10px;
        }

        .view-customers-btn:hover {
            opacity: 0.8;
        }
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
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });

            $('#productSearch').on('keyup change', function() {
                dt.search(this.value).draw();
            });

            // View Customers Button Click Handler
            $('.view-customers-btn').on('click', function() {
                const productId = $(this).data('product-id');
                loadProductCustomers(productId);
            });

            function loadProductCustomers(productId) {
                // Show modal
                $('#customersModal').modal('show');

                // Reset modal content
                $('#customers-list').html(`
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <p>Loading customers...</p>
                    </div>
                `);

                // AJAX request to fetch customers
                $.ajax({
                    url: `/admin/products/${productId}/customers`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Populate product details
                            $('#modal-product-company').text(response.product.company);
                            $('#modal-product-model').text(response.product.model);
                            $('#modal-product-serial').text(response.product.serial_no);
                            $('#modal-product-cost').text(response.product.cost_price);
                            $('#modal-product-price').text(response.product.sell_price);
                            $('#total-customers-badge').text(response.total_customers);

                            // Populate customers list
                            if (response.customers.length > 0) {
                                let customersHtml = '';
                                response.customers.forEach(function(customer, index) {
                                    const statusClass = customer.status === 'completed' ?
                                        'success' :
                                        customer.status === 'active' ? 'primary' : 'warning';

                                    customersHtml += `
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <strong>${index + 1}. ${customer.customer_name}</strong>
                                                    <span class="label label-${statusClass} pull-right">${customer.status.toUpperCase()}</span>
                                                </h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5><i class="fa fa-user"></i> Customer Information</h5>
                                                        <table class="table table-condensed table-borderless">
                                                            <tr>
                                                                <td><strong>Name:</strong></td>
                                                                <td>${customer.customer_name}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>NIC:</strong></td>
                                                                <td><code>${customer.customer_nic || 'N/A'}</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Father Name:</strong></td>
                                                                <td>${customer.customer_father_name || 'N/A'}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Mobile 1:</strong></td>
                                                                <td>${customer.customer_mobile_1 || 'N/A'}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Mobile 2:</strong></td>
                                                                <td>${customer.customer_mobile_2 || 'N/A'}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Residence:</strong></td>
                                                                <td>${customer.customer_residence || 'N/A'}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Occupation:</strong></td>
                                                                <td>${customer.customer_occupation || 'N/A'}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5><i class="fa fa-shopping-cart"></i> Purchase Information</h5>
                                                        <table class="table table-condensed table-borderless">
                                                            <tr>
                                                                <td><strong>Purchase Date:</strong></td>
                                                                <td>${customer.purchase_date}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total Price:</strong></td>
                                                                <td>Rs. ${customer.total_price}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Advance Payment:</strong></td>
                                                                <td>Rs. ${customer.advance_payment}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Remaining Balance:</strong></td>
                                                                <td><strong class="text-danger">Rs. ${customer.remaining_balance}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Installment Months:</strong></td>
                                                                <td>${customer.installment_months} months</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Monthly Installment:</strong></td>
                                                                <td>Rs. ${customer.monthly_installment}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                                $('#customers-list').html(customersHtml);
                            } else {
                                $('#customers-list').html(`
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-info-circle"></i> No customers have purchased this product yet.
                                    </div>
                                `);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#customers-list').html(`
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i> Error loading customers: ${error}
                            </div>
                        `);
                    }
                });
            }
        });
    </script>
@endpush
