<?php

namespace App\Http\Controllers;

use App\Support\AccountingMonthlyBalance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AccountingController extends Controller
{
    public function liquidateExpenses(): View
    {
        $this->authorizeAccounting();

        $employees = DB::table('users')
            ->where('role_id', 2)
            ->select('id', 'name', 'employee_id')
            ->orderBy('name')
            ->get();

        $categories = DB::table('categories')
            ->select('id', 'particulars_category')
            ->orderBy('particulars_category')
            ->get();

        return view('accounting.liquidate-expenses', compact('employees', 'categories'));
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

        $particularId = null;

        if (! empty($validated['category_id'])) {
            $particular = DB::table('particulars')
                ->where('category_id', $validated['category_id'])
                ->where('particular_name', $validated['transaction_details'])
                ->first();

            $particularId = $particular?->id ?? DB::table('particulars')->insertGetId([
                'category_id' => $validated['category_id'],
                'particular_name' => $validated['transaction_details'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $expenseId = DB::table('liquidation_expenses')->insertGetId([
            'liquidation_id' => $liquidation->id,
            'expense_date' => $validated['expense_date'],
            'particular_id' => $particularId,
            'category_id' => $validated['category_id'] ?? null,
            'transaction_type' => $validated['transaction_type'],
            'transaction_details' => $validated['transaction_details'],
            'description' => $validated['description'] ?? null,
            'amount' => round((float) $validated['amount'], 2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $expense = DB::table('liquidation_expenses')
            ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
            ->join('users', 'liquidations.user_id', '=', 'users.id')
            ->leftJoin('particulars', 'liquidation_expenses.particular_id', '=', 'particulars.id')
            ->leftJoin('categories as direct_categories', 'liquidation_expenses.category_id', '=', 'direct_categories.id')
            ->leftJoin('categories as particular_categories', 'particulars.category_id', '=', 'particular_categories.id')
            ->where('liquidation_expenses.id', $expenseId)
            ->select(
                'liquidation_expenses.id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_type',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'users.name as employee_name',
                DB::raw('COALESCE(particulars.particular_name, liquidation_expenses.transaction_details) as particular_name'),
                DB::raw('COALESCE(direct_categories.particulars_category, particular_categories.particulars_category) as category_name')
            )
            ->first();

        AccountingMonthlyBalance::syncStoredMonth($expenseDate);

        return response()->json([
            'message' => 'Expense transaction recorded successfully.',
            'expense' => $expense,
        ], 201);
    }

    public function updateOpeningBalance(Request $request): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        return response()->json([
            'message' => 'Opening balance received.',
            'opening_balance' => round((float) $validated['opening_balance'], 2),
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
            'message' => 'Expense transaction deleted successfully.',
        ]);
    }

    private function authorizeAccounting(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->role_id === 3, 403);
    }
}
