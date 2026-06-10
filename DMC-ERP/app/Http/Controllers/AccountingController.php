<?php

namespace App\Http\Controllers;

use App\Models\CashAdvanceMonthlyBalance;
use App\Services\AccountingBudgetService;
use App\Support\AccountingMonthlyBalance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccountingController extends Controller
{
    public function liquidateExpenses(Request $request): View|string
    {
        $this->authorizeAccounting();

        $year = 2026;
        $month = (int) $request->query('month', now()->month);

        if ($month < 1 || $month > 12) {
            $month = now()->year === 2026 ? (int) now()->month : 1;
        }

        $monthDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $balance = AccountingMonthlyBalance::forMonth($monthDate);

        $months = collect(range(1, 12))
            ->map(function (int $month) {
                $date = Carbon::createFromDate(2026, $month, 1);

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

        $transactionTypeSql = "CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END";
        $selectedType = strtolower((string) $request->query('type', ''));
        $selectedType = in_array($selectedType, ['credit', 'debit'], true) ? $selectedType : '';
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $startDate = is_string($startDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) ? $startDate : '';
        $endDate = is_string($endDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate) ? $endDate : '';
        $selectedCategory = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('search', ''));

        $returnedBorrowSelect = Schema::hasColumn('liquidation_expenses', 'borrow_return_status')
            ? DB::raw("EXISTS (
                SELECT 1
                FROM liquidation_expenses
                WHERE liquidation_expenses.cash_advance_request_id = cash_advance_requests.id
                    AND liquidation_expenses.borrow_return_status = 'returned'
            ) as has_returned_borrow")
            : DB::raw('0 as has_returned_borrow');

        // Show recorded transactions coming from cash advance requests (backtracking)
        $expensesQuery = DB::table('cash_advance_requests')
            ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->leftJoin('categories as transaction_categories', 'cash_advance_requests.category_id', '=', 'transaction_categories.id')
            ->whereBetween('cash_advance_requests.request_date', [
                $monthDate->toDateString(),
                $monthDate->copy()->endOfMonth()->toDateString(),
            ])
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as employee_id',
                'cash_advance_requests.request_date as expense_date',
                DB::raw("{$transactionTypeSql} as transaction_type"),
                'cash_advance_requests.purpose as transaction_details',
                'cash_advance_requests.category_id',
                DB::raw('COALESCE(transaction_categories.particulars_category, cash_advance_requests.category) as category'),
                'cash_advance_requests.accounting_remarks as description',
                'cash_advance_requests.approved_amount as amount',
                'users.name as employee_name',
                $returnedBorrowSelect
            );

        if ($selectedType) {
            $expensesQuery->whereRaw("{$transactionTypeSql} = ?", [$selectedType]);
        }

        if ($startDate) {
            $expensesQuery->whereDate('cash_advance_requests.request_date', '>=', $startDate);
        }

        if ($endDate) {
            $expensesQuery->whereDate('cash_advance_requests.request_date', '<=', $endDate);
        }

        if ($selectedCategory !== '') {
            $expensesQuery->whereRaw('COALESCE(transaction_categories.particulars_category, cash_advance_requests.category) = ?', [$selectedCategory]);
        }

        if ($search !== '') {
            $expensesQuery->where(function ($query) use ($search) {
                $query->where('users.name', 'like', "%{$search}%")
                    ->orWhere('cash_advance_requests.purpose', 'like', "%{$search}%")
                    ->orWhereRaw('COALESCE(transaction_categories.particulars_category, cash_advance_requests.category) like ?', ["%{$search}%"])
                    ->orWhere('cash_advance_requests.accounting_remarks', 'like', "%{$search}%");
            });
        }

        $expenses = $expensesQuery
            ->orderByDesc('cash_advance_requests.request_date')
            ->orderByDesc('cash_advance_requests.id')
            ->paginate(15)
            ->withQueryString();

        $expenses->getCollection()->transform(function ($expense) {
            $expense->expense_date = Carbon::parse($expense->expense_date);

            return $expense;
        });

        $transactionFilters = [
            'type' => $selectedType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'category' => $selectedCategory,
            'search' => $search,
        ];
        $hasTransactionFilters = (bool) ($selectedType || $startDate || $endDate || $selectedCategory || $search);

        if ($request->ajax()) {
            return view('transactions.partials.table', [
                'categories' => $categories,
                'employees' => $employees,
                'expenses' => $expenses,
                'hasTransactionFilters' => $hasTransactionFilters,
            ])->render();
        }

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
            'transactionFilters' => $transactionFilters,
            'hasTransactionFilters' => $hasTransactionFilters,
        ]);
    }

    public function storeExpense(Request $request): JsonResponse
    {
        $this->authorizeAccounting();

        if ($request->input('record_source') === 'breakdown') {
            $validated = $request->validate([
                'cash_advance_request_id' => 'required|integer|exists:cash_advance_requests,id',
                'expense_date' => 'required|date',
                'employee_id' => 'required|integer|exists:users,id',
                'category_id' => 'required|integer|exists:categories,id',
                'amount' => 'required|numeric',
                'transaction_details' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'borrow_return_status' => 'nullable|in:returned,not_yet_returned',
                'attachments' => 'nullable|array|max:10',
                'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,heic,heif,gif,bmp,svg,doc,docx,xls,xlsx,csv,txt,rtf,ppt,pptx|max:10240',
            ]);

            $expenseDate = Carbon::parse($validated['expense_date']);
            $cutoffPeriod = $expenseDate->format('F Y');
            $breakdownAmount = round((float) $validated['amount'], 2);
            if ($breakdownAmount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Breakdown amount must be greater than zero.',
                ], 422);
            }

            $categoryName = (string) DB::table('categories')
                ->where('id', $validated['category_id'])
                ->value('particulars_category');
            $isBorrowCategory = strcasecmp($categoryName, 'Borrow') === 0;
            $borrowReturnStatus = $isBorrowCategory ? ($validated['borrow_return_status'] ?? null) : null;

            if ($isBorrowCategory && ! $borrowReturnStatus) {
                throw ValidationException::withMessages([
                    'borrow_return_status' => 'Please choose Returned or Not yet returned for Borrow breakdowns.',
                ]);
            }

            $transactionDetails = trim((string) ($validated['transaction_details'] ?? ''));
            if ($isBorrowCategory && $borrowReturnStatus && $transactionDetails === '') {
                $transactionDetails = $borrowReturnStatus === 'returned'
                    ? 'Returned'
                    : 'Not yet returned';
            }
            $validated['transaction_details'] = $transactionDetails !== '' ? $transactionDetails : null;
            $validated['borrow_return_status'] = $borrowReturnStatus;

            $result = DB::transaction(function () use ($validated, $cutoffPeriod, $breakdownAmount, $expenseDate) {
                $transaction = DB::table('cash_advance_requests')
                    ->where('id', $validated['cash_advance_request_id'])
                    ->lockForUpdate()
                    ->first();

                abort_unless($transaction, 404);

                $liquidation = DB::table('liquidations')
                    ->where('user_id', $validated['employee_id'])
                    ->where('cutoff_period', $cutoffPeriod)
                    ->where('status', 'pending')
                    ->orderByDesc('id')
                    ->lockForUpdate()
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

                $expensePayload = [
                    'liquidation_id' => $liquidation->id,
                    'cash_advance_request_id' => $validated['cash_advance_request_id'],
                    'expense_date' => $validated['expense_date'],
                    'category_id' => $validated['category_id'],
                    'transaction_details' => $validated['transaction_details'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'amount' => $breakdownAmount,
                    'borrow_return_status' => $validated['borrow_return_status'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (Schema::hasColumn('liquidation_expenses', 'particular_id')) {
                    $expensePayload['particular_id'] = $this->defaultParticularIdForCategory(
                        (int) $validated['category_id']
                    );
                }

                $expenseId = DB::table('liquidation_expenses')->insertGetId($expensePayload);

                $allocation = app(AccountingBudgetService::class)->recordUsage((int) $transaction->id);

                return compact('expenseId', 'allocation');
            });

            $attachmentCount = $this->storeTransactionAttachments(
                $request,
                (int) $result['expenseId'],
                $expenseDate,
                $validated['transaction_details'] ?? $validated['description'] ?? 'breakdown',
                (int) $validated['employee_id']
            );

            $expense = DB::table('liquidation_expenses')
                ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
                ->leftJoin('users', 'liquidations.user_id', '=', 'users.id')
                ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
                ->where('liquidation_expenses.id', $result['expenseId'])
                ->select(
                    'liquidation_expenses.id',
                    'liquidation_expenses.expense_date',
                    'liquidation_expenses.transaction_details',
                    'liquidation_expenses.description',
                    'liquidation_expenses.amount',
                    'liquidation_expenses.borrow_return_status',
                    'users.name as employee_name',
                    DB::raw('categories.particulars_category as category_name')
                )
                ->first();

            AccountingMonthlyBalance::syncStoredMonth($expenseDate);

            return response()->json([
                'success' => true,
                'message' => ($result['allocation']['overspent_amount'] ?? 0) > 0
                    ? 'Breakdown saved. Parent Budget is overspent; actual cash was not deducted again.'
                    : 'Breakdown saved successfully.',
                'expense' => $expense,
                'attachment_count' => $attachmentCount,
                'allocation' => $result['allocation'],
            ], 201);
        }

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'employee_id' => 'nullable|integer|exists:users,id',
            'transaction_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:2000',
            'category_id' => 'nullable|integer|exists:categories,id',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,heic,heif,gif,bmp,svg,doc,docx,xls,xlsx,csv,txt,rtf,ppt,pptx|max:10240',
        ]);

        $expenseDate = Carbon::parse($validated['expense_date']);
        $cutoffPeriod = $expenseDate->format('F Y');
        $transactionType = $validated['transaction_type'];
        $categoryId = $validated['category_id'] ?? null;

        if ($transactionType === 'debit' && ! $categoryId) {
            throw ValidationException::withMessages([
                'category_id' => 'Category is required for Debit transactions.',
            ]);
        }

        $categoryName = $categoryId
            ? DB::table('categories')->where('id', $categoryId)->value('particulars_category')
            : null;

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
            'category_id' => $categoryId,
            'category' => $categoryName,
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

        $this->storeCashAdvanceRequestAttachments(
            $request,
            (int) $requestId,
            $expenseDate,
            $purpose,
            $requesterId
        );

        $expense = DB::table('cash_advance_requests')
            ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->leftJoin('categories as transaction_categories', 'cash_advance_requests.category_id', '=', 'transaction_categories.id')
            ->where('cash_advance_requests.id', $requestId)
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.request_date as expense_date',
                DB::raw("CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END as transaction_type"),
                'cash_advance_requests.purpose as transaction_details',
                'cash_advance_requests.category_id',
                DB::raw('COALESCE(transaction_categories.particulars_category, cash_advance_requests.category) as category'),
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

    public function updateExpense(Request $request, int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'employee_id' => 'nullable|integer|exists:users,id',
            'transaction_type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:2000',
            'category_id' => 'nullable|integer|exists:categories,id',
            'remarks' => 'nullable|string|max:2000',
        ]);

        $expense = DB::table('cash_advance_requests')
            ->where('id', $id)
            ->first();

        abort_unless($expense, 404);

        $categoryId = $validated['category_id'] ?? null;

        if ($validated['transaction_type'] === 'debit' && ! $categoryId) {
            throw ValidationException::withMessages([
                'category_id' => 'Category is required for Debit transactions.',
            ]);
        }

        $categoryName = $categoryId
            ? DB::table('categories')->where('id', $categoryId)->value('particulars_category')
            : null;

        $remarks = trim((string) ($validated['remarks'] ?? ''));
        $accountingRemarks = $this->normalizeAccountingRemarks($validated['transaction_type'], $remarks);
        $oldDate = $expense->request_date;

        DB::table('cash_advance_requests')
            ->where('id', $id)
            ->update([
                'requester_id' => $validated['employee_id'] ?? Auth::id(),
                'requested_amount' => round((float) $validated['amount'], 2),
                'approved_amount' => round((float) $validated['amount'], 2),
                'purpose' => trim($validated['purpose']),
                'category_id' => $categoryId,
                'category' => $categoryName,
                'request_date' => $validated['expense_date'],
                'date_needed' => $validated['expense_date'],
                'accounting_remarks' => $accountingRemarks,
                'updated_at' => now(),
            ]);

        AccountingMonthlyBalance::syncStoredMonth($oldDate);
        AccountingMonthlyBalance::syncStoredMonth($validated['expense_date']);

        $transactionTypeSql = "CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END";
        $updatedExpense = DB::table('cash_advance_requests')
            ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->leftJoin('categories as transaction_categories', 'cash_advance_requests.category_id', '=', 'transaction_categories.id')
            ->where('cash_advance_requests.id', $id)
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as employee_id',
                'cash_advance_requests.request_date as expense_date',
                DB::raw("{$transactionTypeSql} as transaction_type"),
                'cash_advance_requests.purpose as transaction_details',
                'cash_advance_requests.category_id',
                DB::raw('COALESCE(transaction_categories.particulars_category, cash_advance_requests.category) as category'),
                'cash_advance_requests.accounting_remarks as description',
                'cash_advance_requests.approved_amount as amount',
                'users.name as employee_name'
            )
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully.',
            'expense' => $updatedExpense,
        ]);
    }

    public function updateExpenseCategory(Request $request, int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $expense = DB::table('cash_advance_requests')
            ->where('id', $id)
            ->first();

        abort_unless($expense, 404);

        $isDebit = ! str_contains(strtolower((string) ($expense->accounting_remarks ?? '')), 'manual credit entry');
        $categoryId = $validated['category_id'] ?? null;

        if ($isDebit && ! $categoryId) {
            throw ValidationException::withMessages([
                'category_id' => 'Category is required for Debit transactions.',
            ]);
        }

        $category = $categoryId
            ? DB::table('categories')->where('id', $categoryId)->value('particulars_category')
            : null;

        DB::table('cash_advance_requests')
            ->where('id', $id)
            ->update([
                'category_id' => $categoryId,
                'category' => $category,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction category updated successfully.',
            'transaction' => [
                'id' => $id,
                'category_id' => $categoryId,
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

    private function normalizeAccountingRemarks(string $transactionType, string $remarks): string
    {
        if ($transactionType === 'credit') {
            if ($remarks === '') {
                return 'Manual Credit Entry';
            }

            return str_contains(strtolower($remarks), 'manual credit entry')
                ? $remarks
                : 'Manual Credit Entry - ' . Str::limit($remarks, 1800, '');
        }

        if ($remarks === '') {
            return 'Manually Recorded';
        }

        return preg_replace('/manual credit entry\s*-?\s*/i', '', $remarks) ?: 'Manually Recorded';
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
        $normalizeLookup = function ($value) use ($normalize): string {
            return preg_replace('/[^a-z0-9]/', '', $normalize($value));
        };

        $categories = DB::table('categories')
            ->select('id', 'particulars_category')
            ->get()
            ->flatMap(function ($category) use ($normalize, $normalizeLookup) {
                return [
                    $normalize($category->particulars_category) => $category,
                    $normalizeLookup($category->particulars_category) => $category,
                ];
            });

        $employees = DB::table('users')
            ->where('role_id', 2)
            ->select('id', 'name', 'employee_id')
            ->get()
            ->flatMap(function ($employee) use ($normalize, $normalizeLookup) {
                $keys = [
                    $normalize($employee->name) => $employee,
                    $normalizeLookup($employee->name) => $employee,
                ];

                if ($employee->employee_id) {
                    $keys[$normalize($employee->employee_id)] = $employee;
                    $keys[$normalizeLookup($employee->employee_id)] = $employee;
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

            $employeeValue = $row['employee'] ?? '';
            
            // If employee name is blank OR not found in system, automatically use the current logged-in user
            if (trim($employeeValue) === '') {
                $employee = Auth::user();
            } else {
                $employeeKey = $normalize($employeeValue);
                $employeeLookupKey = $normalizeLookup($employeeValue);
                $employee = $employeeKey !== ''
                    ? ($employees[$employeeKey] ?? $employees[$employeeLookupKey] ?? null)
                    : null;
                
                // If employee not found, use current user
                if (!$employee) {
                    $employee = Auth::user();
                }
            }
            
            if (! $employee) {
                $errors[] = 'Unable to determine employee for this transaction.';
            }

            $typeValue = $normalize($row['type'] ?? '');
            $transactionType = null;
            if (str_contains($typeValue, 'credit')
                || str_contains($typeValue, 'cash in')
                || str_contains($typeValue, 'received')
                || str_contains($typeValue, 'return')
                || str_contains($typeValue, 'refund')
                || in_array($typeValue, ['cr', 'c'], true)) {
                $transactionType = 'credit';
            } elseif (str_contains($typeValue, 'debit')
                || str_contains($typeValue, 'cash out')
                || str_contains($typeValue, 'cash advance')
                || str_contains($typeValue, 'advance')
                || in_array($typeValue, ['dr', 'd'], true)) {
                $transactionType = 'debit';
            }

            if (! $transactionType) {
                $errors[] = 'Type must be Debit/Cash Out or Credit/Cash In.';
            }

            $purpose = trim((string) ($row['purpose'] ?? ''));

            $categoryValue = $row['category'] ?? '';
            $category = null;
            
            if ($categoryValue !== '') {
                $categoryKey = $normalize($categoryValue);
                $categoryLookupKey = $normalizeLookup($categoryValue);
                $category = $categoryKey !== ''
                    ? ($categories[$categoryKey] ?? $categories[$categoryLookupKey] ?? null)
                    : null;
                
                // If category doesn't exist, create it automatically
                if (!$category) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'particulars_category' => trim($categoryValue),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $category = (object) [
                        'id' => $categoryId,
                        'particulars_category' => trim($categoryValue),
                    ];
                    
                    // Add to categories map for future lookups in this import
                    $categories[$categoryKey] = $category;
                    $categories[$categoryLookupKey] = $category;
                }
            }

            if ($transactionType === 'debit' && ! $category) {
                $errors[] = 'Category is required for Debit transactions.';
            }

            $amountValue = trim((string) ($row['amount'] ?? ''));
            
            // Handle empty amount
            if ($amountValue === '') {
                $amount = 0.0;
            } else {
                // Remove currency symbols and spaces
                $amountValue = preg_replace('/[₱$€¥\s]/u', '', $amountValue);
                
                // Handle negative amounts in parentheses format: (1000) = -1000
                $isNegative = preg_match('/^\([\d.,\s]+\)$/', $amountValue);
                if ($isNegative) {
                    $amountValue = '-' . preg_replace('/[()]/','', $amountValue);
                }
                
                // Remove commas (thousand separators)
                $amountValue = str_replace(',', '', $amountValue);
                
                // Remove any remaining non-numeric characters except dots and minus signs
                $amountValue = preg_replace('/[^\d.\-]/','', $amountValue);
                
                // Ensure only one decimal point
                $parts = explode('.', $amountValue);
                if (count($parts) > 2) {
                    $amountValue = $parts[0] . '.' . implode('', array_slice($parts, 1));
                }
                
                // Convert to float - be strict about numeric validation
                if (empty($amountValue) || $amountValue === '.' || $amountValue === '-' || $amountValue === '-.') {
                    $amount = 0.0;
                } else {
                    $amount = (float) $amountValue;
                    $amount = round($amount, 2);
                }
            }
            
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
                    'purpose' => $purpose !== '' ? $purpose : null,
                    'category_id' => $category?->id,
                    'category' => $category?->particulars_category,
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

    public function clearMonth(int $year, int $month): JsonResponse
    {
        $this->authorizeAccounting();

        // Validate year and month
        if ($month < 1 || $month > 12) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid month.',
            ], 422);
        }

        // Get all expenses for the specified month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $expenses = DB::table('cash_advance_requests')
            ->whereBetween('request_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->select('id', 'request_date')
            ->get();

        if ($expenses->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No transactions found for this month.',
                'deleted_count' => 0,
            ]);
        }

        // Delete all expenses for this month
        DB::table('cash_advance_requests')
            ->whereBetween('request_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->delete();

        // Sync the monthly balance for this month
        AccountingMonthlyBalance::syncStoredMonth($startDate->toDateString());

        return response()->json([
            'success' => true,
            'message' => sprintf('Successfully deleted %d transaction(s) for %s %d.', $expenses->count(), $startDate->format('F'), $year),
            'deleted_count' => $expenses->count(),
        ]);
    }

    public function showExpenseBreakdown(Request $request, int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $debit = DB::table('cash_advance_requests')
            ->join('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->where('cash_advance_requests.id', $id)
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as employee_id',
                'cash_advance_requests.request_date as expense_date',
                'cash_advance_requests.approved_amount',
                'cash_advance_requests.requested_amount',
                'users.name as employee_name'
            )
            ->first();

        abort_unless($debit, 404);

        $transactionAmount = round((float) ($debit->approved_amount ?? $debit->requested_amount ?? 0), 2);
        $breakdowns = DB::table('liquidation_expenses')
            ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
            ->where('liquidation_expenses.cash_advance_request_id', $debit->id)
            ->select(
                'liquidation_expenses.id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'liquidation_expenses.receipt_path',
                'liquidation_expenses.borrow_return_status',
                DB::raw('categories.particulars_category as category_name')
            )
            ->orderBy('liquidation_expenses.expense_date')
            ->orderBy('liquidation_expenses.id')
            ->get();

        $attachments = DB::table('cash_advance_request_attachments')
            ->where('cash_advance_request_id', $debit->id)
            ->select('id', 'original_name', 'file_path', 'mime_type', 'file_size')
            ->orderBy('id')
            ->get()
            ->map(function ($attachment) use ($request) {
                $url = $this->attachmentRouteUrl('request', (int) $attachment->id, $request);

                return [
                    'id' => $attachment->id,
                    'name' => $attachment->original_name,
                    'url' => $url,
                    'download_url' => $this->attachmentRouteUrl('request', (int) $attachment->id, $request, true),
                    'type' => $attachment->mime_type,
                    'size' => (int) ($attachment->file_size ?? 0),
                ];
            })
            ->values();

        $transactionAttachments = $breakdowns->isEmpty()
            ? collect()
            : DB::table('transaction_attachments')
                ->whereIn('transaction_breakdown_id', $breakdowns->pluck('id')->all())
                ->select('id', 'transaction_breakdown_id', 'file_name', 'file_path', 'file_size', 'file_type', 'created_at')
                ->orderBy('id')
                ->get()
                ->groupBy('transaction_breakdown_id');

        $allocation = $this->breakdownAllocationForRequest((int) $debit->id);

        return response()->json([
            'success' => true,
            'debit' => [
                'id' => $debit->id,
                'employee_name' => $debit->employee_name,
                'expense_date' => Carbon::parse($debit->expense_date)->format('Y-m-d'),
                'parent_amount' => $transactionAmount,
            ],
            'breakdowns' => $breakdowns->map(function ($row) use ($request, $transactionAttachments) {
                $rowAttachments = ($transactionAttachments->get($row->id) ?? collect())
                    ->map(fn ($attachment) => $this->transactionAttachmentPayload($attachment, $request))
                    ->values();

                return [
                    'id' => $row->id,
                    'expense_date' => Carbon::parse($row->expense_date)->format('Y-m-d'),
                    'category_name' => $row->category_name,
                    'transaction_details' => $row->transaction_details,
                    'description' => $row->description,
                    'amount' => (float) $row->amount,
                    'borrow_return_status' => $row->borrow_return_status,
                    'receipt_url' => $row->receipt_path
                        ? $this->attachmentRouteUrl('receipt', (int) $row->id, $request)
                        : null,
                    'receipt_download_url' => $row->receipt_path
                        ? $this->attachmentRouteUrl('receipt', (int) $row->id, $request, true)
                        : null,
                    'attachments' => $rowAttachments,
                ];
            })->values(),
            'attachments' => $attachments,
            'attachment_count' => $attachments->count()
                + $breakdowns->filter(fn ($row) => ! empty($row->receipt_path))->count()
                + $transactionAttachments->flatten(1)->count(),
            'allocation' => $allocation,
        ]);
    }

    public function updateBreakdown(Request $request, int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $validated = $request->validate([
            'expense_date' => 'sometimes|required|date',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'amount' => 'sometimes|required|numeric',
            'transaction_details' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $updatedExpense = DB::transaction(function () use ($id, $validated) {
            $expense = DB::table('liquidation_expenses')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            abort_unless($expense, 404);
            abort_unless($expense->cash_advance_request_id, 422, 'Only breakdown rows linked to a parent transaction can be adjusted.');

            $newCategoryId = (int) ($validated['category_id'] ?? $expense->category_id);

            $oldAmount = round((float) $expense->amount, 2);
            $newAmount = array_key_exists('amount', $validated)
                ? round((float) $validated['amount'], 2)
                : $oldAmount;
            $oldEffectiveAmount = $expense->borrow_return_status === 'not_yet_returned' ? 0.0 : $oldAmount;
            $newEffectiveAmount = $expense->borrow_return_status === 'not_yet_returned' ? 0.0 : $newAmount;

            if ($newAmount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Breakdown amount must be greater than zero.',
                ]);
            }

            if (abs($oldEffectiveAmount) >= 0.01 || abs($newEffectiveAmount) >= 0.01) {
                app(AccountingBudgetService::class)->replaceUsage(
                    (int) $expense->cash_advance_request_id,
                    (int) $expense->id,
                    $oldEffectiveAmount,
                    $newEffectiveAmount
                );
            }

            DB::table('liquidation_expenses')
                ->where('id', $id)
                ->update([
                    'expense_date' => $validated['expense_date'] ?? $expense->expense_date,
                    'category_id' => $newCategoryId,
                    'transaction_details' => $validated['transaction_details'] ?? $expense->transaction_details,
                    'description' => $validated['description'] ?? $expense->description,
                    'amount' => $newAmount,
                    'updated_at' => now(),
                ]);

            $updatedExpense = DB::table('liquidation_expenses')
                ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
                ->where('liquidation_expenses.id', $id)
                ->select(
                    'liquidation_expenses.id',
                    'liquidation_expenses.cash_advance_request_id',
                    'liquidation_expenses.expense_date',
                    'liquidation_expenses.transaction_details',
                    'liquidation_expenses.description',
                    'liquidation_expenses.amount',
                    'liquidation_expenses.borrow_return_status',
                    DB::raw('categories.particulars_category as category_name')
                )
                ->first();

            $allocation = app(AccountingBudgetService::class)
                ->allocationForRequest((int) $expense->cash_advance_request_id);

            return compact('updatedExpense', 'allocation');
        });

        AccountingMonthlyBalance::syncStoredMonth($updatedExpense['updatedExpense']->expense_date);

        return response()->json([
            'success' => true,
            'message' => 'Breakdown updated successfully.',
            'expense' => $updatedExpense['updatedExpense'],
            'allocation' => $updatedExpense['allocation'],
        ]);
    }

    public function deleteBreakdown(int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $deleted = DB::transaction(function () use ($id) {
            $expense = DB::table('liquidation_expenses')
                ->where('liquidation_expenses.id', $id)
                ->select(
                    'liquidation_expenses.id',
                    'liquidation_expenses.cash_advance_request_id',
                    'liquidation_expenses.expense_date',
                    'liquidation_expenses.amount',
                    'liquidation_expenses.borrow_return_status'
                )
                ->lockForUpdate()
                ->first();

            abort_unless($expense, 404);

            $allocation = ($expense->cash_advance_request_id && $expense->borrow_return_status !== 'not_yet_returned')
                ? app(AccountingBudgetService::class)->removeUsage(
                    (int) $expense->cash_advance_request_id,
                    (int) $expense->id,
                    (float) $expense->amount
                )
                : null;

            DB::table('transaction_attachments')
                ->where('transaction_breakdown_id', $expense->id)
                ->select('file_path')
                ->get()
                ->each(fn ($attachment) => Storage::disk('public')->delete($attachment->file_path));

            DB::table('liquidation_expenses')->where('id', $id)->delete();

            if ($expense->cash_advance_request_id) {
                $allocation = app(AccountingBudgetService::class)
                    ->allocationForRequest((int) $expense->cash_advance_request_id);
            }

            return compact('expense', 'allocation');
        });

        AccountingMonthlyBalance::syncStoredMonth($deleted['expense']->expense_date);

        return response()->json([
            'success' => true,
            'message' => 'Breakdown deleted successfully.',
            'allocation' => $deleted['allocation'],
        ]);
    }

    public function deleteTransactionAttachment(int $id): JsonResponse
    {
        $this->authorizeAccounting();

        $attachment = DB::table('transaction_attachments')
            ->where('id', $id)
            ->first();

        abort_unless($attachment, 404);

        Storage::disk('public')->delete($attachment->file_path);
        DB::table('transaction_attachments')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
        ]);
    }

    public function serveAttachment(Request $request, string $type, int $id)
    {
        $this->authorizeAccounting();

        $attachment = match ($type) {
            'request' => DB::table('cash_advance_request_attachments')
                ->where('id', $id)
                ->select('original_name as name', 'file_path as path', 'mime_type as type')
                ->first(),
            'transaction' => DB::table('transaction_attachments')
                ->where('id', $id)
                ->select('file_name as name', 'file_path as path', 'file_type as type')
                ->first(),
            'receipt' => DB::table('liquidation_expenses')
                ->where('id', $id)
                ->whereNotNull('receipt_path')
                ->select('receipt_path as path')
                ->first(),
            default => null,
        };

        abort_unless($attachment && ! empty($attachment->path), 404);
        abort_unless(Storage::disk('public')->exists($attachment->path), 404);

        $path = Storage::disk('public')->path($attachment->path);
        $fileName = $this->downloadFileName($attachment->name ?? basename($attachment->path));
        $mimeType = ($attachment->type ?? null) ?: Storage::disk('public')->mimeType($attachment->path) ?: 'application/octet-stream';

        if ($request->boolean('download')) {
            return response()->download($path, $fileName, ['Content-Type' => $mimeType]);
        }

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
        ]);
    }

    private function breakdownAllocationForRequest(int $cashAdvanceRequestId): array
    {
        return app(AccountingBudgetService::class)->allocationForRequest($cashAdvanceRequestId);
    }

    private function storeCashAdvanceRequestAttachments(
        Request $request,
        int $cashAdvanceRequestId,
        Carbon $expenseDate,
        ?string $purpose,
        ?int $employeeUserId
    ): int {
        $files = $request->file('attachments', []);

        if (! is_array($files) || count($files) === 0) {
            return 0;
        }

        $employeeNumber = $this->employeeNumberForUser($employeeUserId);
        $now = now();
        $rows = [];

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $fileName = $this->descriptiveAttachmentFileName($file, $expenseDate, $purpose, $employeeNumber);
            $path = $file->storeAs('cash-advance-attachments', $this->storedAttachmentFileName($fileName), 'public');

            $rows[] = [
                'cash_advance_request_id' => $cashAdvanceRequestId,
                'original_name' => $fileName,
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows) {
            DB::table('cash_advance_request_attachments')->insert($rows);
        }

        return count($rows);
    }

    private function storeTransactionAttachments(
        Request $request,
        int $transactionBreakdownId,
        ?Carbon $expenseDate = null,
        ?string $purpose = null,
        ?int $employeeUserId = null
    ): int
    {
        $files = $request->file('attachments', []);

        if (! is_array($files) || count($files) === 0) {
            return 0;
        }

        $now = now();
        $rows = [];

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $fileName = $this->descriptiveAttachmentFileName(
                $file,
                $expenseDate ?? now(),
                $purpose,
                $this->employeeNumberForUser($employeeUserId)
            );
            $path = $file->storeAs('transaction-attachments', $this->storedAttachmentFileName($fileName), 'public');

            $rows[] = [
                'transaction_breakdown_id' => $transactionBreakdownId,
                'file_name' => $fileName,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => Auth::id(),
                'created_at' => $now,
            ];
        }

        if ($rows) {
            DB::table('transaction_attachments')->insert($rows);
        }

        return count($rows);
    }

    private function transactionAttachmentPayload(object $attachment, ?Request $request = null): array
    {
        $url = $this->attachmentRouteUrl('transaction', (int) $attachment->id, $request);

        return [
            'id' => $attachment->id,
            'name' => $attachment->file_name,
            'url' => $url,
            'download_url' => $this->attachmentRouteUrl('transaction', (int) $attachment->id, $request, true),
            'size' => (int) $attachment->file_size,
            'type' => $attachment->file_type,
            'created_at' => $attachment->created_at,
        ];
    }

    private function attachmentRouteUrl(string $type, int $id, ?Request $request = null, bool $download = false): string
    {
        $path = route('accounting.attachment', ['type' => $type, 'id' => $id], false);
        $url = $request
            ? rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . $path
            : url($path);

        return $download ? $url . '?download=1' : $url;
    }

    private function publicStorageUrl(?string $path, ?Request $request = null): ?string
    {
        if (! $path) {
            return null;
        }

        if (! $request) {
            return Storage::disk('public')->url($path);
        }

        $baseUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');

        return $baseUrl . '/storage/' . ltrim($path, '/');
    }

    private function sanitizeAttachmentFileName(string $fileName): string
    {
        $fileName = basename($fileName);
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $baseName = Str::slug($baseName ?: 'attachment');
        $baseName = $baseName ?: 'attachment';

        return trim($baseName . ($extension ? '.' . $extension : ''));
    }

    private function descriptiveAttachmentFileName($file, Carbon $expenseDate, ?string $purpose, ?string $employeeNumber): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $purposeSlug = Str::limit(Str::slug($purpose ?: 'receipt') ?: 'receipt', 80, '');
        $baseParts = [
            $expenseDate->format('Y-m-d'),
            $purposeSlug,
            Str::slug($employeeNumber ?: 'no-employee-number') ?: 'no-employee-number',
        ];

        return implode('-', $baseParts) . ($extension ? '.' . $extension : '');
    }

    private function storedAttachmentFileName(string $fileName): string
    {
        $safeName = $this->sanitizeAttachmentFileName($fileName);
        $extension = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));
        $baseName = pathinfo($safeName, PATHINFO_FILENAME) ?: 'attachment';

        return $baseName . '-' . Str::uuid()->toString() . ($extension ? '.' . $extension : '');
    }

    private function employeeNumberForUser(?int $employeeUserId): ?string
    {
        if (! $employeeUserId) {
            return null;
        }

        return DB::table('users')->where('id', $employeeUserId)->value('employee_id');
    }

    private function defaultParticularIdForCategory(int $categoryId): int
    {
        if (! Schema::hasTable('particulars')) {
            throw ValidationException::withMessages([
                'category_id' => 'Particulars table is required before saving breakdown expenses.',
            ]);
        }

        $query = DB::table('particulars');
        if (Schema::hasColumn('particulars', 'category_id')) {
            $query->where('category_id', $categoryId);
        }

        $particularId = $query->orderBy('id')->value('id');
        if ($particularId) {
            return (int) $particularId;
        }

        $categoryName = DB::table('categories')
            ->where('id', $categoryId)
            ->value('particulars_category') ?: 'General';

        $payload = [];

        if (Schema::hasColumn('particulars', 'category_id')) {
            $payload['category_id'] = $categoryId;
        }

        if (Schema::hasColumn('particulars', 'particular_name')) {
            $payload['particular_name'] = $categoryName;
        }

        if (Schema::hasColumn('particulars', 'description')) {
            $payload['description'] = null;
        }

        if (Schema::hasColumn('particulars', 'created_at')) {
            $payload['created_at'] = now();
        }

        if (Schema::hasColumn('particulars', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        return (int) DB::table('particulars')->insertGetId($payload);
    }

    private function downloadFileName(?string $fileName): string
    {
        return $this->sanitizeAttachmentFileName($fileName ?: 'attachment') ?: 'attachment';
    }

    private function authorizeAccounting(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->role_id === 3, 403);
    }
}
