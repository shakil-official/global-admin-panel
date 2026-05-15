<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ResetPermissionsCommand extends Command
{
    protected $signature = 'permissions:reset';
    protected $description = 'Completely reset and recreate permissions system';

    public function handle()
    {
        $this->info('=== COMPLETE PERMISSION RESET ===');
        
        // Step 1: Clear all permission-related tables
        $this->line('Step 1: Clearing permission tables...');
        
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        Permission::query()->delete();
        
        $this->info('✓ Cleared all permission tables');
        
        // Step 2: Define exact permissions based on routes
        $this->line('Step 2: Creating exact permissions...');
        
        $permissions = [
            // Module permissions (exactly as in routes)
            'blog.view', 'blog.create', 'blog.update', 'blog.delete',
            'service.view', 'service.create', 'service.update', 'service.delete',
            'package.view', 'package.create', 'package.update', 'package.delete',
            'category.view', 'category.create', 'category.update', 'category.delete',
            'subcategory.view', 'subcategory.create', 'subcategory.update', 'subcategory.delete',
            'client.view', 'client.create', 'client.update', 'client.delete',
            'branch.view', 'branch.create', 'branch.update', 'branch.delete',
            'coverage.view', 'coverage.create', 'coverage.update', 'coverage.delete',
            'faq.view', 'faq.create', 'faq.update', 'faq.delete',
            'faqcategory.view', 'faqcategory.create', 'faqcategory.update', 'faqcategory.delete',
            'feedback.view', 'feedback.create', 'feedback.update', 'feedback.delete',
            'networkpartner.view', 'networkpartner.create', 'networkpartner.update', 'networkpartner.delete',
            'offer.view', 'offer.create', 'offer.update', 'offer.delete',
            'paybill.view', 'paybill.create', 'paybill.update', 'paybill.delete',
            'setting.view', 'setting.create', 'setting.update', 'setting.delete',
            'slider.view', 'slider.create', 'slider.update', 'slider.delete',
            'tag.view', 'tag.create', 'tag.update', 'tag.delete',
            
            // System permissions
            'dashboard.view',
            'user.create', 'user.view', 'user.update', 'user.delete',
            'about.update',
            'contact.view', 'contact.update', 'contact.delete'
        ];
        
        // Step 3: Insert permissions
        $this->line('Step 3: Inserting permissions...');
        
        $insertData = [];
        foreach ($permissions as $permission) {
            $insertData[] = [
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        Permission::insert($insertData);
        
        $this->info('✓ Inserted ' . count($insertData) . ' permissions');
        
        // Step 4: Verify
        $this->line('Step 4: Verification...');
        
        $totalPermissions = Permission::where('guard_name', 'web')->count();
        $this->info("✓ Total permissions in database: {$totalPermissions}");
        
        // Test a few permissions
        $testPerms = ['blog.view', 'service.create', 'faqcategory.delete'];
        foreach ($testPerms as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $this->line("✓ Permission '{$perm}' exists: " . ($exists ? 'YES' : 'NO'));
        }
        
        $this->line('');
        $this->info('=== RESET COMPLETE ===');
        $this->info('All permissions have been reset to match routes exactly!');
        $this->line('');
        $this->info('Next steps:');
        $this->line('1. Clear browser cache');
        $this->line('2. Refresh the page');
        $this->line('3. Assign permissions to roles');
        
        return Command::SUCCESS;
    }
}
