<?php
// database/migrations/2026_07_12_change_category_id_to_auto_increment_v2.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pehle table ka structure check karein
        // Agar id already INT auto-increment hai to skip karein
        
        Schema::table('categories', function (Blueprint $table) {
            // Foreign key constraints hatao (agar hain to)
            // $table->dropForeign(['category_id']); 
        });

        // Direct query se ID change karein
        DB::statement('ALTER TABLE categories MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down()
    {
        // Rollback - UUID me wapas
        DB::statement('ALTER TABLE categories MODIFY id CHAR(36) NOT NULL');
    }
};