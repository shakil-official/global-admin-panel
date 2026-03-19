<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Home Internet' => [
                [
                    'name' => '36 Mbps',
                    'speed' => '36',
                    'base_price' => 800,
                    'features' => [
                        'Up to 36Mbps (1:8 Ratio)',
                        'IPv4 Public IP',
                        'Basic BDIX FTP Servers',
                        'HD Video Calling',
                        'Standard Youtube & Facebook',
                        '5% Vat Applicable'
                    ]
                ],
                [
                    'name' => '70 Mbps',
                    'speed' => '70',
                    'base_price' => 1000,
                    'features' => [
                        'Up to 70 Mbps Internet (1:8 Ratio)',
                        'IP Addresses: Both IPv4 & IPv6 Public IP',
                        'Extra Speed on VAS',
                        'Enrich BDIX FTP Servers',
                        'Uncapped Speed in IX and Gaming servers',
                        'Smooth 4k Video Calling',
                        'Bufferless Youtube & Facebook',
                        '5% Vat Applicable'
                    ],
                    'is_popular' => true
                ],
                [
                    'name' => '80 Mbps',
                    'speed' => '80',
                    'base_price' => 1200,
                    'features' => [
                        'Up to 80 Mbps Internet (1:6 Ratio)',
                        'Multiple IPv4 & IPv6 Public IP',
                        'Priority Speed on VAS',
                        'Premium BDIX FTP Servers',
                        'Advanced Security Features',
                        'Ultra HD Video Calling',
                        'Business Grade Support',
                        '5% Vat Applicable'
                    ]
                ],
                [
                    'name' => '90 Mbps',
                    'speed' => '90',
                    'base_price' => 1500,
                    'features' => [
                        'Up to 90 Mbps Internet (1:6 Ratio)',
                        'Multiple IPv4 & IPv6 Public IP',
                        'Priority Speed on VAS',
                        'Premium BDIX FTP Servers',
                        'Enhanced Security Features',
                        'Ultra HD Video Calling',
                        'Priority Support',
                        '5% Vat Applicable'
                    ]
                ],
                [
                    'name' => '120 Mbps',
                    'speed' => '120',
                    'base_price' => 2000,
                    'features' => [
                        'Up to 120 Mbps Internet (1:4 Ratio)',
                        'Multiple IPv4 & IPv6 Public IP',
                        'Highest Priority Speed on VAS',
                        'Premium BDIX FTP Servers',
                        'Advanced Security Features',
                        'Ultra HD Video Calling',
                        'Priority Support',
                        '5% Vat Applicable'
                    ]
                ],
            ],
            'Corporate' => [
                [
                    'name' => 'Corporate Solution',
                    'speed' => 'Custom',
                    'base_price' => 0,
                    'features' => [
                        'Dedicated Bandwidth',
                        'Guaranteed SLA',
                        'Custom IP Configuration',
                        'On-site Technical Support',
                        'Flexible Contract Terms',
                        'Priority Support'
                    ]
                ]
            ],
            'SME' => [
                [
                    'name' => 'SME Package',
                    'speed' => 'Coming Soon',
                    'base_price' => 0,
                    'features' => [
                        'Plans for this category will be updated soon.',
                        'Tailored solutions for small businesses',
                        'Competitive pricing',
                        'Reliable connectivity'
                    ]
                ]
            ],
            'Freelancer' => [
                [
                    'name' => 'Freelancer Package',
                    'speed' => 'Coming Soon',
                    'base_price' => 0,
                    'features' => [
                        'Plans for this category will be updated soon.',
                        'Perfect for remote workers',
                        'High-speed connectivity',
                        'Reliable performance'
                    ]
                ]
            ],
            'GameX' => [
                [
                    'name' => 'GameX Package',
                    'speed' => 'Coming Soon',
                    'base_price' => 0,
                    'features' => [
                        'Plans for this category will be updated soon.',
                        'Ultra-low latency',
                        'Optimized for gaming',
                        'Zero packet loss'
                    ]
                ]
            ],
        ];

        foreach ($data as $category => $packages) {
            foreach ($packages as $package) {
                $basePrice = $package['base_price'] ?? 0;
                $vatPercentage = 5;
                $vatAmount = ($basePrice * $vatPercentage) / 100;
                $totalPrice = $basePrice + $vatAmount;

                DB::table('packages')->insert([
                    'title' => $package['name'],
                    'slug' => Str::slug($package['name']),
                    'category' => $category,
                    'speed' => $package['speed'] ?? null,
                    'base_price' => $basePrice,
                    'vat_enabled' => true,
                    'vat_percentage' => $vatPercentage,
                    'vat_amount' => $vatAmount,
                    'total_price' => $totalPrice,
                    'is_popular' => $package['is_popular'] ?? false,
                    'features' => json_encode($package['features'] ?? []),
                    'short_description' => null,
                    'description' => null,
                    'type' => 'upstream',
                    'status' => 'active',
                    'user_id' => 1, // যদি super admin থাকে
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
