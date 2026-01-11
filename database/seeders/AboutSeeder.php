<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::query()->updateOrCreate(
            ['id' => 1], // Condition to find the record
            [
                'user_id' => 1, // Update or create with these values
                'title' => 'About Us',
                'description' => 'We are a company committed to delivering exceptional products and services.',
            ]
        );
    }
}
