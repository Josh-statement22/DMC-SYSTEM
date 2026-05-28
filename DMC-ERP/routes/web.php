<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CashAdvanceMonthlyBalance;
use App\Models\CashAdvanceRequest;
use App\Support\AccountingMonthlyBalance;

if (!function_exists('redirect_if_role_not_allowed')) {
    function redirect_if_role_not_allowed(array $allowedRoleIds)
    {
        $user = Auth::user();

        if ($user && in_array((int) $user->role_id, $allowedRoleIds, true)) {
            return null;
        }

        if (!$user) {
            return redirect()->route('login');
        }

        return match ((int) $user->role_id) {
            1 => redirect('/superadmin/dashboard'),
            2 => redirect('/admin/dashboard'),
            3 => redirect('/accounting/dashboard'),
            default => abort(403),
        };
    }
}

if (!function_exists('buildLiquidationTrackingRecords')) {
    function buildLiquidationTrackingRecords(?int $employeeId = null)
    {
        $expenseTotalsSubquery = DB::raw('(select liquidation_id, sum(amount) as total_expended, count(*) as expense_count from liquidation_expenses group by liquidation_id) as expense_totals');

        $records = DB::table('liquidations')
            ->join('users', 'liquidations.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin($expenseTotalsSubquery, 'liquidations.id', '=', 'expense_totals.liquidation_id')
            ->select(
                'liquidations.id',
                'liquidations.user_id',
                'users.employee_id',
                'users.name',
                DB::raw("COALESCE(roles.name, 'Employee') as role_name"),
                'liquidations.cutoff_period',
                'liquidations.status',
                'liquidations.remarks',
                'liquidations.document_path',
                'liquidations.submitted_at',
                'liquidations.approved_at',
                'liquidations.created_at',
                'liquidations.amount as balance_sent',
                DB::raw('COALESCE(expense_totals.total_expended, 0) as total_expended'),
                DB::raw('COALESCE(expense_totals.expense_count, 0) as expense_count')
            )
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('liquidations.user_id', $employeeId);
            })
            ->orderByDesc(DB::raw('COALESCE(liquidations.submitted_at, liquidations.approved_at, liquidations.created_at)'))
            ->get();

        $expenseRowsByLiquidation = DB::table('liquidation_expenses')
            ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
            ->select(
                'liquidation_expenses.liquidation_id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'liquidation_expenses.receipt_path',
                'categories.particulars_category as category_name'
            )
            ->orderByDesc('liquidation_expenses.expense_date')
            ->get()
            ->groupBy('liquidation_id');

        $cashAdvanceRows = DB::table('cash_advance_requests')
            ->select(
                'requester_id',
                DB::raw("DATE_FORMAT(COALESCE(released_at, reviewed_at, request_date), '%Y-%m') as month_key"),
                DB::raw('SUM(COALESCE(approved_amount, requested_amount, 0)) as total_released')
            )
            ->where('status', 'approved')
            ->groupBy('requester_id', 'month_key')
            ->get();

        $releasedByUserMonth = $cashAdvanceRows->mapWithKeys(function ($row) {
            return [((int) $row->requester_id) . '|' . $row->month_key => (float) $row->total_released];
        });

        return $records->map(function ($row) use ($expenseRowsByLiquidation, $releasedByUserMonth) {
            $recordDate = \Carbon\Carbon::parse($row->submitted_at ?? $row->approved_at ?? $row->created_at);
            $weekStart = $recordDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $weekEnd = $recordDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $monthKey = $recordDate->format('Y-m');
            $releasedKey = ((int) $row->user_id) . '|' . $monthKey;
            $releasedAmount = (float) ($releasedByUserMonth[$releasedKey] ?? 0);
            $balanceSent = $releasedAmount > 0 ? $releasedAmount : (float) $row->balance_sent;
            $expenseBreakdown = ($expenseRowsByLiquidation->get($row->id) ?? collect())->map(function ($expense) {
                return [
                    'expense_date' => $expense->expense_date,
                    'category' => $expense->category_name,
                    'details' => trim((string) $expense->transaction_details),
                    'description' => trim((string) $expense->description),
                    'amount' => (float) $expense->amount,
                    'receipt_path' => $expense->receipt_path,
                    'receipt_url' => $expense->receipt_path ? Storage::disk('public')->url($expense->receipt_path) : null,
                    'label' => trim($expense->category_name . ' - ' . $expense->transaction_details . ' (₱' . number_format((float) $expense->amount, 2) . ')'),
                ];
            })->values();

            return [
                'id' => (int) $row->id,
                'user_id' => (int) $row->user_id,
                'employee_id' => $row->employee_id,
                'name' => $row->name,
                'role_name' => $row->role_name ?: 'Employee',
                'cutoff_period' => $row->cutoff_period,
                'status' => $row->status,
                'remarks' => $row->remarks,
                'document_path' => $row->document_path,
                'submitted_at' => $row->submitted_at,
                'approved_at' => $row->approved_at,
                'record_date' => $recordDate->format('Y-m-d'),
                'record_timestamp' => $recordDate->toIso8601String(),
                'period_month_key' => $monthKey,
                'period_month_label' => $recordDate->format('F Y'),
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d'),
                'week_label' => $weekStart->format('M j') . '–' . $weekEnd->format('M j'),
                'balance_sent' => $balanceSent,
                'total_expenses' => (float) $row->total_expended,
                'remaining_balance' => $balanceSent - (float) $row->total_expended,
                'expense_count' => (int) $row->expense_count,
                'expense_breakdown' => $expenseBreakdown,
                'search_text' => strtolower(trim(
                    ($row->employee_id ?? '') . ' ' .
                    ($row->name ?? '') . ' ' .
                    ($row->role_name ?? '') . ' ' .
                    ($row->cutoff_period ?? '') . ' ' .
                    ($row->status ?? '')
                )),
            ];
        })->values();
    }
}

/*
|--------------------------------------------------------------------------
| Login Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');


/*
|--------------------------------------------------------------------------
| Login Handler
|--------------------------------------------------------------------------
*/

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'employee_id' => 'required',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Redirect based on role_id
        if ($user->role_id == 1) {
            return redirect('/superadmin/dashboard');
        }
        
        if ($user->role_id == 2) {
            return redirect('/admin/dashboard');
        }

        if ($user->role_id == 3) {
            return redirect('/accounting/dashboard');
        }
        
        // If unknown role
        Auth::logout();
        return back()->with('error', 'Unauthorized role');
    }

    return back()->with('error', 'Invalid credentials');
});


/*
|--------------------------------------------------------------------------
| Superadmin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/superadmin/dashboard', function (Request $request) {
    $perPage = 10;
    $page = max(1, (int) $request->query('page', 1));
    $search = trim((string) $request->query('search', ''));

    $userQuery = \App\Models\User::with('role')->orderBy('name');

    if ($search !== '') {
        $userQuery->where(function ($query) use ($search) {
            $query->where('id', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('role', function ($roleQuery) use ($search) {
                    $roleQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    $allUsers = $userQuery->get();
    $filteredUsers = $allUsers->count();
    $totalUsers = \App\Models\User::count();
    $totalPages = max(1, (int) ceil($filteredUsers / $perPage));
    $page = min($page, $totalPages);
    $startIndex = ($page - 1) * $perPage;
    $users = $allUsers->slice($startIndex, $perPage)->values();

    return view('superadmin.dashboard', [
        'users' => $users,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalUsers' => $totalUsers,
        'filteredUsers' => $filteredUsers,
        'search' => $search,
        'superadminCount' => \App\Models\User::where('role_id', 1)->count(),
        'adminCount' => \App\Models\User::where('role_id', 2)->count(),
    ]);
})->middleware('auth')->name('superadmin.dashboard');

Route::get('/superadmin/users/next-employee-id', function () {
    $currentYear = date('Y');
    $lastUser = \App\Models\User::orderByRaw("CAST(employee_id AS UNSIGNED) DESC")->first();
    
    if (!$lastUser) {
        // If no users exist, start with first ID
        $nextId = $currentYear . '00001';
    } else {
        $lastEmployeeId = $lastUser->employee_id;
        $lastYear = substr($lastEmployeeId, 0, 4);
        $lastSequence = (int) substr($lastEmployeeId, 4);
        
        if ($lastYear == $currentYear) {
            // Same year, increment the sequence
            $nextSequence = $lastSequence + 1;
        } else {
            // Different year, reset sequence to 1
            $nextSequence = 1;
        }
        
        $nextId = $currentYear . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
    }
    
    return response()->json(['employee_id' => $nextId]);
})->middleware('auth')->name('superadmin.next.employee.id');

Route::post('/superadmin/users/store', function (Request $request) {
    $validated = $request->validate([
        'employee_id' => 'required|unique:users,employee_id',
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'password' => 'required|min:6',
        'role_id' => 'required|exists:roles,id'
    ]);

    $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

    \App\Models\User::create($validated);

    return redirect()->route('superadmin.dashboard')->with('success', 'User created successfully');
})->middleware('auth')->name('superadmin.users.store');

Route::delete('/superadmin/users/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);
    $userName = $user->name;
    $user->delete();

    return redirect()->route('superadmin.dashboard')->with('success', "User '{$userName}' has been removed successfully");
})->middleware('auth')->name('superadmin.users.destroy');

Route::put('/superadmin/users/{id}', function (Request $request, $id) {
    $user = \App\Models\User::findOrFail($id);

    $rules = [
        'employee_id' => 'required|unique:users,employee_id,' . $id,
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $id,
        'role_id' => 'required|exists:roles,id'
    ];

    // Only validate password if provided
    if ($request->filled('password')) {
        $rules['password'] = 'required|min:6|confirmed';
    }

    $validated = $request->validate($rules);

    // Hash password if provided
    if ($request->filled('password')) {
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('superadmin.dashboard')->with('success', "User '{$user->name}' has been updated successfully");
})->middleware('auth')->name('superadmin.users.update');


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');

Route::get('/admin/dashboard/summary', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $viewMode = in_array($request->query('view_mode'), ['week', 'month']) ? $request->query('view_mode') : 'week';
    $start = $request->query('start_date') ? \Carbon\Carbon::parse($request->query('start_date'))->startOfDay() : \Carbon\Carbon::now()->startOfWeek();
    $end = $request->query('end_date') ? \Carbon\Carbon::parse($request->query('end_date'))->endOfDay() : \Carbon\Carbon::now()->endOfWeek();

    $baseQuery = CashAdvanceRequest::query()
        ->whereRaw("DATE(COALESCE(released_at, reviewed_at, request_date)) BETWEEN ? AND ?", [
            $start->toDateString(),
            $end->toDateString(),
        ]);

    $totalRequests = (clone $baseQuery)->count();
    $approvedCount = (clone $baseQuery)->whereRaw('LOWER(status) = ?', ['approved'])->count();
    $pendingCount = (clone $baseQuery)->whereRaw('LOWER(status) = ?', ['pending'])->count();
    $rejectedCount = (clone $baseQuery)->whereRaw('LOWER(status) = ?', ['rejected'])->count();
    $requestedAmount = (clone $baseQuery)->sum('requested_amount');
    $approvedAmount = (clone $baseQuery)->whereRaw('LOWER(status) = ?', ['approved'])->sum('approved_amount');
    $releasedAmount = (clone $baseQuery)->whereNotNull('released_at')->sum('approved_amount');

    $recentRequests = (clone $baseQuery)
        ->with('requester')
        ->orderByDesc('submitted_at')
        ->limit(5)
        ->get([
            'id',
            'requester_id',
            'requested_amount',
            'approved_amount',
            'purpose',
            'status',
            'request_date',
            'date_needed',
            'reviewed_at',
            'released_at',
        ])
        ->map(function ($request) {
            return [
                'id' => $request->id,
                'requester_name' => $request->requester?->name ?? 'Unknown',
                'status' => $request->status,
                'requested_amount' => $request->requested_amount,
                'approved_amount' => $request->approved_amount,
                'request_date' => optional($request->request_date)?->toDateString(),
                'date_needed' => optional($request->date_needed)?->toDateString(),
                'reviewed_at' => optional($request->reviewed_at)?->toDateTimeString(),
                'released_at' => optional($request->released_at)?->toDateTimeString(),
                'purpose' => $request->purpose,
            ];
        });

    return response()->json([
        'view_mode' => $viewMode,
        'start_date' => $start->toDateString(),
        'end_date' => $end->toDateString(),
        'total_requests' => $totalRequests,
        'approved_count' => $approvedCount,
        'pending_count' => $pendingCount,
        'rejected_count' => $rejectedCount,
        'requested_amount' => $requestedAmount,
        'approved_amount' => $approvedAmount,
        'released_amount' => $releasedAmount,
        'recent_requests' => $recentRequests,
    ]);
})->middleware('auth')->name('admin.dashboard.summary');

/*
|--------------------------------------------------------------------------
| Accounting Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/accounting/dashboard', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $currentMonth = now();

    $employees = DB::table('users')
        ->select('id', 'name', 'employee_id')
        ->where('role_id', 2)
        ->orderBy('name')
        ->get();

    $currentMonthlyBalance = (object) AccountingMonthlyBalance::forMonth($currentMonth);

    return view('accounting.dashboard', compact('employees', 'currentMonthlyBalance'));
})->middleware('auth')->name('accounting.dashboard');

Route::get('/accounting/cash-advance/monthly-balance', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $monthKey = (string) $request->query('month_key', now()->format('Y-m'));
    $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey)->startOfMonth();

    $balance = AccountingMonthlyBalance::forMonth($monthDate);

    return response()->json([
        'balance' => $balance,
    ]);
})->middleware('auth')->name('accounting.cash-advance.monthly-balance.show');

Route::post('/accounting/cash-advance/monthly-balance', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $validated = $request->validate([
        'month_key' => 'nullable|date_format:Y-m',
        'opening_balance' => 'required|numeric|min:0',
    ]);

    $monthDate = isset($validated['month_key'])
        ? \Carbon\Carbon::createFromFormat('Y-m', $validated['month_key'])->startOfMonth()
        : now()->startOfMonth();

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
        'message' => 'Monthly opening balance saved successfully.',
        'balance' => $balance,
    ]);
})->middleware('auth')->name('accounting.cash-advance.monthly-balance.store');

Route::get('/accounting/liquidation', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $liquidationRecords = buildLiquidationTrackingRecords();

    return view('accounting.liquidation', compact('liquidationRecords'));
})->middleware('auth')->name('accounting.liquidation');

Route::get('/accounting/liquidation/submitted', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $liquidations = buildLiquidationTrackingRecords()
        ->filter(fn ($record) => strtolower((string) ($record['status'] ?? '')) === 'submitted'
            || (!empty($record['submitted_at']) && strtolower((string) ($record['status'] ?? '')) === 'pending'))
        ->values();

    return response()->json([
        'liquidations' => $liquidations,
    ]);
})->middleware('auth')->name('accounting.liquidation.submitted');

Route::get('/accounting/liquidation/employee/{employee}', function ($employee) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $selectedEmployee = DB::table('users')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->select(
            'users.id',
            'users.employee_id',
            'users.name',
            DB::raw("COALESCE(roles.name, 'Employee') as role_name")
        )
        ->where('users.id', (int) $employee)
        ->where('users.role_id', 2)
        ->first();

    abort_if(!$selectedEmployee, 404);

    $liquidationRecords = buildLiquidationTrackingRecords((int) $selectedEmployee->id);

    return view('accounting.liquidation', compact('liquidationRecords', 'selectedEmployee'));
})->middleware('auth')->name('accounting.liquidation.employee');

Route::get('/accounting/liquidate-expenses', [\App\Http\Controllers\AccountingController::class, 'liquidateExpenses'])->middleware('auth')->name('accounting.liquidate-expenses');
Route::post('/accounting/liquidate-expenses/expense', [\App\Http\Controllers\AccountingController::class, 'storeExpense'])->middleware('auth')->name('accounting.store-expense');
Route::get('/accounting/liquidate-expenses/expense/{id}/breakdown', [\App\Http\Controllers\AccountingController::class, 'showExpenseBreakdown'])->middleware('auth')->name('accounting.show-expense-breakdown');
Route::post('/accounting/liquidate-expenses/opening-balance', [\App\Http\Controllers\AccountingController::class, 'updateOpeningBalance'])->middleware('auth')->name('accounting.update-opening-balance');
Route::delete('/accounting/liquidate-expenses/expense/{id}', [\App\Http\Controllers\AccountingController::class, 'deleteExpense'])->middleware('auth')->name('accounting.delete-expense');

Route::get('/accounting/summary', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $employees = DB::table('users')
        ->where('role_id', 2)
        ->select('id', 'name', 'employee_id')
        ->orderBy('name')
        ->get();

    $categories = DB::table('categories')
        ->select('id', 'particulars_category')
        ->orderBy('particulars_category')
        ->get();

    return view('accounting.summary', compact('employees', 'categories'));
})->middleware('auth')->name('accounting.summary');

Route::get('/accounting/summary/data', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $page = max(1, (int) $request->query('page', 1));
    $perPage = 20;
    $showAll = $request->boolean('all', false);
    $employeeId = $request->query('employee_id');
    $categoryId = $request->query('category_id');
    $fromDate = $request->query('from_date');
    $toDate = $request->query('to_date');

    // Build query for liquidation expenses
    $expenseQuery = DB::table('liquidation_expenses')
        ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
        ->join('users', 'liquidations.user_id', '=', 'users.id')
        ->leftJoin('categories as direct_categories', 'liquidation_expenses.category_id', '=', 'direct_categories.id');

    // Apply filters
    if ($employeeId) {
        $expenseQuery->where('liquidations.user_id', $employeeId);
    }

    if ($fromDate) {
        $expenseQuery->where('liquidation_expenses.expense_date', '>=', $fromDate);
    }

    if ($toDate) {
        $expenseQuery->where('liquidation_expenses.expense_date', '<=', $toDate);
    }

    if ($categoryId) {
        $expenseQuery->where('liquidation_expenses.category_id', $categoryId);
    }

    // Get summary data before pagination
    $summaryQuery = clone $expenseQuery;
    $summary = $summaryQuery->selectRaw('
        COUNT(*) as total_count,
        SUM(CASE WHEN liquidation_expenses.transaction_type = "credit" THEN liquidation_expenses.amount ELSE 0 END) as total_credits,
        SUM(CASE WHEN liquidation_expenses.transaction_type = "debit" THEN liquidation_expenses.amount ELSE 0 END) as total_debits
    ')->first();

    $summary->total_credits = $summary->total_credits ?? 0;
    $summary->total_debits = $summary->total_debits ?? 0;
    $summary->net_amount = $summary->total_debits - $summary->total_credits;

    // Get paginated expenses
    $totalExpenses = (clone $expenseQuery)->count();
    $totalPages = max(1, (int) ceil($totalExpenses / $perPage));
    $page = min($page, $totalPages);

    $expensesQuery = (clone $expenseQuery)
        ->select(
            'liquidation_expenses.expense_date',
            'users.name as employee_name',
            'liquidation_expenses.transaction_type',
            'liquidation_expenses.description',
            'liquidation_expenses.transaction_details',
            'liquidation_expenses.amount',
            'liquidation_expenses.transaction_details as particular_name',
            'direct_categories.particulars_category as category_name',
            DB::raw('CASE WHEN liquidation_expenses.transaction_type = "credit" THEN liquidation_expenses.amount ELSE 0 END as credit'),
            DB::raw('CASE WHEN liquidation_expenses.transaction_type = "debit" THEN liquidation_expenses.amount ELSE 0 END as debit')
        )
        ->orderBy('liquidation_expenses.expense_date');

    $expenses = $showAll
        ? $expensesQuery->get()
        : $expensesQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();

    return response()->json([
        'expenses' => $expenses,
        'summary' => $summary,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $totalExpenses,
            'per_page' => $perPage,
        ],
    ]);
})->middleware('auth')->name('accounting.summary.data');

Route::get('/accounting/cash-advance/requests', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $requests = DB::table('cash_advance_requests')
        ->join('users', 'cash_advance_requests.requester_id', '=', 'users.id')
        ->select(
            'cash_advance_requests.id',
            'cash_advance_requests.reference_no',
            'cash_advance_requests.requester_id',
            'users.employee_id',
            'users.name as employee_name',
            'cash_advance_requests.requested_amount',
            'cash_advance_requests.approved_amount',
            'cash_advance_requests.purpose',
            'cash_advance_requests.request_date',
            'cash_advance_requests.date_needed',
            'cash_advance_requests.status',
            'cash_advance_requests.accounting_remarks',
            'cash_advance_requests.submitted_at',
            'cash_advance_requests.reviewed_at',
            'cash_advance_requests.released_at',
            'cash_advance_requests.sent_by_name',
            'cash_advance_requests.approved_by_name'
        )
        ->orderByDesc('cash_advance_requests.created_at')
        ->limit(50)
        ->get();

    return response()->json(['requests' => $requests]);
})->middleware('auth')->name('accounting.cash-advance.requests.index');

Route::get('/accounting/cash-advance/requests/stream', function () {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    return response()->stream(function () {
        @set_time_limit(0);
        @ini_set('zlib.output_compression', '0');
        @ini_set('implicit_flush', '1');

        while (ob_get_level() > 0) {
            @ob_end_flush();
        }

        echo "retry: 2000\n\n";
        @flush();

        $lastSignature = null;
        $startedAt = time();

        while (!connection_aborted() && time() - $startedAt < 300) {
            $summary = DB::table('cash_advance_requests')
                ->selectRaw("
                    COUNT(*) as total_count,
                    SUM(CASE WHEN LOWER(status) = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    MAX(UNIX_TIMESTAMP(updated_at)) as latest_update
                ")
                ->first();

            $latestRequest = DB::table('cash_advance_requests')
                ->join('users as requesters', 'cash_advance_requests.requester_id', '=', 'requesters.id')
                ->select(
                    'cash_advance_requests.id',
                    'cash_advance_requests.requested_amount',
                    'cash_advance_requests.status',
                    'cash_advance_requests.purpose',
                    'cash_advance_requests.updated_at',
                    'requesters.name as employee_name'
                )
                ->orderByDesc('cash_advance_requests.updated_at')
                ->first();

            $signature = implode('|', [
                (int) ($summary->total_count ?? 0),
                (int) ($summary->pending_count ?? 0),
                (int) ($summary->latest_update ?? 0),
            ]);

            if ($signature !== $lastSignature) {
                echo "event: cash-advance-requests-updated\n";
                echo 'data: ' . json_encode([
                    'signature' => $signature,
                    'pending_count' => (int) ($summary->pending_count ?? 0),
                    'latest_request' => $latestRequest,
                    'timestamp' => now()->toIso8601String(),
                ]) . "\n\n";

                $lastSignature = $signature;
            } else {
                echo ": ping\n\n";
            }

            @flush();
            sleep(5);  // Increased from 1 second to reduce database polling pressure
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache, no-transform',
        'X-Accel-Buffering' => 'no',
        'Connection' => 'keep-alive',
    ]);
})->middleware('auth')->name('accounting.cash-advance.requests.stream');

Route::post('/cash-advance/requests', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([2])) {
        return $redirect;
    }

    $validated = $request->validate([
        'purpose' => 'required|string|max:2000',
        'requested_amount' => 'required|numeric|min:0.01',
        'date_needed' => 'nullable|date',
    ]);

    $requestDate = now();
    $referenceNo = 'CA-' . $requestDate->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

    $requestId = DB::table('cash_advance_requests')->insertGetId([
        'reference_no' => $referenceNo,
        'requester_id' => Auth::id(),
        'requested_amount' => $validated['requested_amount'],
        'purpose' => trim($validated['purpose']),
        'request_date' => $requestDate->toDateString(),
        'date_needed' => $validated['date_needed'] ?? $requestDate->toDateString(),
        'status' => 'pending',
        'submitted_at' => $requestDate,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('cash_advance_request_audits')->insert([
        'cash_advance_request_id' => $requestId,
        'action' => 'submitted',
        'old_status' => null,
        'new_status' => 'pending',
        'remarks' => null,
        'acted_by' => Auth::id(),
        'meta' => json_encode([
            'requested_amount' => (float) $validated['requested_amount'],
            'purpose' => trim($validated['purpose']),
        ]),
        'acted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Get the full request data to broadcast
    $newRequest = DB::table('cash_advance_requests')
        ->where('id', $requestId)
        ->first();

    // Broadcast the event to accounting department (non-blocking)
    try {
        \App\Events\CashAdvanceRequestSubmitted::dispatch($newRequest, Auth::id());
    } catch (\Exception $e) {
        // Log the error but don't fail the request - data was already saved
        \Log::error('Failed to broadcast cash advance request: ' . $e->getMessage());
    }

    return response()->json([
        'message' => 'Cash advance request submitted successfully.',
        'id' => $requestId,
    ]);
})->middleware('auth')->name('cash-advance.requests.store');

Route::get('/cash-advance/requests/my', function () {
    if ($redirect = redirect_if_role_not_allowed([2])) {
        return $redirect;
    }
    $start = request()->query('start_date');
    $end = request()->query('end_date');

    $requests = DB::table('cash_advance_requests')
        ->leftJoin('users as reviewers', 'cash_advance_requests.reviewed_by', '=', 'reviewers.id')
        ->select(
            'cash_advance_requests.id',
            'cash_advance_requests.reference_no',
            'cash_advance_requests.requested_amount',
            'cash_advance_requests.approved_amount',
            'cash_advance_requests.purpose',
            'cash_advance_requests.request_date',
            'cash_advance_requests.date_needed',
            'cash_advance_requests.status',
            'cash_advance_requests.accounting_remarks',
            'cash_advance_requests.submitted_at',
            'cash_advance_requests.reviewed_at',
            'cash_advance_requests.released_at',
            DB::raw('COALESCE(cash_advance_requests.released_at, cash_advance_requests.reviewed_at, cash_advance_requests.submitted_at, cash_advance_requests.request_date) as sent_date'),
            DB::raw('COALESCE(cash_advance_requests.sent_by_name, cash_advance_requests.approved_by_name, reviewers.name) as reviewer_name'),
            DB::raw('COALESCE(cash_advance_requests.approved_by_name, reviewers.name) as approved_by_name'),
            DB::raw('COALESCE(cash_advance_requests.sent_by_name, reviewers.name) as sent_by_name')
        )
        ->where('cash_advance_requests.requester_id', Auth::id())
        ->when($start || $end, function ($query) use ($start, $end) {
            $col = DB::raw('COALESCE(cash_advance_requests.released_at, cash_advance_requests.reviewed_at, cash_advance_requests.submitted_at, cash_advance_requests.request_date)');
            if ($start && $end) {
                $query->whereBetween($col, [$start, $end]);
            } elseif ($start) {
                $query->where($col, '>=', $start);
            } elseif ($end) {
                $query->where($col, '<=', $end);
            }
        })
        ->orderByDesc(DB::raw('COALESCE(cash_advance_requests.submitted_at, cash_advance_requests.created_at)'))
        ->get();

    return response()->json(['requests' => $requests]);
})->middleware('auth')->name('cash-advance.requests.my');

Route::patch('/accounting/cash-advance/requests/{requestId}/decision', function (Request $request, $requestId) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $validated = $request->validate([
        'decision' => 'required|in:approved,rejected',
        'accounting_remarks' => 'nullable|string|max:2000',
        'approved_amount' => 'nullable|numeric|min:0.01',
    ]);

    $cashAdvanceRequest = DB::table('cash_advance_requests')
        ->where('id', (int) $requestId)
        ->first();

    abort_if(!$cashAdvanceRequest, 404);

    $oldStatus = (string) $cashAdvanceRequest->status;
    $newStatus = $validated['decision'];
    $actorName = (string) (Auth::user()->name ?? 'Accounting Staff');
    $approvedAmount = $newStatus === 'approved'
        ? (float) ($validated['approved_amount'] ?? $cashAdvanceRequest->approved_amount ?? $cashAdvanceRequest->requested_amount)
        : null;

    DB::table('cash_advance_requests')
        ->where('id', (int) $requestId)
        ->update([
            'status' => $newStatus,
            'approved_amount' => $approvedAmount,
            'accounting_remarks' => $validated['accounting_remarks'] ?? null,
            'reviewed_by' => Auth::id(),
            'approved_by_name' => $newStatus === 'approved' ? $actorName : null,
            'sent_by_name' => $newStatus === 'approved' ? $actorName : null,
            'reviewed_at' => now(),
            'released_at' => $newStatus === 'approved' ? now() : null,
            'updated_at' => now(),
        ]);

    DB::table('cash_advance_request_audits')->insert([
        'cash_advance_request_id' => (int) $requestId,
        'action' => $newStatus === 'approved' ? 'approved_and_released' : 'rejected',
        'old_status' => $oldStatus,
        'new_status' => $newStatus,
        'remarks' => $validated['accounting_remarks'] ?? null,
        'acted_by' => Auth::id(),
        'meta' => json_encode([
            'approved_amount' => $approvedAmount,
            'approved_by_name' => $newStatus === 'approved' ? $actorName : null,
            'sent_by_name' => $newStatus === 'approved' ? $actorName : null,
        ]),
        'acted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Get the updated request data to broadcast
    $updatedRequest = DB::table('cash_advance_requests')
        ->where('id', (int) $requestId)
        ->first();

    // Broadcast the decision event to the requester (non-blocking)
    try {
        \App\Events\CashAdvanceRequestDecisionMade::dispatch(
            $requestId,
            $newStatus,
            $updatedRequest,
            $actorName
        );
    } catch (\Exception $e) {
        // Log the error but don't fail the request - data was already updated
        \Log::error('Failed to broadcast cash advance decision: ' . $e->getMessage());
    }

    return response()->json([
        'message' => $newStatus === 'approved'
            ? 'Request approved and released successfully.'
            : 'Request rejected successfully.',
    ]);
})->middleware('auth')->name('accounting.cash-advance.requests.decision');

Route::post('/accounting/cash-advance/requests/send', function (Request $request) {
    \Log::info('Cash advance send route called', ['user_id' => Auth::id()]);
    
    if ($redirect = redirect_if_role_not_allowed([3])) {
        \Log::warning('User not authorized for accounting', ['user_id' => Auth::id()]);
        return $redirect;
    }

    try {
        \Log::info('Validating request data');
        $validated = $request->validate([
            'requester_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'purpose' => 'required|string|max:2000',
            'accounting_remarks' => 'nullable|string|max:2000',
            'release_date' => 'nullable|date',
        ]);

        $requester = DB::table('users')
            ->where('id', (int) $validated['requester_id'])
            ->where('role_id', 2)
            ->first();

        abort_if(!$requester, 422, 'Selected employee is invalid.');

        $releaseDate = !empty($validated['release_date'])
            ? \Carbon\Carbon::parse($validated['release_date'])
            : now();
        $actorName = (string) (Auth::user()->name ?? 'Accounting Staff');
        $referenceNo = 'CA-' . $releaseDate->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

        \Log::info('Inserting cash advance request', ['reference_no' => $referenceNo, 'amount' => $validated['amount']]);
        
        $requestId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => $referenceNo,
            'requester_id' => (int) $validated['requester_id'],
            'requested_amount' => $validated['amount'],
            'approved_amount' => $validated['amount'],
            'purpose' => trim($validated['purpose']),
            'request_date' => $releaseDate->toDateString(),
            'date_needed' => $releaseDate->toDateString(),
            'status' => 'approved',
            'accounting_remarks' => !empty($validated['accounting_remarks'])
                ? trim($validated['accounting_remarks'])
                : 'Directly sent by accounting.',
            'reviewed_by' => Auth::id(),
            'approved_by_name' => $actorName,
            'sent_by_name' => $actorName,
            'submitted_at' => $releaseDate,
            'reviewed_at' => now(),
            'released_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Log::info('Inserting audit record for cash advance', ['request_id' => $requestId]);
        
        DB::table('cash_advance_request_audits')->insert([
            'cash_advance_request_id' => $requestId,
            'action' => 'sent_directly',
            'old_status' => null,
            'new_status' => 'approved',
            'remarks' => !empty($validated['accounting_remarks'])
                ? trim($validated['accounting_remarks'])
                : 'Direct send by accounting',
            'acted_by' => Auth::id(),
            'meta' => json_encode([
                'requested_amount' => (float) $validated['amount'],
                'approved_amount' => (float) $validated['amount'],
                'purpose' => trim($validated['purpose']),
                'approved_by_name' => $actorName,
                'sent_by_name' => $actorName,
                'accounting_remarks' => !empty($validated['accounting_remarks'])
                    ? trim($validated['accounting_remarks'])
                    : null,
            ]),
            'acted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Log::info('Cash advance sent successfully', ['request_id' => $requestId]);
        
        // Build response with explicit headers
        $jsonContent = json_encode([
            'message' => 'Cash advance has been sent and recorded successfully.',
            'id' => $requestId,
        ]);
        
        $response = response($jsonContent, 200)
            ->header('Content-Type', 'application/json')
            ->header('Content-Length', strlen($jsonContent))
            ->header('Connection', 'close')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        
        return $response;
    } catch (\Exception $e) {
        \Log::error('Error in cash advance send route', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        $errorContent = json_encode([
            'error' => 'An error occurred while processing your request.',
            'message' => $e->getMessage(),
        ]);
        
        return response($errorContent, 500)
            ->header('Content-Type', 'application/json')
            ->header('Content-Length', strlen($errorContent))
            ->header('Connection', 'close');
    }
})->middleware('auth')->name('accounting.cash-advance.requests.send');

Route::get('/admin/pricelist', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $projects = DB::table('projects')
        ->select('id', 'project_name')
        ->orderBy('project_name')
        ->get();

    $selectedProjectId = (int) $request->query('project');
    $projectIds = $projects->pluck('id');

    if (!$selectedProjectId || !$projectIds->contains($selectedProjectId)) {
        $selectedProjectId = optional($projects->first())->id;
    }

    $selectedProject = optional($projects->firstWhere('id', $selectedProjectId))->project_name;

    $items = collect();

    if ($selectedProjectId) {
        $items = DB::table('project_items')
            ->join('items', 'project_items.item_id', '=', 'items.id')
            ->leftJoin('suppliers', 'project_items.supplier_id', '=', 'suppliers.id')
            ->where('project_items.project_id', $selectedProjectId)
            ->select(
                'items.id',
                'items.item_number',
                'items.item_name',
                'items.item_description',
                'suppliers.supplier_name',
                'project_items.quantity',
                'project_items.unit_price as price',
                'items.image_path'
            )
            ->orderBy('items.item_number')
            ->get();
    }

    return view('admin.pricelist', compact('projects', 'selectedProject', 'items'));
})->middleware('auth')->name('admin.pricelist');

Route::post('/admin/items/upload-image', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    request()->validate([
        'item_id' => 'required|integer|exists:items,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:10240', // Max 10MB
    ]);

    try {
        $itemId = request('item_id');
        $item = DB::table('items')->where('id', $itemId)->first();
        
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Create slug for item name
        $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($item->item_name)));
        
        // Get file extension
        $extension = request()->file('image')->getClientOriginalExtension();
        $filename = $itemSlug . '.' . $extension;

        // Store images in a schema-safe path that does not depend on removed columns.
        $projectDir = 'items/uploads';
        
        // Store the file
        $path = request()->file('image')->storeAs($projectDir, $filename, 'public');

        // Update database
        DB::table('items')
            ->where('id', $itemId)
            ->update([
                'image_path' => $path,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image_path' => $path
        ]);

    } catch (\Exception $e) {
        \Log::error('Image upload failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

Route::get('/admin/purchase', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    return view('admin.purchase');
})->middleware('auth')->name('admin.purchase');

Route::get('/admin/additem', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $projects = DB::table('projects')->orderBy('project_name')->get();
    $categories = DB::table('item_categories')->orderBy('category_name')->get();
    return view('admin.additem', compact('projects', 'categories'));
})->middleware('auth')->name('admin.additem');

Route::post('/admin/additem', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    // Validate based on whether user is creating a new category or selecting existing
    if (request('category_id') === 'add_new') {
        request()->validate([
            'item_number' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|max:255',
            'supplier_address' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'new_category' => 'required|string|max:255',
            'base64_image' => 'nullable|string',
        ]);
    } else {
        request()->validate([
            'item_number' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'category_id' => 'required|integer|exists:item_categories,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|max:255',
            'supplier_address' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'base64_image' => 'nullable|string',
        ]);
    }

    $itemName = request('item_name');
    $imagePath = null;

    // Handle image upload
    if (request('base64_image')) {
        try {
            // Create slug for item name (lowercase, spaces to underscores)
            $itemSlug = strtolower(preg_replace('/\s+/', '_', trim($itemName))) . '_' . uniqid() . '.jpg';

            // Create directory structure if it doesn't exist
            $projectDir = storage_path('app/public/items');
            if (!is_dir($projectDir)) {
                mkdir($projectDir, 0755, true);
            }

            // Extract base64 data
            $base64Image = request('base64_image');
            if (strpos($base64Image, 'data:image') === 0) {
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            }

            // Decode and save image
            $imageData = base64_decode($base64Image);
            $filepath = $projectDir . '/' . $itemSlug;

            file_put_contents($filepath, $imageData);

            // Store relative path in database
            $imagePath = 'items/' . $itemSlug;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
        }
    }

    // Handle category: create new if add_new is selected
    if (request('category_id') === 'add_new') {
        $categoryId = DB::table('item_categories')->insertGetId([
            'category_name' => request('new_category'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        $categoryId = request('category_id');
    }

    // Create/find supplier first, then store supplier_id in items table.
    $supplierId = DB::table('suppliers')->where('supplier_name', request('supplier_name'))->value('id');

    if (!$supplierId) {
        $supplierId = DB::table('suppliers')->insertGetId([
            'supplier_name' => request('supplier_name'),
            'phone_number' => request('supplier_phone'),
            'address' => request('supplier_address'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        DB::table('suppliers')
            ->where('id', $supplierId)
            ->update([
                'phone_number' => request('supplier_phone'),
                'address' => request('supplier_address'),
                'updated_at' => now(),
            ]);
    }

    DB::table('items')->insert([
        'item_number' => request('item_number'),
        'item_name' => request('item_name'),
        'item_description' => request('item_description'),
        'category_id' => $categoryId,
        'supplier_id' => $supplierId,
        'quantity' => request('quantity'),
        'price' => request('price'),
        'purchase_date' => request('purchase_date'),
        'image_path' => $imagePath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.additem')->with('success', 'Item added successfully!');
})->middleware('auth')->name('admin.additem.store');

Route::get('/admin/priceanalysis', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    return view('admin.priceanalysis');
})->middleware('auth')->name('admin.priceanalysis');

Route::get('/admin/liquidation', function () {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $user = Auth::user();
    $cutoffPeriod = now()->format('F Y');

    $liquidation = DB::table('liquidations')
        ->where('user_id', $user->id)
        ->where('cutoff_period', $cutoffPeriod)
        ->where('status', 'pending')
        ->orderByDesc('id')
        ->first();

    if (!$liquidation) {
        $liquidationId = DB::table('liquidations')->insertGetId([
            'user_id' => $user->id,
            'cutoff_period' => $cutoffPeriod,
            'amount' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $liquidation = DB::table('liquidations')->where('id', $liquidationId)->first();
    }

    $particulars = \Illuminate\Support\Facades\DB::table('particulars')
        ->orderBy('particular_name')
        ->pluck('particular_name', 'id');

    $categories = \Illuminate\Support\Facades\DB::table('categories')
        ->orderBy('particulars_category')
        ->pluck('particulars_category', 'id');

    $liquidationExpenses = DB::table('liquidation_expenses')
        ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->where('liquidations.user_id', $user->id)
        ->where('liquidations.status', 'pending')
        ->select(
            'liquidation_expenses.id',
            'liquidation_expenses.expense_date',
            'categories.particulars_category as category_name',
            'liquidation_expenses.transaction_details',
            'liquidation_expenses.description',
            'liquidation_expenses.amount',
            'liquidation_expenses.receipt_path'
        )
        ->orderBy('liquidation_expenses.expense_date')
        ->get();

    return view('admin.liquidation', compact('particulars', 'liquidationExpenses', 'categories'));
})->middleware('auth')->name('admin.liquidation');

Route::post('/admin/liquidation/expenses', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $validated = $request->validate([
        'expense_date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
        'transaction_details' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'amount' => 'required|numeric|min:0.01',
        'receipt_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    $user = Auth::user();
    $expenseDate = \Carbon\Carbon::parse($validated['expense_date']);
    $cutoffPeriod = $expenseDate->format('F Y');

    $liquidation = DB::table('liquidations')
        ->where('user_id', $user->id)
        ->where('cutoff_period', $cutoffPeriod)
        ->where('status', 'pending')
        ->orderByDesc('id')
        ->first();

    if (!$liquidation) {
        $liquidationId = DB::table('liquidations')->insertGetId([
            'user_id' => $user->id,
            'cutoff_period' => $cutoffPeriod,
            'amount' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $liquidation = DB::table('liquidations')->where('id', $liquidationId)->first();
    }

    // Get the category name
    $category = DB::table('categories')->where('id', $validated['category_id'])->first();
    $categoryName = $category->particulars_category ?? 'General';

    $receiptPath = null;
    if ($request->hasFile('receipt_image')) {
        $receiptPath = $request->file('receipt_image')->store('liquidation-receipts', 'public');
    }

    $expenseId = DB::table('liquidation_expenses')->insertGetId([
        'liquidation_id' => $liquidation->id,
        'expense_date' => $validated['expense_date'],
        'category_id' => $validated['category_id'],
        'transaction_details' => $validated['transaction_details'],
        'description' => $validated['description'] ?? null,
        'amount' => $validated['amount'],
        'receipt_path' => $receiptPath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $expense = DB::table('liquidation_expenses')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->where('liquidation_expenses.id', $expenseId)
        ->select(
            'liquidation_expenses.id',
            'liquidation_expenses.expense_date',
            'liquidation_expenses.amount',
            'liquidation_expenses.transaction_details',
            'liquidation_expenses.description',
            'liquidation_expenses.receipt_path',
            'categories.particulars_category as category_name'
        )
        ->first();

    if ($expense && $expense->receipt_path) {
        $expense->receipt_url = Storage::disk('public')->url($expense->receipt_path);
    }

    return response()->json([
        'message' => 'Expense saved successfully.',
        'expense' => $expense,
    ]);
})->middleware('auth')->name('admin.liquidation.expenses.store');

Route::post('/admin/liquidation/submit', function (Request $request) {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $validated = $request->validate([
        'month_key' => 'required|date_format:Y-m',
    ]);

    $user = Auth::user();
    $month = \Carbon\Carbon::createFromFormat('Y-m', $validated['month_key'])->startOfMonth();
    $cutoffPeriod = $month->format('F Y');

    $liquidation = DB::table('liquidations')
        ->where('user_id', $user->id)
        ->where('cutoff_period', $cutoffPeriod)
        ->where('status', 'pending')
        ->orderByDesc('id')
        ->first();

    if (! $liquidation) {
        return response()->json([
            'message' => 'No liquidation record found for the selected month.',
        ], 404);
    }

    $expenseCount = DB::table('liquidation_expenses')
        ->where('liquidation_id', $liquidation->id)
        ->count();

    if ($expenseCount === 0) {
        return response()->json([
            'message' => 'Add at least one expense before submitting this liquidation.',
        ], 422);
    }

    $releasedAmount = DB::table('cash_advance_requests')
        ->where('requester_id', $user->id)
        ->where('status', 'approved')
        ->whereRaw("DATE_FORMAT(COALESCE(released_at, reviewed_at, request_date), '%Y-%m') = ?", [$validated['month_key']])
        ->sum(DB::raw('COALESCE(approved_amount, requested_amount, 0)'));

    DB::table('liquidations')
        ->where('id', $liquidation->id)
        ->update([
            'amount' => $releasedAmount,
            'status' => 'submitted',
            'submitted_at' => now(),
            'approved_at' => null,
            'remarks' => null,
            'updated_at' => now(),
        ]);

    return response()->json([
        'message' => 'Liquidation submitted to accounting for review.',
        'liquidation_id' => (int) $liquidation->id,
        'status' => 'submitted',
    ]);
})->middleware('auth')->name('admin.liquidation.submit');

Route::patch('/accounting/liquidation/{liquidation}/decision', function (Request $request, $liquidation) {
    if ($redirect = redirect_if_role_not_allowed([3])) {
        return $redirect;
    }

    $validated = $request->validate([
        'decision' => 'required|in:approved,rejected',
        'remarks' => 'nullable|string|max:2000',
    ]);

    $record = DB::table('liquidations')
        ->where('id', (int) $liquidation)
        ->first();

    if (! $record) {
        return response()->json([
            'message' => 'Liquidation record not found.',
        ], 404);
    }

    $isSubmittedForReview = strtolower((string) $record->status) === 'submitted'
        || (!empty($record->submitted_at) && strtolower((string) $record->status) === 'pending');

    if (! $isSubmittedForReview) {
        return response()->json([
            'message' => 'This liquidation is no longer pending accounting review.',
        ], 422);
    }

    DB::table('liquidations')
        ->where('id', (int) $liquidation)
        ->update([
            'status' => $validated['decision'],
            'remarks' => $validated['remarks'] ?? null,
            'approved_at' => $validated['decision'] === 'approved' ? now() : null,
            'updated_at' => now(),
        ]);

    return response()->json([
        'message' => $validated['decision'] === 'approved'
            ? 'Liquidation approved successfully.'
            : 'Liquidation rejected successfully.',
        'liquidation_id' => (int) $liquidation,
        'status' => $validated['decision'],
        'remarks' => $validated['remarks'] ?? null,
        'approved_at' => $validated['decision'] === 'approved' ? now()->toDateTimeString() : null,
    ]);
})->middleware('auth')->name('accounting.liquidation.decision');

Route::delete('/admin/liquidation/expenses/{expenseId}', function ($expenseId) {
    if ($redirect = redirect_if_role_not_allowed([1, 2])) {
        return $redirect;
    }

    $user = Auth::user();

    $expense = DB::table('liquidation_expenses')
        ->join('liquidations', 'liquidation_expenses.liquidation_id', '=', 'liquidations.id')
        ->where('liquidation_expenses.id', $expenseId)
        ->where('liquidations.user_id', $user->id)
        ->where('liquidations.status', 'pending')
        ->select('liquidation_expenses.id', 'liquidation_expenses.receipt_path')
        ->first();

    if (! $expense) {
        return response()->json([
            'message' => 'Expense not found or access denied.',
        ], 404);
    }

    DB::table('liquidation_expenses')
        ->where('id', $expenseId)
        ->delete();

    if ($expense->receipt_path) {
        Storage::disk('public')->delete($expense->receipt_path);
    }

    return response()->json([
        'message' => 'Expense deleted successfully.',
    ]);
})->middleware('auth')->name('admin.liquidation.expenses.destroy');


/*
|--------------------------------------------------------------------------
| API - Search Items
|--------------------------------------------------------------------------
*/

Route::get('/api/search-items', function (Request $request) {
    $query = $request->query('q', '');
    
    if (strlen($query) < 1) {
        return response()->json([]);
    }

    // Get all matching items
    $itemDetails = DB::table('items')
        ->where('item_number', 'LIKE', '%' . $query . '%')
        ->orWhere('item_name', 'LIKE', '%' . $query . '%')
        ->select('item_number', 'item_name', 'project', 'supplier', 'price', 'quantity')
        ->get();

    if ($itemDetails->isEmpty()) {
        return response()->json([]);
    }

    // Group by item number and name
    $groupedItems = [];
    foreach ($itemDetails as $item) {
        $key = $item->item_number . '|' . $item->item_name;
        
        if (!isset($groupedItems[$key])) {
            $groupedItems[$key] = [
                'number' => $item->item_number,
                'name' => $item->item_name,
                'project' => $item->project,
                'image' => '/images/placeholder.jpg',
                'suppliers' => []
            ];
        }

        // Add supplier if not already added
        $supplierExists = false;
        foreach ($groupedItems[$key]['suppliers'] as $s) {
            if ($s['supplier'] === $item->supplier) {
                $supplierExists = true;
                break;
            }
        }

        if (!$supplierExists) {
            $groupedItems[$key]['suppliers'][] = [
                'supplier' => $item->supplier,
                'price' => (float)$item->price,
                'quantity' => $item->quantity
            ];
        }
    }

    // Sort suppliers by price in each item
    foreach ($groupedItems as &$item) {
        usort($item['suppliers'], function($a, $b) {
            return $a['price'] <=> $b['price'];
        });
    }

    return response()->json(array_values($groupedItems));
})->middleware('auth')->name('api.search-items');

Route::get('/api/projects', function () {
    $projects = DB::table('projects')
        ->select('id', 'project_name')
        ->orderBy('project_name')
        ->get();
    
    return response()->json($projects);
})->middleware('auth')->name('api.projects');

Route::post('/api/projects', function (Request $request) {
    try {
        $request->validate([
            'project_name' => 'required|string|max:255|unique:projects',
            'project_date' => 'required|date'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
        ], 422);
    }
    
    $projectId = DB::table('projects')->insertGetId([
        'project_name' => $request->project_name,
        'project_date' => $request->project_date,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'project' => [
            'id' => $projectId,
            'project_name' => $request->project_name,
            'project_date' => $request->project_date
        ]
    ]);
})->middleware('auth')->name('api.projects.store');

// Price Analysis API - Search for items with all supplier prices
Route::get('/api/price-analysis/search', function (Request $request) {
    $query = $request->query('q');
    
    if (!$query) {
        return response()->json(['success' => false, 'message' => 'Query required']);
    }

    // Find ALL matching items by item_number or item_name
    $matchingItems = DB::table('items')
        ->where('item_number', 'LIKE', "%{$query}%")
        ->orWhere('item_name', 'LIKE', "%{$query}%")
        ->select('item_number', 'item_name', 'item_description', 'image_path')
        ->distinct()
        ->limit(10) // Limit to top 10 matches
        ->get();

    if ($matchingItems->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No items found']);
    }

    // For each unique item, get all suppliers and prices
    $results = [];
    foreach ($matchingItems as $matchedItem) {
        $allPurchases = DB::table('items')
            ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
            ->where('items.item_number', $matchedItem->item_number)
            ->select(
                'items.id',
                'items.item_number',
                'items.item_name',
                'items.item_description',
                'items.image_path',
                'items.price',
                'items.quantity',
                'items.purchase_date',
                'items.supplier_id',
                'suppliers.supplier_name',
                'suppliers.phone_number',
                'suppliers.address'
            )
            ->orderBy('items.price', 'asc')
            ->get();

        // Group suppliers for this item
        $suppliers = $allPurchases->map(function($purchase) {
            return [
                'item_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier_name ?? 'N/A',
                'phone_number' => $purchase->phone_number,
                'address' => $purchase->address,
                'price' => (float) $purchase->price,
                'quantity' => $purchase->quantity,
                'purchase_date' => $purchase->purchase_date
            ];
        });

        $results[] = [
            'item_number' => $matchedItem->item_number,
            'item_name' => $matchedItem->item_name,
            'item_description' => $matchedItem->item_description,
            'image_path' => $matchedItem->image_path,
            'suppliers' => $suppliers
        ];
    }

    return response()->json([
        'success' => true,
        'items' => $results
    ]);
})->middleware('auth')->name('api.price-analysis.search');

// Price Analysis API - Add item to project
Route::post('/api/price-analysis/add-to-project', function (Request $request) {
    try {
        $data = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'item_id' => 'required|integer|exists:items,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
        ], 422);
    }

    $item = DB::table('items')
        ->select('id', 'quantity')
        ->where('id', $data['item_id'])
        ->first();

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }

    $allocatedQuantity = (int) DB::table('project_items')
        ->where('item_id', $data['item_id'])
        ->sum('quantity');

    $remainingStock = (int) $item->quantity - $allocatedQuantity;

    if ((int) $data['quantity'] > $remainingStock) {
        return response()->json([
            'success' => false,
            'message' => 'Requested quantity exceeds remaining stock',
            'remaining_stock' => max(0, $remainingStock)
        ], 422);
    }

    // Insert/Update in project_items pivot table
    $existingLink = DB::table('project_items')
        ->where('project_id', $data['project_id'])
        ->where('item_id', $data['item_id'])
        ->where('supplier_id', $data['supplier_id'])
        ->first();

    if ($existingLink) {
        // Update quantity and unit_price if already linked
        DB::table('project_items')
            ->where('id', $existingLink->id)
            ->update([
                'quantity' => (int) $existingLink->quantity + (int) $data['quantity'],
                'unit_price' => $data['unit_price'],
                'updated_at' => now()
            ]);
    } else {
        // Create new link
        DB::table('project_items')->insert([
            'project_id' => $data['project_id'],
            'item_id' => $data['item_id'],
            'supplier_id' => $data['supplier_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Item added to project successfully'
    ]);
})->middleware('auth')->name('api.price-analysis.add-to-project');

// DEBUG TEST
Route::get('/test-items', function () {
    $items = DB::table('items')->limit(5)->get();
    return response()->json([
        'total' => DB::table('items')->count(),
        'sample' => $items
    ]);
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Price Analysis - Category-Based Navigation
|--------------------------------------------------------------------------
*/

// Get all categories
Route::get('/api/categories', function () {
    $categories = DB::table('item_categories')
        ->select('id', 'category_name')
        ->orderBy('category_name', 'asc')
        ->get();
    
    return response()->json([
        'success' => true,
        'categories' => $categories
    ]);
})->middleware('auth')->name('api.categories');

// Get items by category
Route::get('/api/categories/{categoryId}/items', function ($categoryId) {
    $category = DB::table('item_categories')
        ->where('id', $categoryId)
        ->select('id', 'category_name')
        ->first();
    
    if (!$category) {
        return response()->json(['success' => false, 'message' => 'Category not found'], 404);
    }
    
    $items = DB::table('items')
        ->where('category_id', $categoryId)
        ->select('id', 'item_number', 'item_name', 'item_description', 'image_path')
        ->distinct()
        ->get();
    
    return response()->json([
        'success' => true,
        'category' => $category,
        'items' => $items
    ]);
})->middleware('auth')->name('api.categories.items');

// Get item details with suppliers
Route::get('/api/categories/items/{itemId}/details', function ($itemId) {
    $item = DB::table('items')
        ->where('id', $itemId)
        ->select('id', 'item_number', 'item_name', 'item_description', 'image_path', 'category_id')
        ->first();
    
    if (!$item) {
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }
    
    // Get all suppliers for this item
    $suppliers = DB::table('items')
        ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
        ->where('items.item_number', $item->item_number)
        ->select(
            'items.id',
            'items.price',
            'items.quantity',
            'items.purchase_date',
            'items.supplier_id',
            'suppliers.supplier_name',
            'suppliers.phone_number',
            'suppliers.address'
        )
        ->orderBy('items.price', 'asc')
        ->get();
    
    $suppliers = $suppliers->map(function($purchase) {
        return [
            'item_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'supplier_name' => $purchase->supplier_name ?? 'N/A',
            'phone_number' => $purchase->phone_number,
            'address' => $purchase->address,
            'price' => (float) $purchase->price,
            'quantity' => $purchase->quantity,
            'purchase_date' => $purchase->purchase_date
        ];
    });
    
    return response()->json([
        'success' => true,
        'item' => [
            'id' => $item->id,
            'item_number' => $item->item_number,
            'item_name' => $item->item_name,
            'item_description' => $item->item_description,
            'image_path' => $item->image_path,
            'suppliers' => $suppliers
        ]
    ]);
})->middleware('auth')->name('api.items.details');

// Create new category
Route::post('/api/categories', function (Request $request) {
    $validated = $request->validate([
        'category_name' => 'required|string|max:255|unique:item_categories,category_name'
    ]);
    
    $category = DB::table('item_categories')->insertGetId([
        'category_name' => $validated['category_name'],
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Category created successfully',
        'category_id' => $category,
        'category_name' => $validated['category_name']
    ]);
})->middleware('auth')->name('api.categories.store');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
