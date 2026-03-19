<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $categories = DB::table('faq_categories')->pluck('title', 'id')->toArray();
        
        // Map category titles to IDs
        $categoryMap = [
            'General Queries' => array_search('General Queries', $categories),
            'Payment Method' => array_search('Payment Method', $categories),
            'Technical Queries' => array_search('Technical Queries', $categories),
            'Troubleshooting' => array_search('Troubleshooting', $categories),
        ];

        $faqs = [
            // General Queries
            [
                'category' => 'General Queries',
                'items' => [
                    [
                        'id' => 'gen-1',
                        'question' => 'What areas does SyncIT cover in Sylhet?',
                        'answer' => 'SyncIT provides high-speed fiber optic internet across major areas of Sylhet including Zindabazar, Ambarkhana, Upashahar, Shahjalal Uposhohor, Tilagarh, and surrounding neighborhoods. Check our Coverage page or contact us to verify service availability in your specific location.'
                    ],
                    [
                        'id' => 'gen-2',
                        'question' => 'How long does installation take?',
                        'answer' => 'Standard installation typically takes 2-4 hours and can usually be scheduled within 24-48 hours of your application approval. Our technicians will visit your premises, install the necessary equipment, configure your router, and ensure everything is working perfectly before they leave.'
                    ],
                    [
                        'id' => 'gen-3',
                        'question' => 'What is the minimum contract period?',
                        'answer' => 'We offer flexible plans with no long-term contracts. You can choose monthly billing with no commitment, or opt for quarterly/annual plans with discounted rates. There are no hidden fees or penalties for cancellation with proper notice.'
                    ],
                    [
                        'id' => 'gen-4',
                        'question' => 'Do you provide the router and equipment?',
                        'answer' => 'Yes, all necessary equipment including a modern dual-band WiFi router is provided as part of your package. The router remains our property during your subscription. If you prefer to use your own router, that option is also available.'
                    ]
                ]
            ],
            // Payment Method
            [
                'category' => 'Payment Method',
                'items' => [
                    [
                        'id' => 'pay-1',
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept multiple payment methods for your convenience: bKash, Nagad, Rocket, bank transfers, cash payment at our office, and online banking. Automatic recurring payments can be set up through mobile financial services for hassle-free monthly billing.'
                    ],
                    [
                        'id' => 'pay-2',
                        'question' => 'When is the bill due each month?',
                        'answer' => 'Bills are generated on the 1st of each month and payment is due within the first 10 days. You will receive reminders via SMS and email. Late payments may result in temporary service suspension after the grace period. Advance payment discounts are available for quarterly and annual plans.'
                    ],
                    [
                        'id' => 'pay-3',
                        'question' => 'Are there any hidden charges or fees?',
                        'answer' => 'No, we believe in transparent pricing. The price you see on our packages page is what you pay monthly. Installation fees (if applicable) and VAT charges are clearly mentioned upfront. There are no surprise charges or hidden costs.'
                    ],
                    [
                        'id' => 'pay-4',
                        'question' => 'Can I get a refund if I cancel mid-month?',
                        'answer' => 'We operate on a prepaid monthly billing system. If you cancel mid-month, the service will remain active until the end of your paid period. Refunds for unused portions of the month are not provided, but you can use the service until your subscription expires.'
                    ]
                ]
            ],
            // Technical Queries
            [
                'category' => 'Technical Queries',
                'items' => [
                    [
                        'id' => 'tech-1',
                        'question' => 'What is the difference between BDIX and regular internet?',
                        'answer' => 'BDIX (Bangladesh Internet Exchange) provides ultra-fast access to local Bangladeshi content and services. While your regular internet speed might be 50 Mbps, BDIX content can be accessed at much higher speeds (often 100+ Mbps) because the data doesn\'t route through international gateways. This includes local streaming services, university portals, and Bangladeshi websites.'
                    ],
                    [
                        'id' => 'tech-2',
                        'question' => 'Can I upgrade or downgrade my package?',
                        'answer' => 'Yes, you can change your package at any time. Upgrades take effect immediately upon payment of the price difference. For downgrades, the change will apply from your next billing cycle. Contact our support team to process package changes seamlessly.'
                    ],
                    [
                        'id' => 'tech-3',
                        'question' => 'Is there a data limit or FUP policy?',
                        'answer' => 'All our packages come with truly unlimited data usage. There are no Fair Usage Policies (FUP) or data caps. You can download, stream, and browse as much as you want without any throttling or overage charges.'
                    ],
                    [
                        'id' => 'tech-4',
                        'question' => 'What is your uptime guarantee?',
                        'answer' => 'We maintain a 99.5% uptime guarantee through our robust fiber optic infrastructure and redundant connectivity. Scheduled maintenance is announced in advance. In case of any service interruption, our technical team works 24/7 to restore connectivity as quickly as possible.'
                    ]
                ]
            ],
            // Troubleshooting
            [
                'category' => 'Troubleshooting',
                'items' => [
                    [
                        'id' => 'trouble-1',
                        'question' => 'My internet is slow. What should I do?',
                        'answer' => 'First, restart your router by unplugging it for 30 seconds. Check if multiple devices are using bandwidth simultaneously. Try connecting via ethernet cable to rule out WiFi issues. Run a speed test at speedtest.syncit.com.bd. If the problem persists, contact our 24/7 support at 09638559900.'
                    ],
                    [
                        'id' => 'trouble-2',
                        'question' => 'I cannot connect to WiFi. How do I fix this?',
                        'answer' => 'Ensure WiFi is enabled on your device. Check if you are entering the correct password (case-sensitive). Try forgetting the network and reconnecting. Restart your device and router. If using 5GHz, try switching to 2.4GHz band. Contact support if the issue continues.'
                    ],
                    [
                        'id' => 'trouble-3',
                        'question' => 'The lights on my router are not normal. What do they mean?',
                        'answer' => 'Red/Orange Power light: Power issue or router malfunction. No Internet light: Connection issue with our network. Blinking WiFi light: Normal, indicates wireless activity. Solid WiFi light: WiFi is on but no devices connected. If you see unusual patterns, unplug the router for 1 minute, then restart. Call support if lights remain abnormal.'
                    ],
                    [
                        'id' => 'trouble-4',
                        'question' => 'How do I report a service outage?',
                        'answer' => 'Report outages through: (1) Call our hotline: 09638559900 or 01965300500, (2) WhatsApp: 01978169689, (3) Email: support@syncit.com.bd, (4) Self-care portal (if accessible). Provide your account number and describe the issue. We will investigate and update you on the resolution timeline.'
                    ]
                ]
            ]
        ];

        foreach ($faqs as $categoryData) {
            $categoryId = $categoryMap[$categoryData['category']] ?? null;
            
            if ($categoryId) {
                foreach ($categoryData['items'] as $item) {
                    DB::table('faqs')->insert([
                        'title' => $item['question'],
                        'description' => $item['answer'],
                        'status' => 'active',
                        'faq_category_id' => $categoryId,
                        'user_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
