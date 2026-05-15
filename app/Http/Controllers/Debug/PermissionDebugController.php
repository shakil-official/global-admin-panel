<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionDebugController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return view('debug.permissions', [
                'error' => 'No authenticated user found',
                'user' => null,
                'roles' => [],
                'permissions' => [],
                'can_blog_create' => false,
                'all_permissions' => []
            ]);
        }

        // Get user roles and permissions
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');
        $canBlogCreate = $user->can('blog_create');
        $allPermissions = \Spatie\Permission\Models\Permission::all()->pluck('name');

        return view('debug.permissions', compact(
            'user', 'roles', 'permissions', 'canBlogCreate', 'allPermissions'
        ));
    }

    public function testPermission($permission): View
    {
        $user = auth()->user();
        $hasPermission = $user ? $user->can($permission) : false;

        return view('debug.test-permission', compact(
            'permission', 'user', 'hasPermission'
        ));
    }
}
