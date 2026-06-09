<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AccountingBudgetService
{
    private const PARENT_BUDGET = 'PARENT_BUDGET';

    private const ALLOCATION_CLEARING = 'BUDGET_ALLOCATION_CLEARING';

    private const USAGE_CLEARING = 'BUDGET_USAGE_CLEARING';

    public function recordUsage(int $cashAdvanceRequestId): array
    {
        $this->ensureParentAllocation($cashAdvanceRequestId);
        $this->ensureExistingUsagesRecorded($cashAdvanceRequestId);

        return $this->allocationForRequest($cashAdvanceRequestId);
    }

    public function replaceUsage(
        int $cashAdvanceRequestId,
        int $liquidationExpenseId,
        float $oldAmount,
        float $newAmount
    ): void {
        $this->ensureParentAllocation($cashAdvanceRequestId);
        $this->ensureExistingUsagesRecorded($cashAdvanceRequestId);

        $changeReference = Str::uuid()->toString();
        if (abs(round($oldAmount, 2)) >= 0.01) {
            $this->recordUsageLines(
                $cashAdvanceRequestId,
                $liquidationExpenseId,
                -$this->nonZeroAmount($oldAmount),
                "liquidation-expense:{$liquidationExpenseId}:{$changeReference}:reversal",
                'Budget usage reversed before update'
            );
        }

        if (abs(round($newAmount, 2)) >= 0.01) {
            $this->recordUsageLines(
                $cashAdvanceRequestId,
                $liquidationExpenseId,
                $this->nonZeroAmount($newAmount),
                "liquidation-expense:{$liquidationExpenseId}:{$changeReference}:updated",
                'Updated budget usage recorded'
            );
        }
    }

    public function removeUsage(int $cashAdvanceRequestId, int $liquidationExpenseId, float $amount): void
    {
        if (abs(round($amount, 2)) < 0.01) {
            return;
        }

        $this->ensureParentAllocation($cashAdvanceRequestId);
        $this->ensureExistingUsagesRecorded($cashAdvanceRequestId);
        $this->recordUsageLines(
            $cashAdvanceRequestId,
            $liquidationExpenseId,
            -$this->nonZeroAmount($amount),
            'liquidation-expense:' . $liquidationExpenseId . ':' . Str::uuid() . ':deleted',
            'Budget usage reversed after deletion'
        );
    }

    public function allocationForRequest(int $cashAdvanceRequestId): array
    {
        $transaction = DB::table('cash_advance_requests')->where('id', $cashAdvanceRequestId)->first();
        abort_unless($transaction, 404);

        $budgetAmount = $this->parentBudgetAmount($transaction);
        $usedAmount = round((float) DB::table('liquidation_expenses')
            ->where('cash_advance_request_id', $cashAdvanceRequestId)
            ->where(function ($query) {
                $query->whereNull('borrow_return_status')
                    ->orWhere('borrow_return_status', '<>', 'not_yet_returned');
            })
            ->sum('amount'), 2);
        $remainingAmount = round($budgetAmount - $usedAmount, 2);
        $overspentAmount = round(max(0, -$remainingAmount), 2);

        return [
            'parent_amount' => $budgetAmount,
            'allocated_amount' => $usedAmount,
            'remaining_amount' => $remainingAmount,
            'budget_remaining' => $remainingAmount,
            'budget_variance' => $remainingAmount,
            'overspent_amount' => $overspentAmount,
            'status' => $overspentAmount > 0
                ? 'OVERSPENT'
                : ($remainingAmount === 0.0 ? 'FULLY_USED' : 'AVAILABLE'),
        ];
    }

    private function ensureParentAllocation(int $cashAdvanceRequestId): void
    {
        $transaction = DB::table('cash_advance_requests')
            ->where('id', $cashAdvanceRequestId)
            ->lockForUpdate()
            ->first();
        abort_unless($transaction, 404);

        $parentAccount = $this->lockParentAccount($cashAdvanceRequestId);
        $clearingAccount = $this->lockOrCreateAccount(
            self::ALLOCATION_CLEARING,
            'system:budget-allocation-clearing',
            'Budget Allocation Clearing'
        );
        $amount = $this->parentBudgetAmount($transaction);

        $this->createJournalEntry(
            'BUDGET_ALLOCATION',
            "cash-advance-request:{$cashAdvanceRequestId}:budget-allocation",
            $cashAdvanceRequestId,
            null,
            'Parent Budget initialized',
            [
                [$parentAccount->id, $amount],
                [$clearingAccount->id, -$amount],
            ],
            ['allocated_budget' => $amount],
            true
        );
    }

    private function ensureExistingUsagesRecorded(int $cashAdvanceRequestId): void
    {
        $expenses = DB::table('liquidation_expenses')
            ->where('cash_advance_request_id', $cashAdvanceRequestId)
            ->where(function ($query) {
                $query->whereNull('borrow_return_status')
                    ->orWhere('borrow_return_status', '<>', 'not_yet_returned');
            })
            ->select('id', 'amount')
            ->orderBy('id')
            ->get();

        foreach ($expenses as $expense) {
            $amount = round((float) $expense->amount, 2);
            if (abs($amount) < 0.01) {
                continue;
            }

            $this->recordUsageLines(
                $cashAdvanceRequestId,
                (int) $expense->id,
                $amount,
                "liquidation-expense:{$expense->id}:budget-usage",
                'Budget usage recorded',
                true
            );
        }
    }

    private function recordUsageLines(
        int $cashAdvanceRequestId,
        int $liquidationExpenseId,
        float $amount,
        string $reference,
        string $description,
        bool $skipIfExists = false
    ): void {
        $parentAccount = $this->lockParentAccount($cashAdvanceRequestId);
        $clearingAccount = $this->lockOrCreateAccount(
            self::USAGE_CLEARING,
            'system:budget-usage-clearing',
            'Budget Usage Clearing'
        );

        $this->createJournalEntry(
            $amount >= 0 ? 'BUDGET_USAGE' : 'BUDGET_USAGE_REVERSAL',
            $reference,
            $cashAdvanceRequestId,
            $liquidationExpenseId,
            $description,
            [
                [$parentAccount->id, -$amount],
                [$clearingAccount->id, $amount],
            ],
            ['budget_usage' => abs($amount)],
            $skipIfExists
        );
    }

    private function createJournalEntry(
        string $transactionType,
        string $reference,
        ?int $cashAdvanceRequestId,
        ?int $liquidationExpenseId,
        string $description,
        array $lines,
        array $meta = [],
        bool $skipIfExists = false
    ): void {
        if ($skipIfExists && DB::table('accounting_budget_journal_entries')->where('reference_no', $reference)->exists()) {
            return;
        }

        $total = round((float) collect($lines)->sum(fn (array $line) => $line[1]), 2);
        if (abs($total) > 0.009) {
            throw new RuntimeException("Budget journal entry {$reference} is not balanced.");
        }

        $entryId = DB::table('accounting_budget_journal_entries')->insertGetId([
            'reference_no' => $reference,
            'transaction_type' => $transactionType,
            'cash_advance_request_id' => $cashAdvanceRequestId,
            'liquidation_expense_id' => $liquidationExpenseId,
            'description' => $description,
            'created_by' => Auth::id(),
            'meta' => $meta ? json_encode($meta) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($lines as [$accountId, $amount]) {
            DB::table('accounting_budget_journal_lines')->insert([
                'journal_entry_id' => $entryId,
                'account_id' => $accountId,
                'amount' => round((float) $amount, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function lockParentAccount(int $cashAdvanceRequestId): object
    {
        return $this->lockOrCreateAccount(
            self::PARENT_BUDGET,
            "cash-advance-request:{$cashAdvanceRequestId}",
            "Parent Budget #{$cashAdvanceRequestId}"
        );
    }

    private function lockOrCreateAccount(string $accountType, string $reference, string $name): object
    {
        DB::table('accounting_budget_accounts')->insertOrIgnore([
            'account_type' => $accountType,
            'reference' => $reference,
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('accounting_budget_accounts')
            ->where('account_type', $accountType)
            ->where('reference', $reference)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function parentBudgetAmount(object $transaction): float
    {
        return round((float) ($transaction->approved_amount ?? $transaction->requested_amount ?? 0), 2);
    }

    private function nonZeroAmount(float $amount): float
    {
        $amount = round($amount, 2);
        if (abs($amount) < 0.01) {
            throw new RuntimeException('Budget usage amount cannot be zero.');
        }

        return $amount;
    }
}
