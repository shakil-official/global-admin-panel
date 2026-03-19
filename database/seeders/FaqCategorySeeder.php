<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'General Queries',
                'slug' => Str::slug('General Queries'),
                'status' => 'active',
                'user_id' => 1,
            ],
            [
                'title' => 'Payment Method',
                'slug' => Str::slug('Payment Method'),
                'status' => 'active',
                'user_id' => 1,
            ],
            [
                'title' => 'Technical Queries',
                'slug' => Str::slug('Technical Queries'),
                'status' => 'active',
                'user_id' => 1,
            ],
            [
                'title' => 'Troubleshooting',
                'slug' => Str::slug('Troubleshooting'),
                'status' => 'active',
                'user_id' => 1,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('faq_categories')->insert([
                'title' => $category['title'],
                'status' => $category['status'],
                'user_id' => $category['user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
