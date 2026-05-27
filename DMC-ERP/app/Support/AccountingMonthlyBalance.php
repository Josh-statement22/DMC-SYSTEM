<?php

namespace App\Support;

use App\Models\CashAdvanceMonthlyBalance;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class AccountingMonthlyBalance
{
    public static function forMonth(CarbonInterface|string|null $date = null): array
    {
        $monthDate = self::monthDate($date);
        $storedBalance = self::storedBalanceFor($monthDate);
        $openingBalance = $storedBalance
            ? (float) $storedBalance->opening_balance
            : self::previousEndingBalance($monthDate);

        $debitTotal = self::cashAdvanceRequestAmountFor($monthDate);
        $creditTotal = self::liquidationAmountFor($monthDate, 'credit');
        $expenseTotal = $debitTotal - $creditTotal;
        $endingBalance = $openingBalance - $expenseTotal;

        return [
            'id' => $storedBalance?->id,
            'exists' => (bool) $storedBalance,
            'year' => (int) $monthDate->year,
            'month' => (int) $monthDate->month,
            'month_key' => $monthDate->format('Y-m'),
            'month_label' => $monthDate->format('F Y'),
            'opening_balance' => round($openingBalance, 2),
            'debit_total' => round($debitTotal, 2),
            'credit_total' => round($creditTotal, 2),
            'expense_total' => round($expenseTotal, 2),
            'remaining_balance' => round($endingBalance, 2),
            'ending_balance' => round($endingBalance, 2),
            'released_total' => round(self::releasedAmountFor($monthDate), 2),
            'carryover_balance' => round(self::previousEndingBalance($monthDate), 2),
            'remarks' => $storedBalance?->remarks,
            'prepared_by' => $storedBalance?->prepared_by,
            'finalized_at' => optional($storedBalance?->finalized_at)?->toIso8601String(),
        ];
    }

    public static function syncStoredMonth(CarbonInterface|string|null $date = null): ?CashAdvanceMonthlyBalance
    {
        $monthDate = self::monthDate($date);
        $storedBalance = self::storedBalanceFor($monthDate);

        if (! $storedBalance) {
            return null;
        }

        $balance = self::forMonth($monthDate);

        $storedBalance->fill([
            'released_total' => $balance['released_total'],
            'expense_total' => $balance['expense_total'],
            'remaining_balance' => $balance['remaining_balance'],
        ]);
        $storedBalance->save();

        return $storedBalance;
    }

    private static function monthDate(CarbonInterface|string|null $date): Carbon
    {
        if ($date instanceof CarbonInterface) {
            return Carbon::parse($date->toDateString())->startOfMonth();
        }

        return Carbon::parse($date ?: now()->toDateString())->startOfMonth();
    }

    private static function storedBalanceFor(CarbonInterface $monthDate): ?CashAdvanceMonthlyBalance
    {
        return CashAdvanceMonthlyBalance::query()
            ->where('year', $monthDate->year)
            ->where('month', $monthDate->month)
            ->first();
    }

    private static function previousEndingBalance(CarbonInterface $monthDate): float
    {
        $previousMonth = Carbon::parse($monthDate->toDateString())->subMonth()->startOfMonth();
        $previousStoredBalance = self::storedBalanceFor($previousMonth);

        if (! $previousStoredBalance) {
            return 0.0;
        }

        $debitTotal = self::liquidationAmountFor($previousMonth, 'debit');
        $creditTotal = self::liquidationAmountFor($previousMonth, 'credit');

        return (float) $previousStoredBalance->opening_balance - ($debitTotal - $creditTotal);
    }

    private static function liquidationAmountFor(CarbonInterface $monthDate, string $transactionType): float
    {
        return (float) DB::table('liquidation_expenses')
            ->where('transaction_type', $transactionType)
            ->whereBetween('expense_date', [
                $monthDate->toDateString(),
                Carbon::parse($monthDate->toDateString())->endOfMonth()->toDateString(),
            ])
            ->sum('amount');
    }

    private static function cashAdvanceRequestAmountFor(CarbonInterface $monthDate): float
    {
        return (float) DB::table('cash_advance_requests')
            ->whereBetween('request_date', [
                $monthDate->toDateString(),
                Carbon::parse($monthDate->toDateString())->endOfMonth()->toDateString(),
            ])
            ->sum(DB::raw('COALESCE(approved_amount, requested_amount, 0)'));
    }

    private static function releasedAmountFor(CarbonInterface $monthDate): float
    {
        return (float) DB::table('cash_advance_requests')
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->whereBetween(DB::raw('DATE(COALESCE(released_at, reviewed_at, request_date))'), [
                $monthDate->toDateString(),
                Carbon::parse($monthDate->toDateString())->endOfMonth()->toDateString(),
            ])
            ->sum(DB::raw('COALESCE(approved_amount, requested_amount, 0)'));
    }
}
