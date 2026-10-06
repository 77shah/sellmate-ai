<?php
// database/migrations/2026_04_30_000003_create_inner_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInnerCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inner_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inner_categories');
    }
}