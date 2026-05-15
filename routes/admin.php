<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Admin\RoleController as NewRoleController;
use App\Http\Controllers\Admin\UserRoleController;
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



Route::middleware('auth.admin')->prefix('super')->group(function () {
    Route::get('/api/my-table-data', [RoleController::class, 'getTableData'])->name('api.my-table-data');
    Route::get('/api/role-list', [RoleController::class, 'roleList'])->name('role.role-list');
    Route::post('/api/role-create', [RoleController::class, 'roleCreate'])->name('role.role-create');
    Route::delete('/api/role-delete', [RoleController::class, 'roleDelete'])->name('role.role-delete');
    Route::get('/api/modules/permissions', [RoleController::class, 'getModulesPermissions'])->name('api.modules.permissions');

    // roles create
    Route::get('roles/view', [RoleController::class, 'roleView'])->name('roles.view');
    Route::get('roles/add', [RoleController::class, 'add'])->name('roles.add');

    // permission management
    Route::get('permissions/settings', [RoleController::class, 'permissionSettings'])->name('permissions.settings');
    Route::get('permissions/audit', [RoleController::class, 'permissionAudit'])->name('permissions.audit');

    Route::get('user/assign/role/edit/{userId}', [RoleController::class, 'userAssignRoleEdit'])->name('user.assign-role-edit');
    Route::post('user/assign/role/update', [RoleController::class, 'userAssignRoleUpdate'])->name('user.assign-role-update');

    Route::get('/get-group-permissions', [RoleController::class, 'getGroupPermissions'])->name('get.group.permissions');

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

// New Permission-based Role Management Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('roles', [NewRoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [NewRoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [NewRoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [NewRoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [NewRoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [NewRoleController::class, 'destroy'])->name('roles.destroy');
    
    Route::get('users/roles', [UserRoleController::class, 'index'])->name('users.roles.index');
    Route::get('users/{user}/roles/edit', [UserRoleController::class, 'edit'])->name('users.roles.edit');
    Route::post('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');
});
