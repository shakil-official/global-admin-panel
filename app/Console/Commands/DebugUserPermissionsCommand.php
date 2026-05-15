<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DebugUserPermissionsCommand extends Command
{
    protected $signature = 'user:debug-permissions {user_id=1}';
    protected $description = 'Debug why a user cannot access permissions';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("User ID {$userId} not found");
            return Command::FAILURE;
        }

        $this->info("=== USER PERMISSION DEBUG ===");
        $this->line("User: {$user->name} (ID: {$user->id}, Email: {$user->email})");
        $this->line('');

        // 1. Check user's roles
        $this->info("1. USER ROLES:");
        $roles = $user->roles;
        if ($roles->isEmpty()) {
            $this->error("   ✗ User has NO ROLES assigned!");
            $this->line("   → This is the problem. Assign a role first.");
        } else {
            foreach ($roles as $role) {
                $this->line("   ✓ Role: {$role->name} (ID: {$role->id})");
            }
        }
        $this->line('');

        // 2. Check role permissions
        $this->info("2. ROLE PERMISSIONS:");
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            $this->line("   Role '{$role->name}' has " . count($rolePermissions) . " permissions:");
            if (empty($rolePermissions)) {
                $this->error("   ✗ NO PERMISSIONS assigned to this role!");
            } else {
                foreach ($rolePermissions as $perm) {
                    $this->line("   ✓ {$perm}");
                }
            }
        }
        $this->line('');

        // 3. Test specific permissions
        $this->info("3. PERMISSION TESTS:");
        $testPermissions = ['blog.view', 'blog.create', 'blog.update', 'blog.delete', 'dashboard.view'];
        foreach ($testPermissions as $perm) {
            $hasPerm = $user->can($perm);
            $icon = $hasPerm ? '✓' : '✗';
            $status = $hasPerm ? 'GRANTED' : 'DENIED';
            $this->line("   {$icon} can('{$perm}'): {$status}");
        }
        $this->line('');

        // 4. Check if permission exists in DB
        $this->info("4. DATABASE PERMISSION CHECK:");
        foreach ($testPermissions as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $icon = $exists ? '✓' : '✗';
            $this->line("   {$icon} Permission '{$perm}' exists in DB: " . ($exists ? 'YES' : 'NO'));
        }
        $this->line('');

        // 5. Solution
        $this->info("5. RECOMMENDED SOLUTION:");
        if ($roles->isEmpty()) {
            $this->line("   → Assign a role to user: php artisan user:assign-role {$user->id} admin");
        } elseif ($roles->first()->permissions->isEmpty()) {
            $this->line("   → Assign permissions to role via Admin Panel");
            $this->line("   → Or run: php artisan permissions:assign-to-role {$roles->first()->id}");
        } else {
            $this->line("   → Clear cache: php artisan cache:clear");
            $this->line("   → Clear permission cache: php artisan permission:cache-reset");
            $this->line("   → User must LOG OUT and LOG BACK IN");
        }

        return Command::SUCCESS;
    }
}
