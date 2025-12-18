<!-- resources/views/products/edit.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row m-b-md">
        <div class="col-sm-8">
            <h1 class="m-b-none">Edit Product</h1>
            <small class="text-muted">Update product details like company, model, serial and prices.</small>
        </div>
        <div class="col-sm-4 text-right">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Products</a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <strong><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</strong>
            <ul class="m-t-xs m-b-none">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Product Information</h5>
                </div>
                <div class="ibox-content">
                    <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" novalidate>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('company') ? 'has-error' : ''); ?>">
                                    <label for="company">Company <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company" name="company" placeholder="e.g., Samsung, LG, Haier" value="<?php echo e(old('company', $product->company)); ?>" required>
                                    <?php if($errors->has('company')): ?>
                                        <span class="help-block"><?php echo e($errors->first('company')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('model') ? 'has-error' : ''); ?>">
                                    <label for="model">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="model" name="model" placeholder="e.g., Galaxy A14" value="<?php echo e(old('model', $product->model)); ?>" required>
                                    <?php if($errors->has('model')): ?>
                                        <span class="help-block"><?php echo e($errors->first('model')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('serial_no') ? 'has-error' : ''); ?>">
                                    <label for="serial_no">Serial No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="e.g., SN-123456789" value="<?php echo e(old('serial_no', $product->serial_no)); ?>" required>
                                    <?php if($errors->has('serial_no')): ?>
                                        <span class="help-block"><?php echo e($errors->first('serial_no')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('cost_price') ? 'has-error' : ''); ?>">
                                    <label for="cost_price">Cost Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rs.</span>
                                        <input type="number" class="form-control" id="cost_price" name="cost_price" value="<?php echo e(old('cost_price', $product->cost_price)); ?>" step="0.01" min="0" required>
                                    </div>
                                    <small class="text-muted">Internal procurement or base cost.</small>
                                    <?php if($errors->has('cost_price')): ?>
                                        <span class="help-block"><?php echo e($errors->first('cost_price')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo e($errors->has('price') ? 'has-error' : ''); ?>">
                                    <label for="price">Sell Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rs.</span>
                                        <input type="number" class="form-control" id="price" name="price" value="<?php echo e(old('price', $product->price)); ?>" step="0.01" min="0" required>
                                    </div>
                                    <small class="text-muted">Displayed / invoiced price to customers.</small>
                                    <?php if($errors->has('price')): ?>
                                        <span class="help-block"><?php echo e($errors->first('price')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="m-t-sm">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Product</button>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Meta</h5>
                </div>
                <div class="ibox-content">
                    <p class="m-b-xs"><strong>Product ID:</strong> #<?php echo e($product->id); ?></p>
                    <p class="m-b-xs"><strong>Created:</strong> <?php echo e($product->created_at?->format('d/m/Y H:i')); ?></p>
                    <p class="m-b-none"><strong>Last Updated:</strong> <?php echo e($product->updated_at?->format('d/m/Y H:i')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/products/edit.blade.php ENDPATH**/ ?>