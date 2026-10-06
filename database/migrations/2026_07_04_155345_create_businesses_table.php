<?php
// database/migrations/2026_07_04_000009_create_businesses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->unique()->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('business_type')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('status')->default('active');
            $table->json('settings')->nullable();
            $table->timestamps();

            //$table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};