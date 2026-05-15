<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user_read')->only(['index', 'show']);
        $this->middleware('permission:user_create')->only(['create', 'store']);
        $this->middleware('permission:user_update')->only(['edit', 'update']);
        $this->middleware('permission:user_delete')->only(['destroy']);
    }

    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = config('permissions.list', []);
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit(Role $role): View
    {
        $permissions = config('permissions.list', []);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role->update(['name' => $request->name]);
        
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
