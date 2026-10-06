<?php
// database/migrations/2026_07_04_000007_create_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('plan_name');
            $table->string('plan_type')->default('monthly');
            $table->string('status')->default('active');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->json('features')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            //$table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};