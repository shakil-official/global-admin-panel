<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CleanPermissionsCommand extends Command
{
    protected $signature = 'permissions:clean';
    protected $description = 'Clean and fix all permissions to match routes exactly';

    public function handle()
    {
        $this->info('Cleaning permissions to match routes exactly...');

        // Define exact permissions based on actual routes
        $exactPermissions = [
            // Module permissions (no export - routes don't have export)
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

        // Get current permissions
        $currentPermissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();

        // Permissions to remove (have export or don't match routes)
        $toRemove = array_diff($currentPermissions, $exactPermissions);

        // Permissions to add (missing from database)
        $toAdd = array_diff($exactPermissions, $currentPermissions);

        $this->info('Current permissions: ' . count($currentPermissions));
        $this->info('To remove: ' . count($toRemove));
        $this->info('To add: ' . count($toAdd));

        // Remove incorrect permissions
        if (!empty($toRemove)) {
            Permission::whereIn('name', $toRemove)->delete();
            $this->info('Removed ' . count($toRemove) . ' incorrect permissions');
            foreach ($toRemove as $perm) {
                $this->line('  - Removed: ' . $perm);
            }
        }

        // Add missing permissions
        if (!empty($toAdd)) {
            $insertData = [];
            foreach ($toAdd as $permission) {
                $insertData[] = [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Permission::insert($insertData);
            $this->info('Added ' . count($toAdd) . ' missing permissions');
            foreach ($toAdd as $perm) {
                $this->line('  + Added: ' . $perm);
            }
        }

        $this->info('Permissions cleaned successfully!');
        $this->info('Total permissions now: ' . Permission::where('guard_name', 'web')->count());

        return Command::SUCCESS;
    }
}
