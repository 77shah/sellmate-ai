<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_refer_and_earn_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refer_and_earn', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refer_and_earn');
    }
};