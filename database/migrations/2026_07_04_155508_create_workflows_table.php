<?php
// database/migrations/2026_07_04_000012_create_workflows_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->string('trigger');
            $table->json('conditions')->nullable();
            $table->json('actions');
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();

            //$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};