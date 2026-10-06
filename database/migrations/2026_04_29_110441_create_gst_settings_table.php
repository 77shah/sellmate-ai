<?php
// database/migrations/2026_04_29_000000_create_gst_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGstSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('gst_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0)->comment('0=OFF, 1=ON');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gst_settings');
    }
}