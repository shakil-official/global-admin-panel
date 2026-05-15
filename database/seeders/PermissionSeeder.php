<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing permissions
        Permission::query()->delete();

        // Define exact permissions based on actual database structure
        $permissions = [
            // Blog
            ['name' => 'blog.view', 'guard_name' => 'web'],
            ['name' => 'blog.create', 'guard_name' => 'web'],
            ['name' => 'blog.update', 'guard_name' => 'web'],
            ['name' => 'blog.delete', 'guard_name' => 'web'],
            
            // Service
            ['name' => 'service.view', 'guard_name' => 'web'],
            ['name' => 'service.create', 'guard_name' => 'web'],
            ['name' => 'service.update', 'guard_name' => 'web'],
            ['name' => 'service.delete', 'guard_name' => 'web'],
            
            // Package
            ['name' => 'package.view', 'guard_name' => 'web'],
            ['name' => 'package.create', 'guard_name' => 'web'],
            ['name' => 'package.update', 'guard_name' => 'web'],
            ['name' => 'package.delete', 'guard_name' => 'web'],
            
            // Category
            ['name' => 'category.view', 'guard_name' => 'web'],
            ['name' => 'category.create', 'guard_name' => 'web'],
            ['name' => 'category.update', 'guard_name' => 'web'],
            ['name' => 'category.delete', 'guard_name' => 'web'],
            
            // SubCategory
            ['name' => 'subcategory.view', 'guard_name' => 'web'],
            ['name' => 'subcategory.create', 'guard_name' => 'web'],
            ['name' => 'subcategory.update', 'guard_name' => 'web'],
            ['name' => 'subcategory.delete', 'guard_name' => 'web'],
            
            // Client
            ['name' => 'client.view', 'guard_name' => 'web'],
            ['name' => 'client.create', 'guard_name' => 'web'],
            ['name' => 'client.update', 'guard_name' => 'web'],
            ['name' => 'client.delete', 'guard_name' => 'web'],
            
            // Branch
            ['name' => 'branch.view', 'guard_name' => 'web'],
            ['name' => 'branch.create', 'guard_name' => 'web'],
            ['name' => 'branch.update', 'guard_name' => 'web'],
            ['name' => 'branch.delete', 'guard_name' => 'web'],
            
            // Coverage
            ['name' => 'coverage.view', 'guard_name' => 'web'],
            ['name' => 'coverage.create', 'guard_name' => 'web'],
            ['name' => 'coverage.update', 'guard_name' => 'web'],
            ['name' => 'coverage.delete', 'guard_name' => 'web'],
            
            // Faq
            ['name' => 'faq.view', 'guard_name' => 'web'],
            ['name' => 'faq.create', 'guard_name' => 'web'],
            ['name' => 'faq.update', 'guard_name' => 'web'],
            ['name' => 'faq.delete', 'guard_name' => 'web'],
            
            // FaqCategory
            ['name' => 'faqcategory.view', 'guard_name' => 'web'],
            ['name' => 'faqcategory.create', 'guard_name' => 'web'],
            ['name' => 'faqcategory.update', 'guard_name' => 'web'],
            ['name' => 'faqcategory.delete', 'guard_name' => 'web'],
            
            // Feedback
            ['name' => 'feedback.view', 'guard_name' => 'web'],
            ['name' => 'feedback.create', 'guard_name' => 'web'],
            ['name' => 'feedback.update', 'guard_name' => 'web'],
            ['name' => 'feedback.delete', 'guard_name' => 'web'],
            
            // NetworkPartner
            ['name' => 'networkpartner.view', 'guard_name' => 'web'],
            ['name' => 'networkpartner.create', 'guard_name' => 'web'],
            ['name' => 'networkpartner.update', 'guard_name' => 'web'],
            ['name' => 'networkpartner.delete', 'guard_name' => 'web'],
            
            // Offer
            ['name' => 'offer.view', 'guard_name' => 'web'],
            ['name' => 'offer.create', 'guard_name' => 'web'],
            ['name' => 'offer.update', 'guard_name' => 'web'],
            ['name' => 'offer.delete', 'guard_name' => 'web'],
            
            // Paybill
            ['name' => 'paybill.view', 'guard_name' => 'web'],
            ['name' => 'paybill.create', 'guard_name' => 'web'],
            ['name' => 'paybill.update', 'guard_name' => 'web'],
            ['name' => 'paybill.delete', 'guard_name' => 'web'],
            
            // Setting
            ['name' => 'setting.view', 'guard_name' => 'web'],
            ['name' => 'setting.create', 'guard_name' => 'web'],
            ['name' => 'setting.update', 'guard_name' => 'web'],
            ['name' => 'setting.delete', 'guard_name' => 'web'],
            
            // Slider
            ['name' => 'slider.view', 'guard_name' => 'web'],
            ['name' => 'slider.create', 'guard_name' => 'web'],
            ['name' => 'slider.update', 'guard_name' => 'web'],
            ['name' => 'slider.delete', 'guard_name' => 'web'],
            
            // Tag
            ['name' => 'tag.view', 'guard_name' => 'web'],
            ['name' => 'tag.create', 'guard_name' => 'web'],
            ['name' => 'tag.update', 'guard_name' => 'web'],
            ['name' => 'tag.delete', 'guard_name' => 'web'],
            
            // System permissions
            ['name' => 'dashboard.view', 'guard_name' => 'web'],
            ['name' => 'user.create', 'guard_name' => 'web'],
            ['name' => 'user.view', 'guard_name' => 'web'],
            ['name' => 'user.update', 'guard_name' => 'web'],
            ['name' => 'user.delete', 'guard_name' => 'web'],
            ['name' => 'about.update', 'guard_name' => 'web'],
            ['name' => 'contact.view', 'guard_name' => 'web'],
            ['name' => 'contact.update', 'guard_name' => 'web'],
            ['name' => 'contact.delete', 'guard_name' => 'web']
        ];

        // Insert permissions with timestamps
        foreach ($permissions as &$permission) {
            $permission['created_at'] = now();
            $permission['updated_at'] = now();
        }

        Permission::insert($permissions);
    }
}
