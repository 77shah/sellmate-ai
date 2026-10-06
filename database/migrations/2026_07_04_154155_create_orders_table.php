<?php
// database/migrations/2026_07_04_154155_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('customer_id')->nullable();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('source')->nullable();
            $table->uuid('assigned_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 🔥 YEH SABHI FOREIGN KEYS HATAO
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            // $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};