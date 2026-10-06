<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'id' => (string) Str::uuid(),
            'uuid' => (string) Str::uuid(), // 🔥 ADD THIS
            'name' => 'Super Admin',
            'email' => 'superadmin@sellmate.com',
            'password' => Hash::make('123456'),
            'mobile_no' => '9876543210',
            'whatsapp_no' => '9876543210',
            'status' => 'active',
            'type' => 'SuperAdmin',
        ]);

        // Admin
        User::create([
            'id' => (string) Str::uuid(),
            'uuid' => (string) Str::uuid(), // 🔥 ADD THIS
            'name' => 'Admin User',
            'email' => 'admin@sellmate.com',
            'password' => Hash::make('123456'),
            'mobile_no' => '9876543211',
            'whatsapp_no' => '9876543211',
            'status' => 'active',
            'type' => 'Admin',
        ]);

        // Business Owner
        User::create([
            'id' => (string) Str::uuid(),
            'uuid' => (string) Str::uuid(), // 🔥 ADD THIS
            'name' => 'John Owner',
            'email' => 'owner@sellmate.com',
            'password' => Hash::make('123456'),
            'mobile_no' => '9876543212',
            'whatsapp_no' => '9876543212',
            'status' => 'active',
            'type' => 'Owner',
        ]);

        // Staff
        User::create([
            'id' => (string) Str::uuid(),
            'uuid' => (string) Str::uuid(), // 🔥 ADD THIS
            'name' => 'Staff Member',
            'email' => 'staff@sellmate.com',
            'password' => Hash::make('123456'),
            'mobile_no' => '9876543213',
            'whatsapp_no' => '9876543213',
            'status' => 'active',
            'type' => 'Staff',
        ]);

        // Sales Agent
        User::create([
            'id' => (string) Str::uuid(),
            'uuid' => (string) Str::uuid(), // 🔥 ADD THIS
            'name' => 'Sales Agent',
            'email' => 'salesagent@sellmate.com',
            'password' => Hash::make('123456'),
            'mobile_no' => '9876543214',
            'whatsapp_no' => '9876543214',
            'status' => 'active',
            'type' => 'SalesAgent',
        ]);
    }
}