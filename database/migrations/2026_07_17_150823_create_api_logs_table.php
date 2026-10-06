// database/migrations/xxxx_create_api_logs_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('endpoint');
            $table->string('method');
            $table->uuid('tenant_id')->nullable();
            $table->integer('status_code');
            $table->integer('response_time')->default(0);
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
            
            $table->index('endpoint');
            $table->index('tenant_id');
            $table->index('created_at');
            $table->index('status_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};