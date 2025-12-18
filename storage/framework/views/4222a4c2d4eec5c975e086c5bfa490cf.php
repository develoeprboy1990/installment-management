<?php $__env->startPush('styles'); ?>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #1ab394;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $breadcrumbs = [['title' => 'Settings', 'url' => route('admin.settings'), 'active' => false]];
        $pageTitle = 'General Settings';
    ?>

    <?php echo $__env->make('backend.partials.breadcrumb', ['pageTitle' => $pageTitle, 'breadcrumbs' => $breadcrumbs], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1">General Settings</a></li>
                        <li><a data-toggle="tab" href="#tab-2">Social Settings</a></li>
                    </ul>
                    <div class="tab-content">

                        
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <form action="<?php echo e(route('store.settings')); ?>" method="POST" enctype="multipart/form-data"
                                    class="form-horizontal">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Project Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="settings[project_name]"
                                                placeholder="Add project name"
                                                value="<?php echo e(old('settings.project_name', $settings['project_name'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Project TagLine</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="settings[project_tagline]"
                                                placeholder="Add project tagline"
                                                value="<?php echo e(old('settings.project_tagline', $settings['project_tagline'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Favicon</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input type="file" name="favicon"
                                                        accept="image/png,image/x-icon,image/svg+xml,image/jpeg,image/webp"
                                                        class="form-control" />
                                                    <p class="help-block">PNG/ICO/SVG/JPG/WEBP up to 1 MB. Recommended size
                                                        64x64 or 32x32.</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?php if(!empty($settings['favicon'])): ?>
                                                        <div style="display:flex;align-items:center;gap:12px;">
                                                            <img src="<?php echo e(getUserSetting('favicon')); ?>" alt="Favicon"
                                                                style="width:32px;height:32px;object-fit:contain;border:1px solid #e5e7eb;border-radius:4px;background:#fff;" />
                                                            <code
                                                                style="background:#f7f7f7;padding:2px 6px;border-radius:4px;"><?php echo e($settings['favicon']); ?></code>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">No favicon uploaded.</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Profile Image</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input type="file" name="profile_image"
                                                        accept="image/png,image/jpg,image/jpeg,image/webp,image/gif"
                                                        class="form-control" />
                                                    <p class="help-block">PNG/JPG/JPEG/WEBP/GIF up to 2 MB. Recommended size
                                                        200x200.</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?php if(!empty($settings['profile_image'])): ?>
                                                        <div style="display:flex;align-items:center;gap:12px;">
                                                            <img src="<?php echo e(getUserSetting('profile_image')); ?>"
                                                                alt="Profile Image"
                                                                style="width:60px;height:60px;object-fit:cover;border:1px solid #e5e7eb;border-radius:50%;background:#fff;" />
                                                            <code
                                                                style="background:#f7f7f7;padding:2px 6px;border-radius:4px;"><?php echo e($settings['profile_image']); ?></code>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">No profile image uploaded.</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    
                                    

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Show Revenue</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input type="hidden" name="settings[show_total_revenue]" value="0">
                                                <input type="checkbox" name="settings[show_total_revenue]" value="1"
                                                    <?php echo e(($settings['show_total_revenue'] ?? '1') == '1' ? 'checked' : ''); ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-white" type="reset">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <form action="<?php echo e(route('store.settings')); ?>" method="POST" enctype="multipart/form-data"
                                    class="form-horizontal">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Facebook</label>
                                        <div class="col-sm-10">
                                            <input type="url" class="form-control" name="settings[facebook]"
                                                placeholder="Add Facebook URL"
                                                value="<?php echo e(old('settings.facebook', $settings['facebook'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instagram</label>
                                        <div class="col-sm-10">
                                            <input type="url" class="form-control" name="settings[instagram]"
                                                placeholder="Add Instagram URL"
                                                value="<?php echo e(old('settings.instagram', $settings['instagram'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">WhatsApp</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="settings[whatsapp]"
                                                placeholder="Add WhatsApp number"
                                                value="<?php echo e(old('settings.whatsapp', $settings['whatsapp'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">YouTube</label>
                                        <div class="col-sm-10">
                                            <input type="url" class="form-control" name="settings[youtube]"
                                                placeholder="Add YouTube URL"
                                                value="<?php echo e(old('settings.youtube', $settings['youtube'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">TikTok</label>
                                        <div class="col-sm-10">
                                            <input type="url" class="form-control" name="settings[tiktok]"
                                                placeholder="Add TikTok URL"
                                                value="<?php echo e(old('settings.tiktok', $settings['tiktok'] ?? '')); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-white" type="reset">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div> 
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on('change', '.toggle-setting', function() {
            $.post('<?php echo e(route('settings.toggle')); ?>', {
                key: $(this).data('key'),
                value: $(this).prop('checked') ? 1 : 0,
                _token: '<?php echo e(csrf_token()); ?>'
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/installment-management/resources/views/backend/settings/index.blade.php ENDPATH**/ ?>