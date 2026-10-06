<?php
// database/migrations/xxxx_xx_xx_add_status_to_gateway_settings.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToGatewaySettings extends Migration
{
    public function up()
    {
        Schema::table('gateway_settings', function (Blueprint $table) {
            $table->boolean('status')->default(0)->after('merchant_key');
        });
    }

    public function down()
    {
        Schema::table('gateway_settings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}