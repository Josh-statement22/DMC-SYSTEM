<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('liquidation_expenses') || !Schema::hasColumn('liquidation_expenses', 'category_id')) {
            return;
        }

        DB::statement('ALTER TABLE liquidation_expenses MODIFY category_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        //
    }
};
