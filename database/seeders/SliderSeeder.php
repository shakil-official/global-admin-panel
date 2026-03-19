<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'name' => 'Home Broadband',
                'position' => 1,
                'image' => 'assets/hero/home-broadband.jpg',
                'type' => 'web',
                'status' => 'active',
                'badge' => "Sylhet's Most Trusted ISP Since 2014",
                'headline' => 'The Best WiFi in Sylhet City',
                'description' => 'Experience reliability over speed. High-performance internet with 99.99% uptime, 24/7 support, and zero compromises.',
                'button_text' => 'View Packages',
                'button_url' => '/packages',
                'feature_pills' => json_encode([]),
            ],
            [
                'name' => 'Corporate Connectivity',
                'position' => 2,
                'image' => 'assets/hero/corporate.jpg',
                'type' => 'web',
                'status' => 'active',
                'badge' => 'Enterprise Solutions',
                'headline' => 'Corporate Connectivity That Never Sleeps',
                'description' => 'Dedicated bandwidth, guaranteed SLA, and priority support for businesses that demand excellence.',
                'button_text' => 'Enterprise Plans',
                'button_url' => '#packages',
                'feature_pills' => json_encode([]),
            ],
            [
                'name' => 'Gaming Nitro',
                'position' => 3,
                'image' => 'assets/hero/gaming.jpg',
                'type' => 'web',
                'status' => 'active',
                'badge' => 'GameX Nitro',
                'headline' => 'Ultra-Low Latency for Gamers',
                'description' => 'Optimized routing, ping protection, and zero packet loss. Dominate the battlefield with GameX Nitro.',
                'button_text' => 'Gaming Packages',
                'button_url' => '#packages',
                'feature_pills' => json_encode([]),
            ],
            [
                'name' => 'Why SyncIT',
                'position' => 4,
                'image' => 'assets/hero/why-syncit.jpg',
                'type' => 'web',
                'status' => 'active',
                'badge' => 'Why SyncIT',
                'headline' => 'Why SyncIT',
                'description' => 'Built for Sylhet with consistent fiber performance, strong local routing, and fast support — so your work, classes, and streaming stay smooth every day.',
                'button_text' => 'Why SyncIT',
                'button_url' => '#about',
                'feature_pills' => json_encode([
                    ['icon' => 'check-circle', 'label' => 'Local CDN'],
                    ['icon' => 'check-circle', 'label' => 'HD Facebook'],
                    ['icon' => 'check-circle', 'label' => 'Bufferless YouTube'],
                    ['icon' => 'check-circle', 'label' => 'Direct Connection with BDIX'],
                    ['icon' => 'check-circle', 'label' => 'Multiple Upstream'],
                    ['icon' => 'check-circle', 'label' => 'Available Public IP'],
                    ['icon' => 'check-circle', 'label' => 'Lag Free & Low Latency'],
                    ['icon' => 'check-circle', 'label' => 'Optical Fiber Connection'],
                ]),
            ],
        ];

        foreach ($slides as $slide) {
            // Ensure image exists in public/uploads/slider or copy from assets
            $destination = 'uploads/slider/' . basename($slide['image']);
            if (!File::exists(public_path($destination))) {
                if (File::exists(public_path($slide['image']))) {
                    File::copy(public_path($slide['image']), public_path($destination));
                }
            }
            $slide['image'] = $destination;
            $slide['user_id'] = Auth::id() ?? null;
            $slide['created_at'] = now();
            $slide['updated_at'] = now();
            DB::table('sliders')->insert($slide);
        }
    }
}
