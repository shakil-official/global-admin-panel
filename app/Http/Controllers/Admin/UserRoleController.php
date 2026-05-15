<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user_read')->only(['index', 'show']);
        $this->middleware('permission:user_update')->only(['edit', 'update']);
    }

    public function index(): View
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.roles', compact('users'));
    }

    public function edit(User $user): View
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.edit-roles', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.users.roles.index')
            ->with('success', 'User roles updated successfully');
    }
}
