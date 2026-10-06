<?php
// database/migrations/2026_07_13_add_plan_id_to_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->string('razorpay_subscription_id')->nullable()->after('plan_id');
            $table->string('stripe_subscription_id')->nullable()->after('razorpay_subscription_id');
            $table->boolean('auto_renew')->default(true)->after('status');
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'razorpay_subscription_id', 'stripe_subscription_id', 'auto_renew']);
        });
    }
};