<?php

namespace App\Http\Controllers;

use App\Models\CashAdvanceMonthlyBalance;
use App\Support\AccountingMonthlyBalance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        // Show recorded transactions coming from cash advance requests (backtracking)
        $expenses = DB::table('cash_advance_requests')
            ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->whereBetween('cash_advance_requests.request_date', [
                $monthDate->toDateString(),
                $monthDate->copy()->endOfMonth()->toDateString(),
            ])
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as employee_id',
                'cash_advance_requests.request_date as expense_date',
                DB::raw("CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END as transaction_type"),
                'cash_advance_requests.purpose as transaction_details',
                'cash_advance_requests.category',
                'cash_advance_requests.accounting_remarks as description',
                'cash_advance_requests.approved_amount as amount',
                'users.name as employee_name'
            )
            ->orderByDesc('cash_advance_requests.request_date')
            ->orderByDesc('cash_advance_requests.id')
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

        if ($request->input('record_source') === 'breakdown') {
            $validated = $request->validate([
                'expense_date' => 'required|date',
                'employee_id' => 'required|integer|exists:users,id',
                'category_id' => 'required|integer|exists:categories,id',
                'amount' => 'required|numeric|min:0.01',
                'transaction_details' => 'nullable|string|max:255',
                'description' => 'nullable|string',
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
                'category_id' => $validated['category_id'],
                'transaction_details' => $validated['transaction_details'] ?? null,
                'description' => $validated['description'] ?? null,
                'amount' => round((float) $validated['amount'], 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $expense = DB::table('liquidation_expenses')
                ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
                ->leftJoin('users', 'liquidations.user_id', '=', 'users.id')
                ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
                ->where('liquidation_expenses.id', $expenseId)
                ->select(
                    'liquidation_expenses.id',
                    'liquidation_expenses.expense_date',
                    'liquidation_expenses.transaction_details',
                    'liquidation_expenses.description',
                    'liquidation_expenses.amount',
                    'users.name as employee_name',
                    DB::raw('categories.particulars_category as category_name')
                )
                ->first();

            AccountingMonthlyBalance::syncStoredMonth($expenseDate);

            return response()->json([
                'success' => true,
                'message' => 'Breakdown saved successfully.',
                'expense' => $expense,
            ], 201);
        }

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'employee_id' => 'nullable|integer|exists:users,id',
            'transaction_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:2000',
            'category' => 'nullable|string|exists:categories,particulars_category',
        ]);

        $expenseDate = Carbon::parse($validated['expense_date']);
        $cutoffPeriod = $expenseDate->format('F Y');
        $transactionType = $validated['transaction_type'];

        $purpose = trim($validated['purpose']) ?: null;

        // Allow null user_id for liquidations when employee not selected.
        $liquidationUserId = $validated['employee_id'] ?? null;

        if ($transactionType === 'debit') {
            $liquidation = DB::table('liquidations')
                ->where('user_id', $liquidationUserId)
                ->where('cutoff_period', $cutoffPeriod)
                ->where('status', 'pending')
                ->orderByDesc('id')
                ->first();

            if (! $liquidation) {
                $liquidationId = DB::table('liquidations')->insertGetId([
                    'user_id' => $liquidationUserId,
                    'cutoff_period' => $cutoffPeriod,
                    'amount' => 0,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $liquidation = DB::table('liquidations')->where('id', $liquidationId)->first();
            }
        }

        // Create a cash advance request entry instead of a liquidation expense
        $referenceNo = 'CA-' . time() . '-' . mt_rand(100, 999);

        $now = now();
        $currentUserId = Auth::id();
        $currentUserName = optional(Auth::user())->name;

        $requesterId = $validated['employee_id'] ?? $currentUserId;

        $requestId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => $referenceNo,
            'requester_id' => $requesterId,
            'requested_amount' => round((float) $validated['amount'], 2),
            'approved_amount' => round((float) $validated['amount'], 2),
            'purpose' => $purpose,
            'category' => $validated['category'] ?? null,
            'request_date' => $validated['expense_date'],
            'date_needed' => $validated['expense_date'],
            'status' => 'approved',
            'accounting_remarks' => $transactionType === 'credit' ? 'Manual Credit Entry' : 'Manually Recorded',
            'reviewed_by' => $currentUserId,
            'approved_by_name' => $currentUserName,
            'sent_by_name' => $currentUserName,
            'submitted_at' => $now,
            'reviewed_at' => $now,
            'released_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $expense = DB::table('cash_advance_requests')
            ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->where('cash_advance_requests.id', $requestId)
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.request_date as expense_date',
                DB::raw("CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END as transaction_type"),
                'cash_advance_requests.purpose as transaction_details',
                'cash_advance_requests.category',
                'cash_advance_requests.accounting_remarks as description',
                'cash_advance_requests.approved_amount as amount',
                'users.name as employee_name'
            )
            ->first();

        AccountingMonthlyBalance::syncStoredMonth($expenseDate);

        return response()->json([
            'success' => true,
            'message' => 'Expense transaction recorded successfully.',
            'expense' => $expense,
        ], 201);
    }

    // Cash advance requests endpoint removed — UI no longer uses it

    public function updateExpenseCategory(Request $request, int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'category' => 'nullable|string|exists:categories,particulars_category',
        ]);

        $expense = DB::table('cash_advance_requests')
            ->where('id', $id)
            ->first();

        abort_unless($expense, 404);

        $category = trim((string) ($validated['category'] ?? ''));
        $category = $category === '' ? null : $category;

        DB::table('cash_advance_requests')
            ->where('id', $id)
            ->update([
                'category' => $category,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction category updated successfully.',
            'transaction' => [
                'id' => $id,
                'category' => $category,
            ],
        ]);
    }

    private function getCategoryIdByName(?string $categoryName): ?int
    {
        if (!$categoryName) {
            return null;
        }

        $category = DB::table('categories')
            ->where('particulars_category', $categoryName)
            ->select('id')
            ->first();

        return $category?->id ?? null;
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

    public function importExpenses(Request $request): JsonResponse
    {
        $this->authorizeAccounting();

        $rows = collect($request->input('rows', []));

        if ($rows->isEmpty()) {
            return response()->json([
                'message' => 'No import rows were provided.',
                'imported_rows' => 0,
                'failed_rows' => 0,
                'failures' => [],
            ], 422);
        }

        if ($rows->count() > 1000) {
            return response()->json([
                'message' => 'Import is limited to 1,000 rows per file.',
                'imported_rows' => 0,
                'failed_rows' => $rows->count(),
                'failures' => [],
            ], 422);
        }

        $normalize = function ($value): string {
            return strtolower(preg_replace('/\s+/', ' ', trim((string) $value)));
        };

        $categories = DB::table('categories')
            ->select('id', 'particulars_category')
            ->get()
            ->mapWithKeys(fn ($category) => [$normalize($category->particulars_category) => $category]);

        $employees = DB::table('users')
            ->where('role_id', 2)
            ->select('id', 'name', 'employee_id')
            ->get()
            ->flatMap(function ($employee) use ($normalize) {
                $keys = [$normalize($employee->name) => $employee];

                if ($employee->employee_id) {
                    $keys[$normalize($employee->employee_id)] = $employee;
                }

                return $keys;
            });

        $failures = [];
        $importedRows = [];
        $syncedMonths = [];
        $now = now();
        $currentUserId = Auth::id();
        $currentUserName = optional(Auth::user())->name;

        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                $failures[] = [
                    'row_number' => $index + 2,
                    'errors' => ['Row data is invalid.'],
                ];

                continue;
            }

            $rowNumber = (int) ($row['row_number'] ?? ($index + 2));
            $errors = [];

            $dateValue = trim((string) ($row['date'] ?? ''));
            try {
                $expenseDate = $dateValue !== '' ? Carbon::parse($dateValue)->startOfDay() : null;
            } catch (\Throwable $exception) {
                $expenseDate = null;
            }

            if (! $expenseDate) {
                $errors[] = 'Date is required or invalid.';
            }

            $employeeKey = $normalize($row['employee'] ?? '');
            $employee = $employeeKey !== '' ? ($employees[$employeeKey] ?? null) : null;
            if (! $employee) {
                $errors[] = 'Employee must match an existing employee name or employee ID.';
            }

            $typeValue = $normalize($row['type'] ?? '');
            $transactionType = match ($typeValue) {
                'debit', 'dr', 'cash advance', 'advance' => 'debit',
                'credit', 'cr', 'return', 'refund', 'liquidation' => 'credit',
                default => null,
            };

            if (! $transactionType) {
                $errors[] = 'Type must be Debit or Credit.';
            }

            $purpose = trim((string) ($row['purpose'] ?? ''));
            if ($purpose === '') {
                $errors[] = 'Purpose is required.';
            }

            $categoryKey = $normalize($row['category'] ?? '');
            $category = $categoryKey !== '' ? ($categories[$categoryKey] ?? null) : null;
            if (! $category) {
                $errors[] = 'Category must match an existing Breakdown Expenses category.';
            }

            $amountValue = preg_replace('/[^\d.\-]/', '', (string) ($row['amount'] ?? ''));
            $amount = is_numeric($amountValue) ? round((float) $amountValue, 2) : 0.0;
            if ($amount <= 0) {
                $errors[] = 'Amount must be greater than zero.';
            }

            if ($errors) {
                $failures[] = [
                    'row_number' => $rowNumber,
                    'errors' => $errors,
                ];

                continue;
            }

            $remarks = trim((string) ($row['remarks'] ?? ''));
            $remarksPrefix = $transactionType === 'credit'
                ? 'Manual Credit Entry - Imported from Excel'
                : 'Imported from Excel';
            $accountingRemarks = $remarks !== ''
                ? $remarksPrefix . ' - ' . Str::limit($remarks, 1800, '')
                : $remarksPrefix;

            try {
                $requestId = DB::table('cash_advance_requests')->insertGetId([
                    'reference_no' => 'IMP-' . $now->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                    'requester_id' => $employee->id,
                    'requested_amount' => $amount,
                    'approved_amount' => $amount,
                    'purpose' => $purpose,
                    'category' => $category->particulars_category,
                    'request_date' => $expenseDate->toDateString(),
                    'date_needed' => $expenseDate->toDateString(),
                    'status' => 'approved',
                    'accounting_remarks' => $accountingRemarks,
                    'reviewed_by' => $currentUserId,
                    'approved_by_name' => $currentUserName,
                    'sent_by_name' => $currentUserName,
                    'submitted_at' => $now,
                    'reviewed_at' => $now,
                    'released_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $importedRows[] = [
                    'row_number' => $rowNumber,
                    'id' => $requestId,
                ];
                $syncedMonths[$expenseDate->format('Y-m')] = $expenseDate->toDateString();
            } catch (\Throwable $exception) {
                $failures[] = [
                    'row_number' => $rowNumber,
                    'errors' => ['Unable to save this row. Please check the data and try again.'],
                ];
            }
        }

        foreach ($syncedMonths as $monthDate) {
            AccountingMonthlyBalance::syncStoredMonth($monthDate);
        }

        return response()->json([
            'message' => count($importedRows) > 0
                ? 'Excel import completed.'
                : 'No rows were imported.',
            'imported_rows' => count($importedRows),
            'failed_rows' => count($failures),
            'imported' => $importedRows,
            'failures' => $failures,
        ], count($importedRows) > 0 ? 201 : 422);
    }

    public function deleteExpense(int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $expense = DB::table('cash_advance_requests')->where('id', $id)->first();

        abort_unless($expense, 404);

        DB::table('cash_advance_requests')->where('id', $id)->delete();
        AccountingMonthlyBalance::syncStoredMonth($expense->request_date);

        return response()->json([
            'success' => true,
            'message' => 'Expense transaction deleted successfully.',
        ]);
    }

    public function showExpenseBreakdown(int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $debit = DB::table('cash_advance_requests')
            ->join('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->where('cash_advance_requests.id', $id)
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as employee_id',
                'cash_advance_requests.request_date as expense_date',
                'users.name as employee_name'
            )
            ->first();

        abort_unless($debit, 404);

        $cutoffPeriod = Carbon::parse($debit->expense_date)->format('F Y');

        $liquidation = DB::table('liquidations')
            ->where('user_id', $debit->employee_id)
            ->where('cutoff_period', $cutoffPeriod)
            ->orderByDesc('id')
            ->first();

        $breakdowns = collect();

        if ($liquidation) {
            $breakdowns = DB::table('liquidation_expenses')
                ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
                ->where('liquidation_expenses.liquidation_id', $liquidation->id)
                ->select(
                    'liquidation_expenses.id',
                    'liquidation_expenses.expense_date',
                    'liquidation_expenses.transaction_details',
                    'liquidation_expenses.description',
                    'liquidation_expenses.amount',
                    DB::raw('categories.particulars_category as category_name')
                )
                ->orderBy('liquidation_expenses.expense_date')
                ->orderBy('liquidation_expenses.id')
                ->get();
        }

        return response()->json([
            'success' => true,
            'debit' => [
                'id' => $debit->id,
                'employee_name' => $debit->employee_name,
                'expense_date' => Carbon::parse($debit->expense_date)->format('Y-m-d'),
            ],
            'breakdowns' => $breakdowns->map(function ($row) {
                return [
                    'id' => $row->id,
                    'expense_date' => Carbon::parse($row->expense_date)->format('Y-m-d'),
                    'category_name' => $row->category_name,
                    'transaction_details' => $row->transaction_details,
                    'description' => $row->description,
                    'amount' => (float) $row->amount,
                ];
            })->values(),
        ]);
    }

    private function authorizeAccounting(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->role_id === 3, 403);
    }
}
