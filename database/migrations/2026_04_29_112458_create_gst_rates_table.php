<?php
// database/migrations/2026_04_29_000000_create_gst_rates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGstRatesTable extends Migration
{
    public function up()
    {
        Schema::create('gst_rates', function (Blueprint $table) {
            $table->id();
            $table->string('rate')->comment('GST rate like 5%, 12%, 18%, 28%');
            $table->boolean('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gst_rates');
    }
}