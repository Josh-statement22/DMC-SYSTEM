<?php

namespace App\Http\Controllers;

use App\Models\CashAdvanceMonthlyBalance;
use App\Models\Liquidation;
use App\Models\LiquidationExpense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * Show the liquidate-expenses page with month switching capability
     */
    public function liquidateExpenses()
    {
        if ($redirect = redirect_if_role_not_allowed([3])) {
            return $redirect;
        }

        // Get current month/year or use provided parameters
        $year = request('year', now()->year);
        $month = request('month', now()->month);
        
        // Validate year and month
        $year = (int) $year;
        $month = (int) $month;
        
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        // Get or create the monthly balance record
        $monthlyBalance = CashAdvanceMonthlyBalance::firstOrCreate(
            ['year' => $year, 'month' => $month],
            ['opening_balance' => 0]
        );

        // Get all employees
        $employees = DB::table('users')
            ->where('role_id', 2)
            ->select('id', 'name', 'employee_id')
            ->orderBy('name')
            ->get();

        // Get all categories
        $categories = DB::table('categories')
            ->orderBy('particulars_category')
            ->get();

        // Get the date range for this month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all expenses for this month and year
        $expenses = LiquidationExpense::whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->get()
            ->map(function ($expense) use ($employees) {
                // Get employee name from the related liquidation
                $liquidation = $expense->liquidation;
                $employee = $employees->firstWhere('id', $liquidation->user_id);
                $expense->employee_name = $employee->name ?? 'Unknown';
                $expense->employee_id = $liquidation->user_id;
                return $expense;
            });

        // Calculate totals
        $debitTotal = $expenses->where('transaction_type', 'debit')->sum('amount');
        $creditTotal = $expenses->where('transaction_type', 'credit')->sum('amount');

        // Generate list of months for the dropdown
        $months = [];
        $currentDate = now();
        for ($i = 0; $i < 24; $i++) {
            $date = $currentDate->copy()->subMonths($i);
            $months[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y'),
                'year' => $date->year,
                'month' => $date->month,
            ];
        }

        return view('accounting.liquidate-expenses', compact(
            'employees',
            'categories',
            'monthlyBalance',
            'expenses',
            'debitTotal',
            'creditTotal',
            'year',
            'month',
            'months'
        ));
    }

    /**
     * Store a new expense transaction
     */
    public function storeExpense()
    {
        request()->validate([
            'expense_date' => 'required|date',
            'employee_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'transaction_details' => 'nullable|string',
            'description' => 'nullable|string',
            'year' => 'required|numeric|min:2000',
            'month' => 'required|numeric|min:1|max:12',
        ]);

        try {
            $expenseDate = Carbon::parse(request('expense_date'));
            $year = $expenseDate->year;
            $month = $expenseDate->month;

            // Get or create liquidation for this month/employee
            $cutoffPeriod = $expenseDate->format('Y-m');
            $liquidation = Liquidation::firstOrCreate(
                [
                    'user_id' => request('employee_id'),
                    'cutoff_period' => $cutoffPeriod,
                ],
                [
                    'amount' => 0,
                    'status' => 'pending',
                    'submitted_at' => now(),
                ]
            );

            // Create the expense
            $expense = LiquidationExpense::create([
                'liquidation_id' => $liquidation->id,
                'expense_date' => $expenseDate,
                'category_id' => request('category_id'),
                'transaction_details' => request('transaction_details'),
                'description' => request('description'),
                'amount' => request('amount'),
                'transaction_type' => request('transaction_type'),
            ]);

            // Update liquidation amount
            $liquidation->update([
                'amount' => $liquidation->expenses()->sum('amount'),
            ]);

            // Update monthly balance
            $this->updateMonthlyBalance($year, $month);

            return response()->json([
                'success' => true,
                'message' => 'Expense recorded successfully',
                'expense' => $expense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error recording expense: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update opening balance for a month
     */
    public function updateOpeningBalance()
    {
        request()->validate([
            'year' => 'required|numeric|min:2000',
            'month' => 'required|numeric|min:1|max:12',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        try {
            $monthlyBalance = CashAdvanceMonthlyBalance::firstOrCreate(
                ['year' => request('year'), 'month' => request('month')],
                ['opening_balance' => 0]
            );

            $monthlyBalance->update([
                'opening_balance' => request('opening_balance'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Opening balance updated successfully',
                'balance' => $monthlyBalance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating opening balance: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Delete an expense
     */
    public function deleteExpense($id)
    {
        try {
            $expense = LiquidationExpense::findOrFail($id);
            $liquidationId = $expense->liquidation_id;
            $year = $expense->expense_date->year;
            $month = $expense->expense_date->month;

            $expense->delete();

            // Update liquidation amount
            $liquidation = Liquidation::find($liquidationId);
            if ($liquidation) {
                $liquidation->update([
                    'amount' => $liquidation->expenses()->sum('amount'),
                ]);
            }

            // Update monthly balance
            $this->updateMonthlyBalance($year, $month);

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting expense: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update the monthly balance calculations
     */
    private function updateMonthlyBalance($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all expenses for this month
        $allExpenses = LiquidationExpense::whereBetween('expense_date', [$startDate, $endDate])->get();

        // Calculate totals
        $debitTotal = $allExpenses->where('transaction_type', 'debit')->sum('amount');
        $creditTotal = $allExpenses->where('transaction_type', 'credit')->sum('amount');

        // Update or create monthly balance
        $monthlyBalance = CashAdvanceMonthlyBalance::firstOrCreate(
            ['year' => $year, 'month' => $month],
            ['opening_balance' => 0]
        );

        $monthlyBalance->update([
            'released_total' => $debitTotal,
            'expense_total' => $creditTotal,
            'remaining_balance' => $monthlyBalance->opening_balance + $creditTotal - $debitTotal,
        ]);
    }
}
