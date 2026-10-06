<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'is_custom')) {
                $table->boolean('is_custom')->default(false);
            }
            if (!Schema::hasColumn('plans', 'requires_approval')) {
                $table->boolean('requires_approval')->default(false);
            }
            if (!Schema::hasColumn('plans', 'is_popular')) {
                $table->boolean('is_popular')->default(false);
            }
            if (!Schema::hasColumn('plans', 'badge')) {
                $table->string('badge', 50)->nullable();
            }
            if (!Schema::hasColumn('plans', 'knowledge_max_chunks')) {
                $table->integer('knowledge_max_chunks')->default(15);
            }
            if (!Schema::hasColumn('plans', 'knowledge_max_chars')) {
                $table->integer('knowledge_max_chars')->default(15000);
            }
            if (!Schema::hasColumn('plans', 'max_documents')) {
                $table->integer('max_documents')->default(2);
            }
            if (!Schema::hasColumn('plans', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'is_custom',
                'requires_approval',
                'is_popular',
                'badge',
                'knowledge_max_chunks',
                'knowledge_max_chars',
                'max_documents',
                'description',
            ]);
        });
    }
};