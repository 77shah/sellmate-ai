<?php
// database/migrations/2026_07_13_create_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('currency')->default('INR');
            $table->enum('billing_period', ['monthly', 'yearly'])->default('monthly');
            $table->integer('ai_messages_limit')->default(0);
            $table->integer('channels_limit')->default(1);
            $table->integer('team_seats_limit')->default(1);
            $table->integer('storage_limit')->default(100);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('razorpay_plan_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};