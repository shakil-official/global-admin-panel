<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    const SERVICES = [
        [
            'title' => 'Home Broadband',
            'position' => 1,
            'icon' => 'home',
            'description' => 'Experience buffer-free streaming, seamless work and gaming, with lightning-fast downloads.',
            'service' => [
                "FTTH Technology",
                "Bufferless 4K Streaming",
                "Parental Control Options"
            ],
            'section' => 'premium',
            'button_text' => 'View Packages',
            'button_url' => '/contact'
        ],

        [
            'title' => 'Corporate & SME',
            'position' => 2,
            'icon' => 'phone',
            'description' => 'Dedicated bandwidth with SLA guarantees and 24/7 business continuity support.',
            'service' => [
                "Dedicated Bandwidth",
                "Real Public IP",
                "Priority Support"
            ],
            'section' => 'premium',
            'button_text' => 'Get a Quote',
            'button_url' => '/contact'
        ],

        [
            'title' => 'GameX Solutions',
            'position' => 3,
            'icon' => 'gamepad',
            'description' => 'Optimized routing with ultra-low ping and packet loss focus for pro gamers.',
            'service' => [
                "Low Latency Routing",
                "Static IP Availability",
                "Direct Peering with Games"
            ],
            'section' => 'premium',
            'button_text' => 'See Gamer Plans',
            'button_url' => '/contact'
        ],

        [
            'title' => 'CCTV System Solutions',
            'position' => 1,
            'icon' => 'camera',
            'service' => [
                "HD & IP Camera Installation",
                "Remote Monitoring Setup",
                "Cloud Storage Integration",
                "24/7 Technical Support"
            ],
            'section' => 'specialized',
            'button_text' => 'Get a Quote',
            'button_url' => '/contact'
        ],

        [
            'title' => 'Complete Network Solutions',
            'position' => 2,
            'icon' => 'zap',
            'service' => [
                "Enterprise WiFi Setup",
                "Network Security Implementation",
                "Server & Data Center Setup",
                "Managed IT Services"
            ],
            'section' => 'specialized',
            'button_text' => 'Get a Quote',
            'button_url' => '/contact'
        ],

        [
            'title' => 'IP Telephony Service',
            'position' => 3,
            'icon' => 'phone',
            'service' => [
                "VoIP System Installation",
                "PBX Configuration",
                "Call Center Solutions",
                "Multi-Location Integration"
            ],
            'section' => 'specialized',
            'button_text' => 'Get a Quote',
            'button_url' => '/contact'
        ],

        [
            'title' => 'BDIX Connectivity',
            'position' => 1,
            'icon' => 'server',
            'section' => 'Value_added'
        ],

        [
            'title' => 'FTP Servers',
            'position' => 2,
            'icon' => 'database',
            'section' => 'Value_added'
        ],

        [
            'title' => 'Google & FB Cache',
            'position' => 3,
            'icon' => 'mic',
            'section' => 'Value_added'
        ],

        [
            'title' => 'Clear Voice',
            'position' => 4,
            'icon' => 'home',
            'section' => 'Value_added'
        ],

    ];

    public function run(): void
    {
        foreach (self::SERVICES as $service) {
            $serviceData = $service;

            if (isset($serviceData['service']) && is_array($serviceData['service'])) {
                $serviceData['service'] = json_encode($serviceData['service']);
            }

            $serviceData['user_id'] = 1;
            $serviceData['created_at'] = now();
            $serviceData['updated_at'] = now();

            DB::table('services')->updateOrInsert(
                ['title' => $serviceData['title']],
                $serviceData
            );
        }
    }
}
