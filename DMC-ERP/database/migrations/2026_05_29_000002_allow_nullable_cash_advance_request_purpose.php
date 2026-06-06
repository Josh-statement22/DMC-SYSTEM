<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite'
            || ! Schema::hasTable('cash_advance_requests')
            || ! Schema::hasColumn('cash_advance_requests', 'purpose')) {
            return;
        }

        DB::statement('ALTER TABLE `cash_advance_requests` MODIFY `purpose` TEXT NULL');
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite'
            || ! Schema::hasTable('cash_advance_requests')
            || ! Schema::hasColumn('cash_advance_requests', 'purpose')) {
            return;
        }

        DB::table('cash_advance_requests')
            ->whereNull('purpose')
            ->update(['purpose' => '']);

        DB::statement('ALTER TABLE `cash_advance_requests` MODIFY `purpose` TEXT NOT NULL');
    }
};
