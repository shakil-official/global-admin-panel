<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            AboutSeeder::class,
            TermAndConditionSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            FaqCategorySeeder::class,
            FaqSeeder::class,
            SliderSeeder::class,
        ]);
    }
}
