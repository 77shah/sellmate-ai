<?php
// database/migrations/2026_07_04_update_users_type_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // MySQL me ENUM modify karne ka safe tarika
        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('Admin', 'User', 'SuperAdmin', 'Owner', 'Staff', 'SalesAgent') DEFAULT 'User'");
    }

    public function down()
    {
        // Rollback - original ENUM me wapas
        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('Admin', 'User') DEFAULT 'User'");
    }
};