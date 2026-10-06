<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'knowledge_max_chunks')) {
                $table->integer('knowledge_max_chunks')->default(15)->after('storage_limit');
            }
            if (!Schema::hasColumn('plans', 'knowledge_max_chars')) {
                $table->integer('knowledge_max_chars')->default(15000)->after('knowledge_max_chunks');
            }
            if (!Schema::hasColumn('plans', 'max_documents')) {
                $table->integer('max_documents')->default(3)->after('knowledge_max_chars');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['knowledge_max_chunks', 'knowledge_max_chars', 'max_documents']);
        });
    }
};