<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = auth()->user();
        $permissions = [];
        
        if ($user) {
            $permissions = [
                'user_roles' => $user->getRoleNames(),
                'user_permissions' => $user->getAllPermissions()->pluck('name'),
                'direct_permissions' => $user->getDirectPermissions()->pluck('name'),
            ];
        }

        $allRoles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $allPermissions = \Spatie\Permission\Models\Permission::all();

        return view('test.permissions', compact('permissions', 'allRoles', 'allPermissions'));
    }
}
