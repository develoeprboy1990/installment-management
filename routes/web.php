<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RoleAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GuarantorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RecoveryOfficerController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\InstallmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityController;


Route::group(['prefix' => 'admin', 'middleware' => ['auth.redirect','role:Admin']], function () {
    // activities
    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('activities/mark-all-read', [ActivityController::class, 'markAllRead'])->name('activities.mark-all-read');
    Route::post('activities/{activity}/mark-read', [ActivityController::class, 'markRead'])->name('activities.mark-read');
    Route::post('activities/{activity}/mark-unread', [ActivityController::class, 'markUnread'])->name('activities.mark-unread');
    Route::get('/admin', [HomeController::class, 'index'])->name('admin.dashboard');
    //Dashboard
    Route::get('report', [DashboardController::class, 'report'])->name('admin.report');
    // NEW: AJAX metrics endpoint
    Route::get('report/metrics', [DashboardController::class, 'metrics'])->name('admin.report.metrics');

    //customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{customer}/statement', [CustomerController::class, 'statement'])->name('customers.statement');


    //guarantors
    Route::resource('guarantors', GuarantorController::class);
    Route::post('guarantors/check', [GuarantorController::class, 'checkGuarantor'])->name('guarantors.check');

    //products
    Route::resource('products', ProductController::class);


    // recovery-officers
    Route::resource('recovery-officers', RecoveryOfficerController::class);
    Route::put('/recovery-officers/{recoveryOfficer}', [RecoveryOfficerController::class, 'update'])->name('recoveryOfficer.update');

    //purchases
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/process-payment', [PurchaseController::class, 'processPayment'])->name('purchases.process-payment');
    Route::get('purchases/installment/{installmentId}/details', [PurchaseController::class, 'getInstallmentDetails'])->name('purchases.installment-details');
    Route::put('/installments/{id}/status', [PurchaseController::class, 'updateInstallStatus'])->name('installments.status');


    //installments
    Route::get('installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::get('installments/{installment}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
    Route::put('installments/{installment}', [InstallmentController::class, 'update'])->name('installments.update');
    Route::delete('installments/{installment}', [InstallmentController::class, 'destroy'])->name('installments.destroy');

    // Additional installment routes
    Route::get('installments/overdue', [InstallmentController::class, 'overdueReport'])->name('installments.overdue');
    Route::get('installments/officer/{officerId}', [InstallmentController::class, 'officerInstallments'])->name('installments.officer');
    Route::get('/customer/{id}/installment-info', [InstallmentController::class, 'getCustomerInstallmentInfo']);
    // ADD this new route
    Route::get('installments/{installmentId}/receipt', [PurchaseController::class, 'printReceipt'])->name('installments.receipt');



    Route::get('/customer/{id}/installment-info', [InstallmentController::class, 'getCustomerInstallmentInfo']);

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/get-list', [UserController::class, 'getUsers'])->name('users.list');
        Route::post('/update', [UserController::class, 'update'])->name('user.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [UserController::class, 'getRolesIndex'])->name('admin.roles');
        Route::post('/store', [UserController::class, 'addRole'])->name('role.store');
        Route::post('/update', [UserController::class, 'updateRole'])->name('role.update');
        Route::get('/delete/{role}', [UserController::class, 'deleteRole'])->name('role.delete');
    });

    Route::prefix('role-assignment')->group(function () {
        Route::get('/', [RoleAssignmentController::class, 'index'])->name('role-assignment');
        Route::get('/user-role', [RoleAssignmentController::class, 'getUserRoles'])->name('user-role');
        Route::post('/assign-role', [RoleAssignmentController::class, 'assignOrUpdateRole'])->name('assign-role');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
    });

    Route::get('/roles-list', [UserController::class, 'getRolesList'])->name('admin.roles-list');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'store'])->name('store.settings');
    Route::post('/settings/toggle', [SettingController::class, 'toggle'])->name('settings.toggle');

});

Route::group(['middleware' => ['role:Customer']], function () {
    Route::get('/customer', [HomeController::class, 'index'])->name('customer.dashboard');
});

Route::group(['middleware' => ['role:Admin|Customer']], function () {

});


Route::get('admin/dashboard', [DashboardController::class, 'report'])
    ->middleware(['auth.redirect', 'role:Admin'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
