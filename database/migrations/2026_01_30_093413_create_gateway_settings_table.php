<?php
// database/migrations/xxxx_xx_xx_create_gateway_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewaySettingsTable extends Migration
{
    public function up()
    {
        Schema::create('gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('payment_company')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('merchant_id')->nullable();
            $table->text('merchant_key')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gateway_settings');
    }
}