// database/migrations/xxxx_create_blocked_ips_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address')->unique();
            $table->text('reason')->nullable();
            $table->timestamp('blocked_until')->nullable();
            $table->boolean('permanent')->default(false);
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('blocked_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
    }
};