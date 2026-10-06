<?php
// database/migrations/2026_07_04_add_tenant_id_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('type');
            $table->timestamp('last_login_at')->nullable();
            $table->string('profile_image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tenant_id', 'last_login_at', 'profile_image']);
        });
    }
};