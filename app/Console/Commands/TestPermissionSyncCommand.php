<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class TestPermissionSyncCommand extends Command
{
    protected $signature = 'permissions:test-sync {role_id} {permission_name}';
    protected $description = 'Test permission sync manually';

    public function handle()
    {
        $roleId = $this->argument('role_id');
        $permissionName = $this->argument('permission_name');

        $this->info("Testing permission sync...");
        $this->line("Role ID: {$roleId}");
        $this->line("Permission: {$permissionName}");

        // Find role
        $role = Role::find($roleId);
        if (!$role) {
            $this->error("Role not found!");
            return Command::FAILURE;
        }
        $this->info("Role found: {$role->name}");

        // Find or create permission
        $permission = Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web'
        ]);
        $this->info("Permission found/created: {$permission->name} (ID: {$permission->id})");

        // Check current role permissions
        $currentPerms = $role->permissions()->pluck('name')->toArray();
        $this->line("Current role permissions: " . json_encode($currentPerms));

        // Try sync with ID
        $this->line("\nTrying sync with ID...");
        try {
            $role->syncPermissions([$permission->id]);
            $this->info("✓ Sync with ID succeeded!");
        } catch (\Exception $e) {
            $this->error("✗ Sync with ID failed: " . $e->getMessage());
        }

        // Check if it worked
        $newPerms = $role->permissions()->pluck('name')->toArray();
        $this->line("New role permissions: " . json_encode($newPerms));

        // Check role_has_permissions table directly
        $this->line("\nChecking role_has_permissions table...");
        $entries = DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->get();
        
        if ($entries->isEmpty()) {
            $this->error("✗ No entries in role_has_permissions!");
        } else {
            $this->info("✓ Entries found: " . $entries->count());
            foreach ($entries as $entry) {
                $this->line("  - role_id: {$entry->role_id}, permission_id: {$entry->permission_id}");
            }
        }

        return Command::SUCCESS;
    }
}
