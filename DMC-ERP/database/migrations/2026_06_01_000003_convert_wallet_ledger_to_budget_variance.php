<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('accounting_wallet_accounts')) {
            Schema::rename('accounting_wallet_accounts', 'accounting_budget_accounts');
        }
        if (Schema::hasTable('accounting_journal_entries')) {
            Schema::rename('accounting_journal_entries', 'accounting_budget_journal_entries');
        }
        if (Schema::hasTable('accounting_journal_lines')) {
            Schema::rename('accounting_journal_lines', 'accounting_budget_journal_lines');
        }
        if (Schema::hasTable('accounting_wallet_funding_sources')) {
            Schema::rename('accounting_wallet_funding_sources', 'accounting_legacy_wallet_funding_sources');
        }

        Schema::table('accounting_budget_journal_entries', function (Blueprint $table) {
            $table->string('transaction_type', 40)->change();
        });

        $legacyTransfers = DB::table('accounting_budget_journal_entries')
            ->where('transaction_type', 'TRANSFER')
            ->orderBy('id')
            ->get();

        foreach ($legacyTransfers as $legacyTransfer) {
            $reference = 'budget-variance-reversal:' . $legacyTransfer->id;
            if (DB::table('accounting_budget_journal_entries')->where('reference_no', $reference)->exists()) {
                continue;
            }

            $reversalId = DB::table('accounting_budget_journal_entries')->insertGetId([
                'reference_no' => $reference,
                'transaction_type' => 'LEGACY_TRANSFER_REVERSAL',
                'cash_advance_request_id' => $legacyTransfer->cash_advance_request_id,
                'liquidation_expense_id' => null,
                'description' => 'Reversal of legacy automatic transfer. Budget overspending is a variance, not a cash movement.',
                'created_by' => $legacyTransfer->created_by,
                'meta' => json_encode([
                    'reverses_journal_entry_id' => $legacyTransfer->id,
                    'reason' => 'Separate actual cash from budget variance',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $legacyLines = DB::table('accounting_budget_journal_lines')
                ->where('journal_entry_id', $legacyTransfer->id)
                ->get();

            foreach ($legacyLines as $legacyLine) {
                DB::table('accounting_budget_journal_lines')->insert([
                    'journal_entry_id' => $reversalId,
                    'account_id' => $legacyLine->account_id,
                    'amount' => -round((float) $legacyLine->amount, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('accounting_budget_accounts')->where('account_type', 'PARENT_AMOUNT')->update([
            'account_type' => 'PARENT_BUDGET',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'INCOME_CLEARING')->update([
            'account_type' => 'BUDGET_ALLOCATION_CLEARING',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'EXPENSE_CLEARING')->update([
            'account_type' => 'BUDGET_USAGE_CLEARING',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'RUNNING_BALANCE')->update([
            'account_type' => 'LEGACY_RUNNING_BALANCE',
        ]);

        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'INCOME')->update([
            'transaction_type' => 'BUDGET_ALLOCATION',
            'description' => 'Parent Budget initialized',
        ]);
        DB::table('accounting_budget_journal_entries')
            ->where('transaction_type', 'BUDGET_ALLOCATION')
            ->orderBy('id')
            ->get()
            ->each(function ($entry) {
                DB::table('accounting_budget_journal_entries')->where('id', $entry->id)->update([
                    'reference_no' => 'cash-advance-request:' . $entry->cash_advance_request_id . ':budget-allocation',
                ]);
            });
        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'EXPENSE')->update([
            'transaction_type' => 'BUDGET_USAGE',
            'description' => 'Budget usage recorded',
        ]);
        DB::table('accounting_budget_journal_entries')
            ->where('transaction_type', 'BUDGET_USAGE')
            ->whereNotNull('liquidation_expense_id')
            ->orderBy('id')
            ->get()
            ->each(function ($entry) {
                DB::table('accounting_budget_journal_entries')->where('id', $entry->id)->update([
                    'reference_no' => 'liquidation-expense:' . $entry->liquidation_expense_id . ':budget-usage',
                ]);
            });
        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'TRANSFER')->update([
            'transaction_type' => 'LEGACY_TRANSFER',
            'description' => 'Deprecated automatic transfer retained for audit history',
        ]);
    }

    public function down(): void
    {
        $reversalIds = DB::table('accounting_budget_journal_entries')
            ->where('transaction_type', 'LEGACY_TRANSFER_REVERSAL')
            ->pluck('id');

        DB::table('accounting_budget_journal_lines')->whereIn('journal_entry_id', $reversalIds)->delete();
        DB::table('accounting_budget_journal_entries')->whereIn('id', $reversalIds)->delete();

        DB::table('accounting_budget_accounts')->where('account_type', 'PARENT_BUDGET')->update([
            'account_type' => 'PARENT_AMOUNT',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'BUDGET_ALLOCATION_CLEARING')->update([
            'account_type' => 'INCOME_CLEARING',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'BUDGET_USAGE_CLEARING')->update([
            'account_type' => 'EXPENSE_CLEARING',
        ]);
        DB::table('accounting_budget_accounts')->where('account_type', 'LEGACY_RUNNING_BALANCE')->update([
            'account_type' => 'RUNNING_BALANCE',
        ]);

        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'BUDGET_ALLOCATION')->update([
            'transaction_type' => 'INCOME',
        ]);
        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'BUDGET_USAGE')->update([
            'transaction_type' => 'EXPENSE',
        ]);
        DB::table('accounting_budget_journal_entries')->where('transaction_type', 'LEGACY_TRANSFER')->update([
            'transaction_type' => 'TRANSFER',
        ]);

        Schema::rename('accounting_legacy_wallet_funding_sources', 'accounting_wallet_funding_sources');
        Schema::rename('accounting_budget_journal_lines', 'accounting_journal_lines');
        Schema::rename('accounting_budget_journal_entries', 'accounting_journal_entries');
        Schema::rename('accounting_budget_accounts', 'accounting_wallet_accounts');
    }
};
