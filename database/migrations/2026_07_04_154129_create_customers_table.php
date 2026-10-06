<?php
// database/migrations/2026_07_04_154129_create_customers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->integer('lead_score')->default(0);
            $table->string('stage')->default('new');
            $table->string('status')->default('active');
            $table->json('tags')->nullable();
            $table->uuid('assigned_to')->nullable();  // 🔥 UUID hai
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 🔥 YEH LINES HATAO YA COMMENT OUT KARO
            // $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};