<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unanswered_queries', function (Blueprint $table) {
            if (!Schema::hasColumn('unanswered_queries', 'owner_reply_images')) {
                $table->json('owner_reply_images')->nullable()->after('owner_reply');
            }
            if (!Schema::hasColumn('unanswered_queries', 'owner_reply_url')) {
                $table->string('owner_reply_url', 500)->nullable()->after('owner_reply_images');
            }
            if (!Schema::hasColumn('unanswered_queries', 'reply_sent_at')) {
                $table->timestamp('reply_sent_at')->nullable()->after('resolved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unanswered_queries', function (Blueprint $table) {
            $table->dropColumn([
                'owner_reply_images',
                'owner_reply_url',
                'reply_sent_at',
            ]);
        });
    }
};