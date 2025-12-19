<!--
*
*  INSPINIA - Responsive Admin Theme
*  version 2.7
*
-->

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo e((getUserSetting('project_name') ?? config('app.name')) . ' - ' . (getUserSetting('project_tagline') ?? '')); ?>

    </title>
    <?php if(getUserSetting('favicon')): ?>
        <link rel="icon" href="<?php echo e(asset('storage/' . getUserSetting('favicon'))); ?>">
        <link rel="shortcut icon" href="<?php echo e(asset('storage/' . getUserSetting('favicon'))); ?>">
    <?php endif; ?>
    <link href="<?php echo e(asset('backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/font-awesome/css/font-awesome.css')); ?>" rel="stylesheet">
    <!-- Toastr style -->
    <link href="<?php echo e(asset('backend/css/plugins/toastr/toastr.min.css')); ?>" rel="stylesheet">
    <!-- Gritter -->
    <link href="<?php echo e(asset('backend/js/plugins/gritter/jquery.gritter.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/css/animate.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/css/plugins/dataTables/datatables.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element" style="text-align: center;">
                            <span>
                                <img alt="image" class="img-circle"
                                    src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('backend/img/profile_small.jpg')); ?>"
                                    style="width: 60px; height: 60px; border-radius: 50%;" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs"> <strong
                                            class="font-bold"><?php echo e(Auth::user()->name); ?></strong> </span> <span
                                        class="text-muted text-xs block"><?php echo e(auth()->user()->getRoleNames()->first()); ?><b
                                            class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="<?php echo e(url('profile')); ?>">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onclick="event.preventDefault(); triggerLogout();">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            +
                        </div>
                    </li>

                    <li class="<?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('dashboard')); ?>"><i class="fa fa-dashboard"></i> <span
                                class="nav-label">Dashboard</span></a>
                    </li>

                    <li class="<?php echo e(request()->is('admin/customers*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('customers.index')); ?>"><i class="fa fa-users"></i> <span
                                class="nav-label">Customers</span></a>
                    </li>

                    <li class="<?php echo e(request()->is('admin/guarantors*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('guarantors.index')); ?>"><i class="fa fa-user-plus"></i> <span
                                class="nav-label">Guarantors</span></a>
                    </li>

                    <li class="<?php echo e(request()->is('admin/products*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('products.index')); ?>"><i class="fa fa-cube"></i> <span
                                class="nav-label">Products</span></a>
                    </li>



                    <li class="<?php echo e(request()->is('admin/recovery-officers*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('recovery-officers.index')); ?>"><i class="fa fa-user-circle-o"></i> <span
                                class="nav-label">Recovery Officers</span></a>
                    </li>

                    <li class="<?php echo e(request()->is('admin/purchases*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('purchases.index')); ?>"><i class="fa fa-shopping-cart"></i> <span
                                class="nav-label">Purchases</span></a>
                    </li>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-installments')): ?>
                        <li class="<?php echo e(request()->is('admin/installments*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('installments.index')); ?>"><i class="fa fa-credit-card"></i> <span
                                    class="nav-label">Installments</span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-profile')): ?>
                        <!-- User Management section with better icons -->
                        <li class="<?php echo e(request()->is('profile') || request()->routeIs('admin.settings') ? 'active' : ''); ?>">
                            <a href="#"><i class="fa fa-user-circle"></i> <span class="nav-label">User
                                    Management</span> <span class="fa arrow"></span></a>
                            <ul
                                class="nav nav-second-level <?php echo e(request()->is('profile') || request()->routeIs('admin.settings') ? 'collapse' : ''); ?>">
                                <li class="<?php echo e(request()->is('profile') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(url('profile')); ?>"><i class="fa fa-user"></i> Profile</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.settings')); ?>"><i class="fa fa-cogs"></i> General
                                        Setting</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-expenses')): ?>
                        <li class="<?php echo e(request()->is('admin/expenses*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('expenses.index')); ?>"><i class="fa fa-money"></i> <span
                                    class="nav-label">Expenses</span></a>
                        </li>
                    <?php endif; ?>

                    <!-- Settings section with better icons -->
                    <li
                        class="<?php echo e(request()->routeIs('admin.users') || request()->routeIs('admin.roles') || request()->routeIs('role-assignment') || request()->routeIs('permissions') ? 'active' : ''); ?>">
                        <a href="#"><i class="fa fa-cog"></i> <span class="nav-label">System Settings</span>
                            <span class="fa arrow"></span></a>
                        <ul
                            class="nav nav-second-level <?php echo e(request()->routeIs('admin.users') || request()->routeIs('admin.roles') || request()->routeIs('role-assignment') || request()->routeIs('permissions') ? 'collapse' : ''); ?>">
                            <li class="<?php echo e(request()->routeIs('admin.users') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.users')); ?>"><i class="fa fa-users"></i> Users</a>
                            </li>
                            <li class="<?php echo e(request()->routeIs('admin.roles') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('admin.roles')); ?>"><i class="fa fa-shield"></i> Roles</a>
                            </li>
                            <li class="<?php echo e(request()->routeIs('role-assignment') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('role-assignment')); ?>"><i class="fa fa-user-secret"></i> Role
                                    Assignments</a>
                            </li>
                            <li class="<?php echo e(request()->routeIs('permissions') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('permissions')); ?>"><i class="fa fa-lock"></i> Permissions</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" onclick="event.preventDefault(); triggerLogout();"><i
                                class="fa fa-sign-out"></i> <span class="nav-label">Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">

            <div class="row border-bottom">
                <nav class="navbar navbar-static-top " role="navigation" style="margin-bottom: 0;">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i
                                class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." class="form-control"
                                    name="top-search" id="top-search" />
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome <?php echo e(Auth::user()->name); ?></span>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>
                                <span class="label label-primary"><?php echo e($activityUnreadCount ?? 0); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts" style="margin-left: 0px;">
                                <?php $__empty_1 = true; $__currentLoopData = ($latestActivities ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li>
                                        <a href="<?php echo e(route('activities.index')); ?>" class="clearfix">
                                            <div>
                                                <i class="fa fa-info-circle fa-fw"></i>
                                                <?php echo e($activity->message); ?>

                                                <span
                                                    class="pull-right text-muted small"><?php echo e($activity->created_at->diffForHumans()); ?></span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li>
                                        <div class="text-center p-xs">
                                            <em>No recent activities</em>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="<?php echo e(route('activities.index')); ?>">
                                            <strong>See All Activities</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" onclick="event.preventDefault(); triggerLogout();"> <i
                                    class="fa fa-sign-out"></i> Log out </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>

            <?php echo $__env->yieldContent('content'); ?>

            <div class="footer">
                <div class="pull-right"></div>
                <div><strong>Copyright</strong> <?php echo e(getUserSetting('project_name') ?? config('app.name')); ?> &copy;
                    <?php echo e(date('Y')); ?></div>
            </div>

        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: none;" class="sidebarlogout">
            <?php echo csrf_field(); ?>
        </form>

    </div>



    <!-- jQuery (required for toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Mainly scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(asset('backend/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/plugins/metisMenu/jquery.metisMenu.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/plugins/slimscroll/jquery.slimscroll.min.js')); ?>"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('backend/js/plugins/chartJs/Chart.min.js')); ?>"></script>

    <!-- Rest of your scripts -->
    <script src="<?php echo e(asset('backend/js/inspinia.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/plugins/pace/pace.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/plugins/toastr/toastr.min.js')); ?>"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Debug: Check if jQuery is loaded properly
            console.log('jQuery version:', $.fn.jquery);

            var d1 = [
                [1262304000000, 6],
                [1264982400000, 3057],
                [1267401600000, 20434],
                [1270080000000, 31982],
                [1272672000000, 26602],
                [1275350400000, 27826],
                [1277942400000, 24302],
                [1280620800000, 24237],
                [1283299200000, 21004],
                [1285891200000, 12144],
                [1288569600000, 10577],
                [1291161600000, 10295]
            ];
            var d2 = [
                [1262304000000, 5],
                [1264982400000, 200],
                [1267401600000, 1605],
                [1270080000000, 6129],
                [1272672000000, 11643],
                [1275350400000, 19055],
                [1277942400000, 30062],
                [1280620800000, 39197],
                [1283299200000, 37000],
                [1285891200000, 27000],
                [1288569600000, 21000],
                [1291161600000, 17000]
            ];

            var data1 = [{
                    label: "Data 1",
                    data: d1,
                    color: '#17a084'
                },
                {
                    label: "Data 2",
                    data: d2,
                    color: '#127e68'
                }
            ];

            // Only plot if the element exists
            if ($("#flot-chart1").length) {
                $.plot($("#flot-chart1"), data1, {
                    xaxis: {
                        tickDecimals: 0
                    },
                    series: {
                        lines: {
                            show: true,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 1
                                }, {
                                    opacity: 1
                                }]
                            },
                        },
                        points: {
                            width: 0.1,
                            show: false
                        },
                    },
                    grid: {
                        show: false,
                        borderWidth: 0
                    },
                    legend: {
                        show: false,
                    }
                });
            }

            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [48, 48, 60, 39, 56, 37, 30]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 40, 51, 36, 25, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };

            // Only create chart if the element exists
            if (document.getElementById("lineChart")) {
                var ctx = document.getElementById("lineChart").getContext("2d");
                new Chart(ctx, {
                    type: 'line',
                    data: lineData,
                    options: lineOptions
                });
            }
        });
    </script>

    <script>
        //logout
        function triggerLogout() {
            const logoutForm = document.querySelector('.sidebarlogout');
            if (logoutForm) {
                logoutForm.submit();
            } else {
                console.error('Logout form not found!');
            }
        }
    </script>
    <script>
        <?php if(session('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>");
        <?php endif; ?>

        <?php if(session('error')): ?>
            toastr.error("<?php echo e(session('error')); ?>");
        <?php endif; ?>

        <?php if(session('warning')): ?>
            toastr.warning("<?php echo e(session('warning')); ?>");
        <?php endif; ?>

        <?php if(session('info')): ?>
            toastr.info("<?php echo e(session('info')); ?>");
        <?php endif; ?>
    </script>
    <?php echo $__env->yieldPushContent('script'); ?>
</body>

</html>
<?php /**PATH /home/u136558562/domains/installment.mcqsmind.com/installment/resources/views/layouts/master.blade.php ENDPATH**/ ?>