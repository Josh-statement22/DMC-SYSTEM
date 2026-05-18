<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('liquidation_expenses')->truncate();
        DB::table('liquidations')->truncate();
        DB::table('cash_advance_request_audits')->truncate();
        DB::table('cash_advance_request_attachments')->truncate();
        DB::table('cash_advance_requests')->truncate();
        DB::table('cash_advance_monthly_balances')->truncate();
        DB::table('users')->truncate();
        DB::table('particulars')->truncate();

        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('All tables truncated successfully!');
    }
}
