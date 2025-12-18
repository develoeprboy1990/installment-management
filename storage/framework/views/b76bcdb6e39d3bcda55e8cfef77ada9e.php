<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row m-b-md">
            <div class="col-sm-8">
                <h2 class="m-b-none">Recovery Officers</h2>
                <small class="text-muted">Manage recovery officers responsible for installment collections.</small>
            </div>
            <div class="col-sm-4 text-right" style="margin-top: 30px;">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-recovery-officers')): ?>
                    <a href="<?php echo e(route('recovery-officers.create')); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New
                        Officer</a>
                <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = $officers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><code><?php echo e($officer->employee_id); ?></code></td>
                                            <td><?php echo e($officer->name); ?></td>
                                            <td><?php echo e($officer->phone ?? '-'); ?></td>
                                            <td><?php echo e($officer->email ?? '-'); ?></td>
                                            <td>Rs. <?php echo e(number_format($officer->getTotalCollected(), 2)); ?></td>
                                            <td><span class="badge badge-info"><?php echo e($officer->getInstallmentsCount()); ?></span>
                                            </td>
                                            <td>
                                                <span class="label label-<?php echo e($officer->is_active ? 'success' : 'danger'); ?>">
                                                    <?php echo e($officer->is_active ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-recovery-officers')): ?>
                                                        <a href="<?php echo e(route('recovery-officers.show', $officer)); ?>"
                                                            class="btn btn-xs btn-info" title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-recovery-officers')): ?>
                                                        <a href="<?php echo e(route('recovery-officers.edit', $officer)); ?>"
                                                            class="btn btn-xs btn-warning" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-recovery-officers')): ?>
                                                        <form action="<?php echo e(route('recovery-officers.destroy', $officer)); ?>"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Are you sure you want to delete this recovery officer?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="btn btn-xs btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="9" class="text-center" style="padding: 24px 0;">
                                                <p class="text-muted">No recovery officers found.</p>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-recovery-officers')): ?>
                                                    <a href="<?php echo e(route('recovery-officers.create')); ?>"
                                                        class="btn btn-primary btn-sm">Add First Officer</a>
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
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/recovery-officers/index.blade.php ENDPATH**/ ?>