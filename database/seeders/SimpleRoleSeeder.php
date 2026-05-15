<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class SimpleRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);
        $viewer = Role::create(['name' => 'viewer']);

        // Define permissions for all modules
        $modules = [
            'blog', 'category', 'subcategory', 'tag', 'branch', 'client', 'coverage',
            'faq', 'faq_category', 'feedback', 'network_partner', 'offer', 'package',
            'paybill', 'service', 'setting', 'slider', 'contact', 'about', 'user', 'dashboard'
        ];

        $actions = ['create', 'view', 'update', 'delete'];
        
        // Create permissions
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                // Skip certain actions for dashboard
                if ($module === 'dashboard' && !in_array($action, ['view'])) {
                    continue;
                }
                
                Permission::create(['name' => "{$module}.{$action}"]);
            }
        }

        // Assign permissions to roles
        $allPermissions = Permission::all();

        // Super Admin gets all permissions
        $superAdmin->givePermissionTo($allPermissions);

        // Admin gets most permissions except user management
        $adminPermissions = $allPermissions->filter(function ($permission) {
            return !str_starts_with($permission->name, 'user.');
        });
        $admin->givePermissionTo($adminPermissions);

        // Editor gets create, view, update permissions for content modules
        $editorPermissions = $allPermissions->filter(function ($permission) {
            $name = $permission->name;
            return !str_contains($name, '.delete') && 
                   !str_starts_with($name, 'user.') && 
                   !str_starts_with($name, 'setting.') &&
                   !str_starts_with($name, 'branch.') &&
                   !str_starts_with($name, 'client.') &&
                   !str_starts_with($name, 'coverage.') &&
                   !str_starts_with($name, 'network_partner.') &&
                   !str_starts_with($name, 'package.') &&
                   !str_starts_with($name, 'paybill.') &&
                   !str_starts_with($name, 'service.');
        });
        $editor->givePermissionTo($editorPermissions);

        // Viewer gets only view permissions
        $viewerPermissions = $allPermissions->filter(function ($permission) {
            return str_contains($permission->name, '.view') || str_contains($permission->name, '.list');
        });
        $viewer->givePermissionTo($viewerPermissions);

        $this->command->info('Roles and permissions created successfully!');
    }
}
