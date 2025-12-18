<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Guarantors</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-guarantors')): ?>
                <a href="<?php echo e(route('guarantors.create')); ?>" class="btn btn-primary">Add Guarantor</a>
            <?php endif; ?>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Customer</th>
                            <th>Name</th>
                            <th>Father's Name</th>
                            <th>NIC</th>
                            <th>Phone</th>
                            <th>Relation</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $guarantors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guarantor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php if($guarantor->image): ?>
                                        <img src="<?php echo e(asset($guarantor->image)); ?>" alt="Guarantor Image" width="50"
                                            height="50" class="rounded-circle object-fit-cover">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="font-weight:700;width: 50px;height: 50px;font-size: 14px;background: darkgrey;border-radius: 27px;text-align: center;line-height: 50px;">
                                            <?php echo e(strtoupper(substr($guarantor->name, 0, 2))); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($guarantor->customer->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($guarantor->customer->account_no); ?></small>
                                </td>
                                <td><?php echo e($guarantor->name); ?></td>
                                <td><?php echo e($guarantor->father_name); ?></td>
                                <td><code><?php echo e($guarantor->nic); ?></code></td>
                                <td><?php echo e($guarantor->phone); ?></td>
                                <td><?php echo e($guarantor->relation); ?></td>
                                <td>
                                    <?php
                                        switch ($guarantor->guarantor_no) {
                                            case 1:
                                                $label = 'Primary';
                                                $color = 'primary';
                                                break;
                                            case 2:
                                                $label = 'Secondary';
                                                $color = 'secondary';
                                                break;
                                            case 3:
                                                $label = 'Third';
                                                $color = 'info';
                                                break;
                                            case 4:
                                                $label = 'Reserve';
                                                $color = 'dark';
                                                break;
                                            default:
                                                $label = 'Unknown';
                                                $color = 'light';
                                        }
                                    ?>

                                    <span class="badge bg-<?php echo e($color); ?>"><?php echo e($label); ?> Guarantor</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-guarantors')): ?>
                                            <a href="<?php echo e(route('guarantors.show', $guarantor->id)); ?>"
                                                class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-guarantors')): ?>
                                            <a href="<?php echo e(route('guarantors.edit', $guarantor->id)); ?>"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-guarantors')): ?>
                                            <form action="<?php echo e(route('guarantors.destroy', $guarantor->id)); ?>" method="POST"
                                                class="" style="display: grid;"
                                                onsubmit="return confirm('Are you sure you want to delete this guarantor?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <p class="text-muted">No guarantors found.</p>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-guarantors')): ?>
                                        <a href="<?php echo e(route('guarantors.create')); ?>" class="btn btn-primary btn-sm">Add First
                                            Guarantor</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .object-fit-cover {
            object-fit: cover;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                paging: false,
                info: false,
                ordering: true,
                searching: true,
                responsive: true,
                columnDefs: [{
                        orderable: false,
                        targets: [1, -1]
                    } // Disable sorting on image and actions columns
                ]
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/guarantors/index.blade.php ENDPATH**/ ?>