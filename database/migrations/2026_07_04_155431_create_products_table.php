<?php
// database/migrations/2026_07_04_155431_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('stock_qty')->default(0);
            $table->string('sku')->nullable();
            $table->uuid('category_id')->nullable();
            $table->json('images')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            // 🔴 FOREIGN KEY HATAO
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            // $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};