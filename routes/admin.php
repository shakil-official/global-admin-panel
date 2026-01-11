<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('super.admin.logout');
});

Route::middleware('auth.admin')->prefix('super')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('super.admin.dashboard');
    Route::get('/user/view', [AdminController::class, 'userView'])->name('super.user.view');
    Route::post('/user/view', [AdminController::class, 'userStore'])->name('super-admin.user.store');
    Route::get('/user/list', [AdminController::class, 'userList'])->name('super-admin.user.list');
    Route::delete('/delete/user', [AdminController::class, 'delete'])->name('super-admin.user.delete');
    Route::get('/admin/assign', [AdminController::class, 'adminAssign'])->name('super-admin.assign');
    Route::get('admin/user/add', [AdminController::class, 'add'])->name('super-admin.user.add');
});



Route::middleware('auth')->group(function () {
    Route::get('/api/my-table-data', [RoleController::class, 'getTableData'])->name('api.my-table-data');
    Route::get('/api/role-list', [RoleController::class, 'roleList'])->name('role.role-list');
    Route::post('/api/role-create', [RoleController::class, 'roleCreate'])->name('role.role-create');
    Route::delete('/api/role-delete', [RoleController::class, 'roleDelete'])->name('role.role-delete');


    // roles create
    Route::get('roles/view', [RoleController::class, 'roleView'])->name('roles.view');

    Route::get('user/assign/role/edit/{userId}', [RoleController::class, 'userAssignRoleEdit'])->name('user.assign-role-edit');
    Route::post('user/assign/role/update', [RoleController::class, 'userAssignRoleUpdate'])->name('user.assign-role-update');

    Route::post('/get-group-permissions', [RoleController::class, 'getGroupPermissions'])->name('get.group.permissions');

    Route::prefix('roles/permission')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/list', 'permissionList')->name('role.permission-list');
            Route::get('/add', 'add')->name('permission-add');
            Route::post('/create', 'create')->name('permission-create');
            Route::post('/update', 'update')->name('permission-update');
            Route::delete('/delete', 'delete')->name('permission-delete');
            Route::post('/list', 'list')->name('permission-list');
            Route::get('/edit/{id}', 'edit')->name('permission-edit');
        });
    });
});
