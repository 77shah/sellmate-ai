// database/migrations/xxxx_create_suspicious_ips_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suspicious_ips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address')->unique();
            $table->text('reason')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('attempts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suspicious_ips');
    }
};