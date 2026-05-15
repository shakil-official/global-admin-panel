<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class DebugPermissionsCommand extends Command
{
    protected $signature = 'permissions:debug';
    protected $description = 'Debug permission database contents';

    public function handle()
    {
        $this->info('=== PERMISSION DATABASE DEBUG ===');
        
        // Get all permissions from database
        $permissions = Permission::all();
        
        $this->info('Total permissions in database: ' . $permissions->count());
        $this->line('');
        
        foreach ($permissions as $permission) {
            $this->line("ID: {$permission->id} | Name: {$permission->name} | Guard: {$permission->guard_name}");
        }
        
        $this->line('');
        $this->info('=== SAMPLE PERMISSION CHECKS ===');
        
        // Test specific permissions
        $testPermissions = ['blog.view', 'blog.create', 'blog.update', 'blog.delete'];
        
        foreach ($testPermissions as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $this->line("Permission '{$perm}' exists: " . ($exists ? 'YES' : 'NO'));
        }
        
        $this->line('');
        $this->info('=== USER PERMISSIONS DEBUG ===');
        
        // Get first user with permissions
        $user = \App\Models\User::with('permissions')->first();
        if ($user) {
            $this->line("User: {$user->name} (ID: {$user->id})");
            $this->line('User permissions:');
            foreach ($user->permissions as $perm) {
                $this->line("  - {$perm->name} (ID: {$perm->id})");
            }
            
            // Test can() method
            $this->line('');
            $this->line('Testing can() method:');
            foreach ($testPermissions as $perm) {
                $can = $user->can($perm);
                $this->line("  can('{$perm}'): " . ($can ? 'TRUE' : 'FALSE'));
            }
        } else {
            $this->line('No users found in database');
        }
        
        return Command::SUCCESS;
    }
}
