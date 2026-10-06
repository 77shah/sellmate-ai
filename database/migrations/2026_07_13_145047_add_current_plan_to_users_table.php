<?php
// database/migrations/2026_07_13_add_current_plan_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->string('razorpay_customer_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_plan_id']);
            $table->dropColumn(['current_plan_id', 'subscription_expires_at', 'razorpay_customer_id', 'stripe_customer_id']);
        });
    }
};