<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firebase_settings', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('project_id')->nullable();
            $table->string('project_number')->nullable();
            $table->string('app_id')->nullable();
            $table->string('package_name')->nullable();
            $table->string('sender_id')->nullable();
            $table->longText('server_key')->nullable();
            $table->longText('key_pair')->nullable();
            $table->longText('json_file')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firebase_settings');
    }
};