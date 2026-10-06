<?php
// database/seeders/PlanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'currency' => 'INR',
                'billing_period' => 'monthly',
                'ai_messages_limit' => 200,
                'channels_limit' => 1,
                'team_seats_limit' => 1,
                'storage_limit' => 100,
                'features' => ['WhatsApp Only', 'Basic Support'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 499,
                'currency' => 'INR',
                'billing_period' => 'monthly',
                'ai_messages_limit' => 2000,
                'channels_limit' => 2,
                'team_seats_limit' => 2,
                'storage_limit' => 1024,
                'features' => ['WhatsApp + Website', 'Email Support', 'AI Auto Reply'],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 999,
                'currency' => 'INR',
                'billing_period' => 'monthly',
                'ai_messages_limit' => 10000,
                'channels_limit' => 4,
                'team_seats_limit' => 5,
                'storage_limit' => 5120,
                'features' => ['All Channels (WhatsApp, IG, FB, Web)', 'Priority Support', 'Analytics'],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'price' => 1999,
                'currency' => 'INR',
                'billing_period' => 'monthly',
                'ai_messages_limit' => 50000,
                'channels_limit' => 10,
                'team_seats_limit' => 15,
                'storage_limit' => 20480,
                'features' => ['All Channels', 'Dedicated Support', 'Custom AI Training', 'API Access'],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 0,
                'currency' => 'INR',
                'billing_period' => 'monthly',
                'ai_messages_limit' => 999999,
                'channels_limit' => 999,
                'team_seats_limit' => 999,
                'storage_limit' => 999999,
                'features' => ['Unlimited Everything', '24/7 Dedicated Support', 'Custom Development', 'White Label'],
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}