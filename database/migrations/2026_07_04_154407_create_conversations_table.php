<?php
// database/migrations/2026_07_04_154407_create_conversations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('customer_id');
            $table->string('channel')->default('whatsapp');
            $table->string('status')->default('open');
            $table->uuid('assigned_to')->nullable();
            $table->integer('first_response_time')->nullable();
            $table->boolean('resolved_by_ai')->default(false);
            $table->timestamp('last_message_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 🔥 YEH SABHI FOREIGN KEYS HATAO
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            // $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};