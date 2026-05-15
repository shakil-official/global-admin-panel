<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::create(['name' => 'viewer', 'guard_name' => 'web']);

        // Define modules and their permissions
        $modules = [
            'blog' => ['create', 'read', 'update', 'delete'],
            'category' => ['create', 'read', 'update', 'delete'],
            'subcategory' => ['create', 'read', 'update', 'delete'],
            'tag' => ['create', 'read', 'update', 'delete'],
            'branch' => ['create', 'read', 'update', 'delete'],
            'client' => ['create', 'read', 'update', 'delete'],
            'coverage' => ['create', 'read', 'update', 'delete'],
            'faq' => ['create', 'read', 'update', 'delete'],
            'faq_category' => ['create', 'read', 'update', 'delete'],
            'feedback' => ['create', 'read', 'update', 'delete'],
            'network_partner' => ['create', 'read', 'update', 'delete'],
            'offer' => ['create', 'read', 'update', 'delete'],
            'package' => ['create', 'read', 'update', 'delete'],
            'paybill' => ['create', 'read', 'update', 'delete'],
            'service' => ['create', 'read', 'update', 'delete'],
            'setting' => ['create', 'read', 'update', 'delete'],
            'slider' => ['create', 'read', 'update', 'delete'],
            'contact' => ['create', 'read', 'update', 'delete'],
            'about' => ['create', 'read', 'update', 'delete'],
            'user' => ['create', 'read', 'update', 'delete'],
            'dashboard' => ['read'],
        ];

        // Create permissions
        $permissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$module}_{$action}";
                $permissions[$permissionName] = Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Assign permissions to roles
        // Super Admin gets all permissions
        $superAdmin->givePermissionTo($permissions);

        // Admin gets most permissions except user management
        $adminPermissions = collect($permissions)->filter(function ($permission, $key) {
            return !str_starts_with($key, 'user_');
        });
        $admin->givePermissionTo($adminPermissions);

        // Editor gets create, read, update permissions for content modules
        $editorPermissions = collect($permissions)->filter(function ($permission, $key) {
            return !str_contains($key, '_delete') && 
                   !str_starts_with($key, 'user_') && 
                   !str_starts_with($key, 'setting_') &&
                   !str_starts_with($key, 'branch_') &&
                   !str_starts_with($key, 'client_') &&
                   !str_starts_with($key, 'coverage_') &&
                   !str_starts_with($key, 'network_partner_') &&
                   !str_starts_with($key, 'package_') &&
                   !str_starts_with($key, 'paybill_') &&
                   !str_starts_with($key, 'service_');
        });
        $editor->givePermissionTo($editorPermissions);

        // Viewer gets only read permissions
        $viewerPermissions = collect($permissions)->filter(function ($permission, $key) {
            return str_contains($key, '_read') || str_contains($key, '_list');
        });
        $viewer->givePermissionTo($viewerPermissions);
    }
}
