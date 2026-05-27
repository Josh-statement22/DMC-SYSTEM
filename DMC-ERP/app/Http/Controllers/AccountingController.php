<?php

namespace App\Http\Controllers;

use App\Models\CashAdvanceMonthlyBalance;
use App\Support\AccountingMonthlyBalance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AccountingController extends Controller
{
    public function liquidateExpenses(Request $request): View
    {
        $this->authorizeAccounting();

        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        if ($year < 2000 || $year > 2100) {
            $year = (int) now()->year;
        }

        if ($month < 1 || $month > 12) {
            $month = (int) now()->month;
        }

        $monthDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $balance = AccountingMonthlyBalance::forMonth($monthDate);

        $months = collect(range(-12, 3))
            ->map(function (int $offset) use ($monthDate) {
                $date = $monthDate->copy()->addMonths($offset);

                return [
                    'value' => $date->format('Y-m'),
                    'year' => (int) $date->year,
                    'month' => (int) $date->month,
                    'label' => $date->format('F Y'),
                ];
            })
            ->values();

        $employees = DB::table('users')
            ->where('role_id', 2)
            ->select('id', 'name', 'employee_id')
            ->orderBy('name')
            ->get();

        $categories = DB::table('categories')
            ->select('id', 'particulars_category')
            ->orderBy('particulars_category')
            ->get();

        $expenses = DB::table('liquidation_expenses')
            ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
            ->join('users', 'liquidations.user_id', '=', 'users.id')
            ->leftJoin('categories as direct_categories', 'liquidation_expenses.category_id', '=', 'direct_categories.id')
            ->whereBetween('liquidation_expenses.expense_date', [
                $monthDate->toDateString(),
                $monthDate->copy()->endOfMonth()->toDateString(),
            ])
            ->select(
                'liquidation_expenses.id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_type',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'users.name as employee_name',
                DB::raw('direct_categories.particulars_category as category_name')
            )
            ->orderByDesc('liquidation_expenses.expense_date')
            ->orderByDesc('liquidation_expenses.id')
            ->get()
            ->map(function ($expense) {
                $expense->expense_date = Carbon::parse($expense->expense_date);

                return $expense;
            });

        return view('accounting.liquidate-expenses', [
            'employees' => $employees,
            'categories' => $categories,
            'year' => (int) $balance['year'],
            'month' => (int) $balance['month'],
            'months' => $months,
            'monthlyBalance' => (object) $balance,
            'debitTotal' => $balance['debit_total'],
            'creditTotal' => $balance['credit_total'],
            'expenses' => $expenses,
        ]);
    }

    public function storeExpense(Request $request): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'employee_id' => 'required|integer|exists:users,id',
            'transaction_type' => 'required|in:debit,credit',
            'category_id' => 'nullable|integer|exists:categories,id',
            'transaction_details' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $expenseDate = Carbon::parse($validated['expense_date']);
        $cutoffPeriod = $expenseDate->format('F Y');

        $liquidation = DB::table('liquidations')
            ->where('user_id', $validated['employee_id'])
            ->where('cutoff_period', $cutoffPeriod)
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if (! $liquidation) {
            $liquidationId = DB::table('liquidations')->insertGetId([
                'user_id' => $validated['employee_id'],
                'cutoff_period' => $cutoffPeriod,
                'amount' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $liquidation = DB::table('liquidations')->where('id', $liquidationId)->first();
        }

        $expenseId = DB::table('liquidation_expenses')->insertGetId([
            'liquidation_id' => $liquidation->id,
            'expense_date' => $validated['expense_date'],
            'transaction_type' => $validated['transaction_type'],
            'category_id' => $validated['category_id'] ?? null,
            'transaction_details' => $validated['transaction_details'],
            'description' => $validated['description'] ?? null,
            'amount' => round((float) $validated['amount'], 2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $expense = DB::table('liquidation_expenses')
            ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
            ->join('users', 'liquidations.user_id', '=', 'users.id')
            ->leftJoin('categories as direct_categories', 'liquidation_expenses.category_id', '=', 'direct_categories.id')
            ->where('liquidation_expenses.id', $expenseId)
            ->select(
                'liquidation_expenses.id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_type',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'users.name as employee_name',
                DB::raw('direct_categories.particulars_category as category_name')
            )
            ->first();

        AccountingMonthlyBalance::syncStoredMonth($expenseDate);

        return response()->json([
            'success' => true,
            'message' => 'Expense transaction recorded successfully.',
            'expense' => $expense,
        ], 201);
    }

    public function updateOpeningBalance(Request $request): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $monthDate = Carbon::createFromDate((int) $validated['year'], (int) $validated['month'], 1)->startOfMonth();
        $openingBalance = round((float) $validated['opening_balance'], 2);

        $monthlyBalance = CashAdvanceMonthlyBalance::firstOrNew([
            'year' => $monthDate->year,
            'month' => $monthDate->month,
        ]);

        $monthlyBalance->fill([
            'opening_balance' => $openingBalance,
            'prepared_by' => Auth::id(),
        ]);
        $monthlyBalance->save();

        AccountingMonthlyBalance::syncStoredMonth($monthDate);
        $balance = AccountingMonthlyBalance::forMonth($monthDate);

        return response()->json([
            'success' => true,
            'message' => 'Opening balance saved successfully.',
            'balance' => $balance,
        ]);
    }

    public function deleteExpense(int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $expense = DB::table('liquidation_expenses')->where('id', $id)->first();

        abort_unless($expense, 404);

        DB::table('liquidation_expenses')->where('id', $id)->delete();
        AccountingMonthlyBalance::syncStoredMonth($expense->expense_date);

        return response()->json([
            'success' => true,
            'message' => 'Expense transaction deleted successfully.',
        ]);
    }

    private function authorizeAccounting(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->role_id === 3, 403);
    }
}
