// database/migrations/2026_07_15_xxxxxx_fix_category_status_final.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Direct SQL
        DB::statement("ALTER TABLE categories MODIFY COLUMN status VARCHAR(50) DEFAULT 'active'");
        DB::table('categories')->update(['status' => 'active']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE categories MODIFY COLUMN status INT DEFAULT 1");
    }
};