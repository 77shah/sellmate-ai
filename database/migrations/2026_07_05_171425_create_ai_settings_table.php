<?php
// database/migrations/2026_07_05_create_ai_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('personality')->default('friendly');
            $table->string('language')->default('english');
            $table->text('business_hours')->nullable();
            $table->boolean('auto_reply_enabled')->default(false);
            $table->text('escalation_rules')->nullable();
            $table->json('custom_responses')->nullable();
            $table->timestamps();
            
            $table->unique(['tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};