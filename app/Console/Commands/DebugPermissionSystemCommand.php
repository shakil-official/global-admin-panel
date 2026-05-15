<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DebugPermissionSystemCommand extends Command
{
    protected $signature = 'permissions:full-debug {user_id=1} {role_id?}';
    protected $description = 'Full debug of permission system';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleId = $this->argument('role_id');

        $this->info("=== FULL PERMISSION SYSTEM DEBUG ===\n");

        // 1. Check Database Tables
        $this->info("1. DATABASE TABLE COUNTS:");
        $permCount = Permission::count();
        $roleCount = Role::count();
        $rolePermCount = DB::table('role_has_permissions')->count();
        $userRoleCount = DB::table('model_has_roles')->count();

        $this->line("   Permissions: {$permCount}");
        $this->line("   Roles: {$roleCount}");
        $this->line("   Role-Permission entries: {$rolePermCount}");
        $this->line("   User-Role entries: {$userRoleCount}");

        // 2. Check User
        $user = User::find($userId);
        if (!$user) {
            $this->error("User {$userId} not found");
            return Command::FAILURE;
        }

        $this->info("\n2. USER INFO:");
        $this->line("   ID: {$user->id}");
        $this->line("   Name: {$user->name}");
        $this->line("   Email: {$user->email}");

        // 3. User's Roles
        $this->info("\n3. USER'S ROLES:");
        $userRoles = $user->roles;
        if ($userRoles->isEmpty()) {
            $this->error("   ✗ User has NO ROLES!");
        } else {
            foreach ($userRoles as $role) {
                $this->line("   ✓ {$role->name} (ID: {$role->id}, Guard: {$role->guard_name})");
            }
        }

        // 4. Check Role Permissions
        $this->info("\n4. ROLE PERMISSIONS:");
        $checkRoleId = $roleId ?: ($userRoles->first()?->id);

        if ($checkRoleId) {
            $role = Role::find($checkRoleId);
            if ($role) {
                $this->line("   Checking role: {$role->name}");
                $perms = $role->permissions;
                $this->line("   Permission count: {$perms->count()}");

                if ($perms->isNotEmpty()) {
                    foreach ($perms as $perm) {
                        $this->line("   ✓ {$perm->name} (Guard: {$perm->guard_name})");
                    }
                } else {
                    $this->error("   ✗ No permissions assigned to this role!");
                }

                // Check role_has_permissions table directly
                $this->info("\n5. ROLE_HAS_PERMISSIONS TABLE:");
                $entries = DB::table('role_has_permissions')
                    ->where('role_id', $checkRoleId)
                    ->get();

                if ($entries->isEmpty()) {
                    $this->error("   ✗ No entries in role_has_permissions for role {$checkRoleId}!");
                } else {
                    $this->info("   ✓ Found {$entries->count()} entries:");
                    foreach ($entries as $entry) {
                        $perm = Permission::find($entry->permission_id);
                        $permName = $perm ? $perm->name : 'UNKNOWN';
                        $this->line("     role_id: {$entry->role_id} -> permission_id: {$entry->permission_id} ({$permName})");
                    }
                }
            }
        }

        // 6. Test can() method
        $this->info("\n6. CAN() METHOD TESTS:");
        $testPerms = ['blog.view', 'blog.create', 'dashboard.view'];
        foreach ($testPerms as $perm) {
            $result = $user->can($perm);
            $icon = $result ? '✓' : '✗';
            $this->line("   {$icon} can('{$perm}'): " . ($result ? 'TRUE' : 'FALSE'));
        }

        // 7. Check for common issues
        $this->info("\n7. COMMON ISSUES CHECK:");

        // Check guard mismatch
        $permGuards = Permission::pluck('guard_name')->unique();
        $roleGuards = Role::pluck('guard_name')->unique();
        $this->line("   Permission guards: " . $permGuards->implode(', '));
        $this->line("   Role guards: " . $roleGuards->implode(', '));

        if ($permGuards->count() > 1 || $roleGuards->count() > 1) {
            $this->warn("   ⚠ Multiple guards detected - this can cause issues!");
        }

        // 8. Solutions
        $this->info("\n8. RECOMMENDED FIXES:");

        if ($userRoles->isEmpty()) {
            $this->line("   → Assign a role to user in Admin Panel");
        }

        if ($checkRoleId && $role && $role->permissions->isEmpty()) {
            $this->line("   → Assign permissions to role in Permission Management");
        }

        if ($rolePermCount == 0) {
            $this->line("   → role_has_permissions table is empty - assign permissions!");
        }

        $this->line("   → Clear cache: php artisan cache:clear");
        $this->line("   → User must LOG OUT and LOG BACK IN after permission changes!");

        return Command::SUCCESS;
    }
}
