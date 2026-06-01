<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('accounting_budget_accounts')
            ->where('account_type', 'BUDGET_ALLOCATION_CLEARING')
            ->update([
                'reference' => 'system:budget-allocation-clearing',
                'name' => 'Budget Allocation Clearing',
                'updated_at' => now(),
            ]);

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'BUDGET_USAGE_CLEARING')
            ->update([
                'reference' => 'system:budget-usage-clearing',
                'name' => 'Budget Usage Clearing',
                'updated_at' => now(),
            ]);

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'PARENT_BUDGET')
            ->orderBy('id')
            ->get()
            ->each(function ($account) {
                DB::table('accounting_budget_accounts')->where('id', $account->id)->update([
                    'name' => str_replace('Parent Amount', 'Parent Budget', $account->name),
                    'updated_at' => now(),
                ]);
            });

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'LEGACY_RUNNING_BALANCE')
            ->orderBy('id')
            ->get()
            ->each(function ($account) {
                DB::table('accounting_budget_accounts')->where('id', $account->id)->update([
                    'name' => str_replace('Running Balance', 'Legacy Running Balance', $account->name),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        DB::table('accounting_budget_accounts')
            ->where('account_type', 'BUDGET_ALLOCATION_CLEARING')
            ->update([
                'reference' => 'system:income-clearing',
                'name' => 'Income Clearing',
                'updated_at' => now(),
            ]);

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'BUDGET_USAGE_CLEARING')
            ->update([
                'reference' => 'system:expense-clearing',
                'name' => 'Expense Clearing',
                'updated_at' => now(),
            ]);

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'PARENT_BUDGET')
            ->orderBy('id')
            ->get()
            ->each(function ($account) {
                DB::table('accounting_budget_accounts')->where('id', $account->id)->update([
                    'name' => str_replace('Parent Budget', 'Parent Amount', $account->name),
                    'updated_at' => now(),
                ]);
            });

        DB::table('accounting_budget_accounts')
            ->where('account_type', 'LEGACY_RUNNING_BALANCE')
            ->orderBy('id')
            ->get()
            ->each(function ($account) {
                DB::table('accounting_budget_accounts')->where('id', $account->id)->update([
                    'name' => str_replace('Legacy Running Balance', 'Running Balance', $account->name),
                    'updated_at' => now(),
                ]);
            });
    }
};
