// database/migrations/xxxx_create_deployments_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('version');
            $table->string('branch')->nullable();
            $table->string('commit_hash')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'running', 'success', 'failed', 'rollback'])->default('pending');
            $table->uuid('deployed_by')->nullable();
            $table->integer('duration')->default(0); // seconds
            $table->json('details')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};