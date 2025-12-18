<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-8">
                <h2 class="m-b-none">Expenses</h2>
                <small class="text-muted">Manage business expenses including rent, salaries, and other costs.</small>
            </div>
            <div class="col-sm-4 text-right" style="margin-top: 30px;">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-expenses')): ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createExpenseModal">
                        <i class="fa fa-plus"></i> Add New Expense
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Expense List</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table id="expensesTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($expense->name); ?></td>
                                            <td><?php echo e($expense->email ?? '-'); ?></td>
                                            <td><?php echo e($expense->phone ?? '-'); ?></td>
                                            <td>
                                                <span class="label label-default">
                                                    <?php echo e($expense->formatted_type); ?>

                                                </span>
                                            </td>
                                            <td><strong>Rs. <?php echo e(number_format($expense->amount, 2)); ?></strong></td>
                                            <td><?php echo e($expense->expense_date->format('d M, Y')); ?></td>
                                            <td>
                                                <span
                                                    class="label label-<?php echo e($expense->status === 'paid' ? 'success' : ($expense->status === 'pending' ? 'warning' : 'danger')); ?>">
                                                    <?php echo e($expense->formatted_status); ?>

                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-xs btn-info view-expense-btn"
                                                        data-expense-id="<?php echo e($expense->id); ?>" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-expenses')): ?>
                                                        <button class="btn btn-xs btn-warning edit-expense-btn"
                                                            data-expense-id="<?php echo e($expense->id); ?>" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-expenses')): ?>
                                                        <button class="btn btn-xs btn-danger delete-expense-btn"
                                                            data-expense-id="<?php echo e($expense->id); ?>"
                                                            data-expense-name="<?php echo e($expense->name); ?>" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <form id="delete-form-<?php echo e($expense->id); ?>"
                                                            action="<?php echo e(route('expenses.destroy', $expense->id)); ?>"
                                                            method="POST" style="display: none;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="9" class="text-center" style="padding: 24px 0;">
                                                <p class="text-muted">No expenses found.</p>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-expenses')): ?>
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                        data-target="#createExpenseModal">
                                                        <i class="fa fa-plus"></i> Add First Expense
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Expense Modal -->
        <div class="modal fade" id="createExpenseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Create New Expense</h4>
                    </div>
                    <form id="createExpenseForm">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                        <span class="text-danger error-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control">
                                        <span class="text-danger error-email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control">
                                        <span class="text-danger error-phone"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expense Type <span class="text-danger">*</span></label>
                                        <select name="expense_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="rent">Rent</option>
                                            <option value="salary">Salary</option>
                                            <option value="utilities">Utilities</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <span class="text-danger error-expense_type"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" class="form-control" step="0.01"
                                            min="0" required>
                                        <span class="text-danger error-amount"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expense Date <span class="text-danger">*</span></label>
                                        <input type="date" name="expense_date" class="form-control" required>
                                        <span class="text-danger error-expense_date"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <span class="text-danger error-status"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <input type="text" name="payment_method" class="form-control"
                                            placeholder="e.g., Cash, Bank Transfer">
                                        <span class="text-danger error-payment_method"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="3"></textarea>
                                        <span class="text-danger error-description"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Create Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Expense Modal -->
        <div class="modal fade" id="editExpenseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Expense</h4>
                    </div>
                    <form id="editExpenseForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" id="edit-expense-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="edit-name" class="form-control"
                                            required>
                                        <span class="text-danger error-edit-name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" id="edit-email" class="form-control">
                                        <span class="text-danger error-edit-email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" id="edit-phone" class="form-control">
                                        <span class="text-danger error-edit-phone"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expense Type <span class="text-danger">*</span></label>
                                        <select name="expense_type" id="edit-expense_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="rent">Rent</option>
                                            <option value="salary">Salary</option>
                                            <option value="utilities">Utilities</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <span class="text-danger error-edit-expense_type"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" id="edit-amount" class="form-control"
                                            step="0.01" min="0" required>
                                        <span class="text-danger error-edit-amount"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expense Date <span class="text-danger">*</span></label>
                                        <input type="date" name="expense_date" id="edit-expense_date"
                                            class="form-control" required>
                                        <span class="text-danger error-edit-expense_date"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" id="edit-status" class="form-control" required>
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <span class="text-danger error-edit-status"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <input type="text" name="payment_method" id="edit-payment_method"
                                            class="form-control">
                                        <span class="text-danger error-edit-payment_method"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                                        <span class="text-danger error-edit-description"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Expense Modal -->
        <div class="modal fade" id="viewExpenseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-file-text"></i> Expense Details</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Name:</th>
                                        <td id="view-name"></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td id="view-email"></td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td id="view-phone"></td>
                                    </tr>
                                    <tr>
                                        <th>Expense Type:</th>
                                        <td><span class="label label-default" id="view-type"></span></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="label" id="view-status"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Amount:</th>
                                        <td><strong class="text-primary">Rs. <span id="view-amount"></span></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Expense Date:</th>
                                        <td id="view-date"></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td id="view-payment_method"></td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td id="view-created_at"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5><strong>Description:</strong></h5>
                                <p id="view-description" class="well"></p>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .btn-group .btn {
            margin-right: 2px;
        }

        .table-borderless td,
        .table-borderless th {
            border: none;
            padding: 8px;
        }

        .table-borderless tr:hover {
            background-color: #f9f9f9;
        }

        .modal-lg {
            width: 90%;
            max-width: 900px;
        }

        .error-name,
        .error-email,
        .error-phone,
        .error-expense_type,
        .error-amount,
        .error-expense_date,
        .error-status,
        .error-payment_method,
        .error-description,
        .error-edit-name,
        .error-edit-email,
        .error-edit-phone,
        .error-edit-expense_type,
        .error-edit-amount,
        .error-edit-expense_date,
        .error-edit-status,
        .error-edit-payment_method,
        .error-edit-description {
            display: block;
            font-size: 12px;
            margin-top: 5px;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            font-family: inherit;
        }

        .swal2-actions {
            gap: 10px;
        }

        .swal2-actions .btn {
            margin: 0 5px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
        }

        .swal2-actions .btn-danger {
            background-color: #ed5565;
            border-color: #ed5565;
            color: white;
        }

        .swal2-actions .btn-danger:hover {
            background-color: #da4453;
            border-color: #da4453;
        }

        .swal2-actions .btn-primary {
            background-color: #1ab394;
            border-color: #1ab394;
            color: white;
        }

        .swal2-actions .btn-primary:hover {
            background-color: #18a689;
            border-color: #18a689;
        }

        /* Modal Backdrop and Z-Index Fixes */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .modal {
            z-index: 1050;
            overflow-y: auto;
        }

        .modal-dialog {
            margin: 30px auto;
        }

        .modal-content {
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }


        /* Prevent body scroll when modal is open - NO PADDING */
        body.modal-open {
            overflow: hidden !important;
        }

        /* Prevent Bootstrap from adding padding-right */
        body.modal-open,
        body.modal-open .navbar-fixed-top,
        body.modal-open .navbar-fixed-bottom,
        body.modal-open .navbar-static-top {
            padding-right: 0 !important;
        }

        /* Fix for main wrapper */
        body.modal-open #wrapper,
        body.modal-open #page-wrapper {
            padding-right: 0 !important;
        }

        /* Fix for DataTables when modal is open */
        body.modal-open .dataTables_wrapper,
        body.modal-open .dataTables_scrollBody {
            padding-right: 0 !important;
        }

        /* Fix for sidebar */
        body.modal-open .navbar-static-side {
            padding-right: 0 !important;
        }

        /* Ensure modal is properly positioned */
        .modal.fade .modal-dialog {
            transform: translate(0, -25%);
            transition: transform 0.3s ease-out;
        }

        .modal.in .modal-dialog {
            transform: translate(0, 0);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function() {
            const expenseFlash = sessionStorage.getItem('expenseFlash');
            const expenseFlashType = sessionStorage.getItem('expenseFlashType') || 'success';

            if (expenseFlash) {
                toastr[expenseFlashType](expenseFlash);
                sessionStorage.removeItem('expenseFlash');
                sessionStorage.removeItem('expenseFlashType');
            }

            // Comprehensive fix for Bootstrap modal scrollbar issue
            // Override Bootstrap's modal methods that cause width changes
            if ($.fn.modal && $.fn.modal.Constructor) {
                var originalShow = $.fn.modal.Constructor.prototype.show;
                var originalHide = $.fn.modal.Constructor.prototype.hide;
                
                $.fn.modal.Constructor.prototype.setScrollbar = function() {
                    // Do nothing - prevent Bootstrap from adding padding
                };
                
                $.fn.modal.Constructor.prototype.resetScrollbar = function() {
                    // Do nothing - prevent Bootstrap from removing padding
                };
                
                $.fn.modal.Constructor.prototype.checkScrollbar = function() {
                    // Always return 0 scrollbar width
                    this.scrollbarWidth = 0;
                };
            }
            
            // Additional event-based fix
            $(document).on('show.bs.modal', '.modal', function() {
                setTimeout(function() {
                    $('body, #wrapper, #page-wrapper, .navbar-static-side').css('padding-right', '0');
                }, 0);
            });
            
            $(document).on('hidden.bs.modal', '.modal', function() {
                $('body, #wrapper, #page-wrapper, .navbar-static-side').css('padding-right', '0');
            });

            // Initialize DataTable only if there are rows with data
            if ($('#expensesTable tbody tr').length > 0 && !$('#expensesTable tbody tr td[colspan]').length) {
                var dt = $('#expensesTable').DataTable({
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
            }

            // Create Expense Form Submit
            $('#createExpenseForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.text-danger').text('');

                $.ajax({
                    url: '/admin/expenses',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#createExpenseModal').modal('hide');
                            $('#createExpenseForm')[0].reset();
                            sessionStorage.setItem('expenseFlash', response.message || 'Expense created successfully.');
                            sessionStorage.setItem('expenseFlashType', 'success');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.error-' + key).text(value[0]);
                            });
                        } else {
                            toastr.error('Failed to create expense. Please try again.');
                        }
                    }
                });
            });

            // Edit Expense Button Click
            $('.edit-expense-btn').on('click', function() {
                var expenseId = $(this).data('expense-id');

                // Clear previous errors
                $('.text-danger').text('');

                // Show modal immediately with loading state
                $('#editExpenseModal').modal('show');
                $('#editExpenseForm :input').prop('disabled', true);

                $.ajax({
                    url: '/admin/expenses/' + expenseId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#edit-expense-id').val(response.expense.id);
                            $('#edit-name').val(response.expense.name);
                            $('#edit-email').val(response.expense.email);
                            $('#edit-phone').val(response.expense.phone);
                            $('#edit-expense_type').val(response.expense.expense_type);
                            $('#edit-amount').val(response.expense.amount);
                            $('#edit-expense_date').val(response.expense.expense_date);
                            $('#edit-status').val(response.expense.status);
                            $('#edit-payment_method').val(response.expense.payment_method);
                            $('#edit-description').val(response.expense.description);

                            // Enable form after data is loaded
                            $('#editExpenseForm :input').prop('disabled', false);
                        }
                    },
                    error: function() {
                        $('#editExpenseModal').modal('hide');
                        alert('Error loading expense data');
                    }
                });
            });

            // Edit Expense Form Submit
            $('#editExpenseForm').on('submit', function(e) {
                e.preventDefault();

                var expenseId = $('#edit-expense-id').val();

                // Clear previous errors
                $('.text-danger').text('');

                $.ajax({
                    url: '/admin/expenses/' + expenseId,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editExpenseModal').modal('hide');
                            sessionStorage.setItem('expenseFlash', response.message || 'Expense updated successfully.');
                            sessionStorage.setItem('expenseFlashType', 'success');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.error-edit-' + key).text(value[0]);
                            });
                        } else {
                            toastr.error('Failed to update expense. Please try again.');
                        }
                    }
                });
            });

            // View Expense Button Click
            $('.view-expense-btn').on('click', function() {
                var expenseId = $(this).data('expense-id');

                // Show modal immediately
                $('#viewExpenseModal').modal('show');

                // Show loading text
                $('#view-name').text('Loading...');

                $.ajax({
                    url: '/admin/expenses/' + expenseId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var expense = response.expense;

                            $('#view-name').text(expense.name);
                            $('#view-email').text(expense.email || 'N/A');
                            $('#view-phone').text(expense.phone || 'N/A');
                            $('#view-type').text(expense.formatted_type);
                            $('#view-amount').text(expense.amount);
                            $('#view-date').text(expense.expense_date);
                            $('#view-payment_method').text(expense.payment_method || 'N/A');
                            $('#view-description').text(expense.description ||
                                'No description provided.');
                            $('#view-created_at').text(expense.created_at);

                            // Set status badge color
                            var statusClass = expense.status === 'paid' ? 'label-success' :
                                (expense.status === 'pending' ? 'label-warning' :
                                    'label-danger');
                            $('#view-status').removeClass().addClass('label ' + statusClass)
                                .text(expense.formatted_status);
                        }
                    },
                    error: function() {
                        $('#viewExpenseModal').modal('hide');
                        alert('Error loading expense details');
                    }
                });
            });


            // Delete Expense Button Click with SweetAlert2
            $(document).on('click', '.delete-expense-btn', function(e) {
                e.preventDefault();
                const expenseId = $(this).data('expense-id');
                const expenseName = $(this).data('expense-name');

                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete the expense:<br><strong>${expenseName}</strong>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fa fa-trash"></i> Yes, delete it!',
                    cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger btn-lg',
                        cancelButton: 'btn btn-primary btn-lg'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit the form
                        document.getElementById('delete-form-' + expenseId).submit();
                    }
                });
            });

            // Reset form when modal is closed
            $('#createExpenseModal').on('hidden.bs.modal', function() {
                $('#createExpenseForm')[0].reset();
                $('.text-danger').text('');
            });

            $('#editExpenseModal').on('hidden.bs.modal', function() {
                $('.text-danger').text('');
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/expenses/index.blade.php ENDPATH**/ ?>