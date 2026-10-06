// database/migrations/xxxx_create_login_attempts_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->boolean('success')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamps();
            
            $table->index('email');
            $table->index('ip_address');
            $table->index('success');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};