<?php
// database/migrations/2026_07_04_000011_create_knowledge_bases_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->string('type');
            $table->string('file_path');
            $table->string('status')->default('processing');
            $table->uuid('uploaded_by');
            $table->json('metadata')->nullable();
            $table->timestamps();

            //$table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};