<?php
// database/migrations/xxxx_xx_xx_create_ads_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('ads_settings', function (Blueprint $table) {
            $table->id();
            $table->string('package_id')->nullable();
            $table->string('email')->nullable();
            $table->string('admob_app_id')->nullable();
            $table->enum('banner_ad', ['Demo', 'Live'])->default('Demo');
            $table->enum('interstitial_ad', ['ON', 'OFF'])->default('OFF');
            $table->string('rewarded_ad_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads_settings');
    }
}