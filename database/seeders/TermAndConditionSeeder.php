<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\TermsAndCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermsAndCondition::query()->updateOrCreate(
            ['id' => 1], // Condition to find the record
            [
                'description' => 'We are a company committed to delivering exceptional products and services.',
            ]
        );
    }
}
