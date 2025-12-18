<!-- resources/views/backend/partials/breadcrumb.blade.php -->


<div class="row wrapper border-bottom white-bg page-heading" style="display: flex; align-items: center;">
    <div class="col-lg-6">
        <h2><?php echo e($pageTitle); ?></h2>
        <ol class="breadcrumb">
            <!-- Dashboard Breadcrumb Link -->
            <li>
                <a href="<?php echo e(route('dashboard')); ?>">Home</a>
            </li>

            <!-- Loop through provided breadcrumbs -->
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="<?php if($breadcrumb['active']): ?> active <?php endif; ?>">
                    <?php if($breadcrumb['active']): ?>
                        <strong><?php echo e($breadcrumb['title']); ?></strong>
                    <?php else: ?>
                        <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </div>
    <div class="col-lg-6 text-right">
        <!-- Action Buttons -->
        <?php if(!empty($buttons)): ?>
            <?php $__currentLoopData = $buttons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($button['modal'])): ?>
                    <!-- Modal Trigger Button -->
                    <button type="button"
                            class="btn <?php echo e($button['class'] ?? 'btn-primary'); ?> me-2"
                            data-toggle="modal"
                            data-target="<?php echo e($button['modal']); ?>">
                        <?php if(!empty($button['icon'])): ?>
                            <i class="<?php echo e($button['icon']); ?>"></i>
                        <?php endif; ?>
                        <?php echo e($button['title']); ?>

                    </button>
                <?php else: ?>
                    <!-- Regular Button -->
                    <a href="<?php echo e($button['url']); ?>" class="btn <?php echo e($button['class'] ?? 'btn-primary'); ?> me-2">
                        <?php if(!empty($button['icon'])): ?>
                            <i class="<?php echo e($button['icon']); ?>"></i>
                        <?php endif; ?>
                        <?php echo e($button['title']); ?>

                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>




<?php /**PATH /var/www/html/installment-management/resources/views/backend/partials/breadcrumb.blade.php ENDPATH**/ ?>