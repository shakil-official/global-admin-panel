<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        // Disabled temporarily - config/permission.php now exists
        // $this->app->booted(function () {
        //     $this->registerPermissions();
        // });
    }

    protected function registerPermissions(): void
    {
        // Auto-register permissions for all modules based on actual route permissions
        $modules = [
            'blog' => ['blog.view', 'blog.create', 'blog.update', 'blog.delete'],
            'service' => ['service.view', 'service.create', 'service.update', 'service.delete'],
            'package' => ['package.view', 'package.create', 'package.update', 'package.delete'],
            'category' => ['category.view', 'category.create', 'category.update', 'category.delete'],
            'subcategory' => ['subcategory.view', 'subcategory.create', 'subcategory.update', 'subcategory.delete'],
            'client' => ['client.view', 'client.create', 'client.update', 'client.delete'],
            'branch' => ['branch.view', 'branch.create', 'branch.update', 'branch.delete'],
            'coverage' => ['coverage.view', 'coverage.create', 'coverage.update', 'coverage.delete'],
            'faq' => ['faq.view', 'faq.create', 'faq.update', 'faq.delete'],
            'faqcategory' => ['faqcategory.view', 'faqcategory.create', 'faqcategory.update', 'faqcategory.delete'],
            'feedback' => ['feedback.view', 'feedback.create', 'feedback.update', 'feedback.delete'],
            'networkpartner' => ['networkpartner.view', 'networkpartner.create', 'networkpartner.update', 'networkpartner.delete'],
            'offer' => ['offer.view', 'offer.create', 'offer.update', 'offer.delete'],
            'paybill' => ['paybill.view', 'paybill.create', 'paybill.update', 'paybill.delete'],
            'setting' => ['setting.view', 'setting.create', 'setting.update', 'setting.delete'],
            'slider' => ['slider.view', 'slider.create', 'slider.update', 'slider.delete'],
            'tag' => ['tag.view', 'tag.create', 'tag.update', 'tag.delete']
        ];

        $permissions = [];

        foreach ($modules as $module => $perms) {
            foreach ($perms as $permission) {
                $permissions[$permission] = ucwords(str_replace('.', ' ', $permission));
            }
        }

        // Additional system permissions
        $permissions['dashboard.view'] = 'Access Dashboard';
        $permissions['user.create'] = 'Create Users';
        $permissions['user.view'] = 'View Users';
        $permissions['user.update'] = 'Update Users';
        $permissions['user.delete'] = 'Delete Users';
        $permissions['about.update'] = 'Update About Page';
        $permissions['contact.view'] = 'View Contacts';
        $permissions['contact.update'] = 'Update Contacts';
        $permissions['contact.delete'] = 'Delete Contacts';

        // Store permissions in config for easy access
        config(['permissions.list' => $permissions]);

        // Actually insert permissions into database
        $this->insertPermissions(array_keys($permissions));
    }

    protected function insertPermissions(array $permissions): void
    {
        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ], [
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
