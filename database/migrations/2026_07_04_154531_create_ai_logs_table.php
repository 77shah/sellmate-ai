<?php
// database/migrations/2026_07_04_000008_create_ai_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('conversation_id')->nullable();
            $table->string('intent')->nullable();
            $table->string('sentiment')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost', 10, 4)->default(0);
            $table->string('model_used')->nullable();
            $table->integer('response_time')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            //$table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('set null');
            //$table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};