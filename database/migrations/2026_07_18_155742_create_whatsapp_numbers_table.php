// database/migrations/xxxx_create_whatsapp_numbers_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_numbers', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT
            $table->uuid('tenant_id');
            $table->string('phone_number')->unique();
            $table->string('phone_number_id')->nullable();
            $table->string('display_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_numbers');
    }
};