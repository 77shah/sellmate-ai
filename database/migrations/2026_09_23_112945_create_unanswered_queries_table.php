// database/migrations/xxxx_create_unanswered_queries_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unanswered_queries', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->string('customer_number');       // WhatsApp number
            $table->string('customer_name')->nullable();
            $table->text('question');                // Customer ne kya pucha
            $table->text('ai_reply')->nullable();    // AI ne kya reply diya
            $table->enum('status', ['pending', 'resolved', 'ignored'])->default('pending');
            $table->uuid('resolved_by')->nullable();
            $table->text('owner_reply')->nullable();  // Owner ka reply
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unanswered_queries');
    }
};