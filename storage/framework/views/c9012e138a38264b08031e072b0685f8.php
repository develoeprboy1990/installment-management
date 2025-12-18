<?php $__env->startSection('content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Recovery Officer Details</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('recovery-officers.index')); ?>">Recovery Officers</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong><?php echo e($recoveryOfficer->name); ?></strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right" style="margin-top: 30px;">
            <a href="<?php echo e(route('recovery-officers.index')); ?>" class="btn btn-white btn-sm">
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
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-recovery-officers')): ?>
                                <a href="<?php echo e(route('recovery-officers.edit', $recoveryOfficer)); ?>" class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Employee ID:</th>
                                <td><code><?php echo e($recoveryOfficer->employee_id); ?></code></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td><strong><?php echo e($recoveryOfficer->name); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    <?php if($recoveryOfficer->phone): ?>
                                        <a href="tel:<?php echo e($recoveryOfficer->phone); ?>" class="text-navy">
                                            <i class="fa fa-phone"></i> <?php echo e($recoveryOfficer->phone); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    <?php if($recoveryOfficer->email): ?>
                                        <a href="mailto:<?php echo e($recoveryOfficer->email); ?>" class="text-navy">
                                            <i class="fa fa-envelope"></i> <?php echo e($recoveryOfficer->email); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?php echo e($recoveryOfficer->address ?? 'Not provided'); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="label label-<?php echo e($recoveryOfficer->is_active ? 'success' : 'danger'); ?>">
                                        <?php echo e($recoveryOfficer->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar"></i> <?php echo e($recoveryOfficer->created_at->format('d M, Y h:i A')); ?>

                                    </small>
                                </td>
                            </tr>
                            <?php if($recoveryOfficer->updated_at != $recoveryOfficer->created_at): ?>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa fa-edit"></i> <?php echo e($recoveryOfficer->updated_at->format('d M, Y h:i A')); ?>

                                        </small>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
                                            <h2 class="font-bold"><?php echo e($recoveryOfficer->getInstallmentsCount()); ?></h2>
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
                                            <h2 class="font-bold">Rs. <?php echo e(number_format($recoveryOfficer->getTotalCollected(), 2)); ?>

                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <h5 class="m-t-md"><i class="fa fa-info-circle"></i> Quick Info</h5>
                        <p class="small text-muted">
                            This recovery officer has collected <strong><?php echo e($recoveryOfficer->getInstallmentsCount()); ?></strong>
                            installments
                            with a total amount of <strong>Rs.
                                <?php echo e(number_format($recoveryOfficer->getTotalCollected(), 2)); ?></strong>.
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
                        <?php if($recoveryOfficer->installments->count() > 0): ?>
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
                                        <?php $__currentLoopData = $recoveryOfficer->installments()->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td>
                                                    <strong><?php echo e($installment->purchase->customer->name); ?></strong><br>
                                                    <small
                                                        class="text-muted"><?php echo e($installment->purchase->customer->account_no); ?></small>
                                                </td>
                                                <td><strong>Rs.
                                                        <?php echo e(number_format($installment->installment_amount, 2)); ?></strong>
                                                </td>
                                                <td><?php echo e($installment->due_date->format('d M, Y')); ?></td>
                                                <td>
                                                    <?php if($installment->paid_date): ?>
                                                        <?php echo e($installment->paid_date->format('d M, Y')); ?>

                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="label label-<?php echo e($installment->status === 'paid' ? 'success' : ($installment->status === 'pending' ? 'warning' : 'danger')); ?>">
                                                        <?php echo e(ucfirst($installment->status)); ?>

                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?php echo e(route('installments.index')); ?>" class="btn btn-xs btn-info"
                                                        title="View Installments">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($recoveryOfficer->installments->count() > 10): ?>
                                <div class="text-center m-t-md">
                                    <a href="<?php echo e(route('installments.index')); ?>" class="btn btn-white btn-sm">
                                        <i class="fa fa-list"></i> View All Installments
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center" style="padding: 40px 0;">
                                <i class="fa fa-inbox fa-3x text-muted"></i>
                                <p class="text-muted m-t-md">No installments collected yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/recovery-officers/show.blade.php ENDPATH**/ ?>