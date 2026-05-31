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

if (!function_exists('buildLiquidationQueueRecords')) {
    function buildLiquidationQueueRecords(?int $employeeId = null)
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
                'liquidations.amount as liquidation_amount',
                DB::raw('COALESCE(expense_totals.total_expended, 0) as total_expended'),
                DB::raw('COALESCE(expense_totals.expense_count, 0) as expense_count')
            )
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('liquidations.user_id', $employeeId);
            })
            ->whereRaw('COALESCE(expense_totals.expense_count, 0) > 0')
            ->where(function ($query) {
                $query->whereNotNull('liquidations.submitted_at')
                    ->orWhereIn('liquidations.status', ['submitted', 'approved', 'rejected']);
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

        return $records->map(function ($row) use ($expenseRowsByLiquidation) {
            try {
                $recordDate = \Carbon\Carbon::createFromFormat('F Y', (string) $row->cutoff_period)->startOfMonth();
            } catch (\Throwable $exception) {
                $recordDate = \Carbon\Carbon::parse($row->submitted_at ?? $row->approved_at ?? $row->created_at);
            }

            $weekStart = $recordDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $weekEnd = $recordDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $monthKey = $recordDate->format('Y-m');
            $liquidationAmount = (float) $row->liquidation_amount;
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
                'record_type' => 'liquidation',
                'queue_record' => true,
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
                'balance_sent' => $liquidationAmount,
                'liquidation_amount' => $liquidationAmount,
                'total_expenses' => (float) $row->total_expended,
                'remaining_balance' => $liquidationAmount - (float) $row->total_expended,
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

if (!function_exists('buildLiquidationTrackingRecords')) {
    function buildLiquidationTrackingRecords(?int $employeeId = null)
    {
        $liquidationTotalsSubquery = DB::table('liquidation_expenses')
            ->whereNotNull('cash_advance_request_id')
            ->groupBy('cash_advance_request_id')
            ->selectRaw('cash_advance_request_id, SUM(amount) as total_expended, COUNT(*) as expense_count');

        $records = DB::table('cash_advance_requests')
            ->join('users', 'cash_advance_requests.requester_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoinSub($liquidationTotalsSubquery, 'liquidation_totals', function ($join) {
                $join->on('liquidation_totals.cash_advance_request_id', '=', 'cash_advance_requests.id');
            })
            ->select(
                'cash_advance_requests.id',
                'cash_advance_requests.requester_id as user_id',
                'users.employee_id',
                'users.name',
                DB::raw("COALESCE(roles.name, 'Employee') as role_name"),
                'cash_advance_requests.reference_no',
                'cash_advance_requests.request_date',
                'cash_advance_requests.purpose',
                'cash_advance_requests.category',
                'cash_advance_requests.accounting_remarks',
                'cash_advance_requests.status',
                'cash_advance_requests.submitted_at',
                'cash_advance_requests.reviewed_at',
                'cash_advance_requests.released_at',
                'cash_advance_requests.created_at',
                DB::raw('COALESCE(cash_advance_requests.approved_amount, cash_advance_requests.requested_amount, 0) as liquidation_amount'),
                DB::raw('COALESCE(liquidation_totals.total_expended, 0) as total_expended'),
                DB::raw('COALESCE(liquidation_totals.expense_count, 0) as expense_count')
            )
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('cash_advance_requests.requester_id', $employeeId);
            })
            ->orderByDesc('cash_advance_requests.request_date')
            ->orderByDesc('cash_advance_requests.id')
            ->get();

        $expenseRowsByRequest = DB::table('liquidation_expenses')
            ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
            ->select(
                'liquidation_expenses.cash_advance_request_id',
                'liquidation_expenses.expense_date',
                'liquidation_expenses.transaction_details',
                'liquidation_expenses.description',
                'liquidation_expenses.amount',
                'liquidation_expenses.receipt_path',
                'categories.particulars_category as category_name'
            )
            ->whereNotNull('liquidation_expenses.cash_advance_request_id')
            ->orderByDesc('liquidation_expenses.expense_date')
            ->get()
            ->groupBy('cash_advance_request_id');

        return $records->map(function ($row) use ($expenseRowsByRequest) {
            $recordDate = \Carbon\Carbon::parse($row->request_date);
            $weekStart = $recordDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $weekEnd = $recordDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            $monthKey = $recordDate->format('Y-m');
            $liquidationAmount = (float) $row->liquidation_amount;
            $expenseBreakdown = ($expenseRowsByRequest->get($row->id) ?? collect())->map(function ($expense) {
                return [
                    'expense_date' => $expense->expense_date,
                    'category' => $expense->category_name,
                    'details' => trim((string) $expense->transaction_details),
                    'description' => trim((string) $expense->description),
                    'amount' => (float) $expense->amount,
                    'receipt_path' => $expense->receipt_path,
                    'receipt_url' => $expense->receipt_path ? Storage::disk('public')->url($expense->receipt_path) : null,
                ];
            })->values();

            if ($expenseBreakdown->isEmpty()) {
                $expenseBreakdown = collect([[
                    'expense_date' => $row->request_date,
                    'category' => $row->category ?: '-',
                    'details' => trim((string) $row->purpose) ?: ($row->reference_no ?: 'Cash advance request'),
                    'description' => trim((string) $row->accounting_remarks),
                    'amount' => $liquidationAmount,
                    'receipt_path' => null,
                    'receipt_url' => null,
                ]]);
            }

            return [
                'id' => (int) $row->id,
                'record_type' => 'cash_advance_request',
                'queue_record' => false,
                'user_id' => (int) $row->user_id,
                'employee_id' => $row->employee_id,
                'name' => $row->name,
                'role_name' => $row->role_name ?: 'Employee',
                'reference_no' => $row->reference_no,
                'cutoff_period' => $recordDate->format('F Y'),
                'status' => $row->status,
                'remarks' => $row->accounting_remarks,
                'document_path' => null,
                'submitted_at' => $row->submitted_at,
                'approved_at' => $row->reviewed_at,
                'record_date' => $recordDate->format('Y-m-d'),
                'record_timestamp' => $recordDate->toIso8601String(),
                'period_month_key' => $monthKey,
                'period_month_label' => $recordDate->format('F Y'),
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d'),
                'week_label' => $weekStart->format('M j') . '–' . $weekEnd->format('M j'),
                'balance_sent' => $liquidationAmount,
                'liquidation_amount' => $liquidationAmount,
                'total_expenses' => (float) $row->total_expended,
                'remaining_balance' => $liquidationAmount - (float) $row->total_expended,
                'expense_count' => max(1, (int) $row->expense_count),
                'expense_breakdown' => $expenseBreakdown,
                'search_text' => strtolower(trim(
                    ($row->employee_id ?? '') . ' ' .
                    ($row->name ?? '') . ' ' .
                    ($row->role_name ?? '') . ' ' .
                    ($row->reference_no ?? '') . ' ' .
                    ($row->purpose ?? '') . ' ' .
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

    $currentUserId = Auth::id();

    $availableMonths = collect(range(1, 12))
        ->map(function (int $month) {
            $date = \Carbon\Carbon::createFromDate(2026, $month, 1);

            return [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y'),
            ];
        });

    $currentMonthKey = now()->year === 2026 ? now()->format('Y-m') : '2026-12';
    $availableMonthKeys = $availableMonths->pluck('value');
    $requestedMonthKey = (string) request()->query('month', '');
    $selectedMonthKey = $availableMonthKeys->contains($requestedMonthKey)
        ? $requestedMonthKey
        : ($availableMonthKeys->contains($currentMonthKey) ? $currentMonthKey : '2026-12');

    $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonthKey)->startOfMonth();
    $monthStart = $selectedMonth->toDateString();
    $monthEnd = $selectedMonth->copy()->endOfMonth()->toDateString();
    $transactionAmountSql = 'COALESCE(cash_advance_requests.approved_amount, cash_advance_requests.requested_amount, 0)';

    $allBreakdownsSubquery = DB::table('liquidation_expenses')
        ->whereNotNull('cash_advance_request_id')
        ->groupBy('cash_advance_request_id')
        ->selectRaw('cash_advance_request_id, COUNT(*) as breakdown_count');

    $monthlyTransactionSummary = DB::table('cash_advance_requests')
        ->where('requester_id', $currentUserId)
        ->whereBetween('request_date', [$monthStart, $monthEnd])
        ->selectRaw("\n            SUM({$transactionAmountSql}) as total_received\n        ")
        ->first();

    $liquidationCount = DB::table('liquidation_expenses')
        ->join('cash_advance_requests', 'liquidation_expenses.cash_advance_request_id', '=', 'cash_advance_requests.id')
        ->where('cash_advance_requests.requester_id', $currentUserId)
        ->whereBetween('liquidation_expenses.expense_date', [$monthStart, $monthEnd])
        ->count();

    $breakdownCategoryQuery = DB::table('cash_advance_requests')
        ->join('liquidation_expenses', 'liquidation_expenses.cash_advance_request_id', '=', 'cash_advance_requests.id')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->where('cash_advance_requests.requester_id', $currentUserId)
        ->whereBetween('request_date', [$monthStart, $monthEnd])
        ->whereNotNull('categories.particulars_category')
        ->groupBy('categories.particulars_category')
        ->selectRaw("\n            categories.particulars_category as category_name,\n            SUM(liquidation_expenses.amount) as total_amount,\n            COUNT(DISTINCT cash_advance_requests.id) as transaction_count\n        ");

    $transactionCategoryQuery = DB::table('cash_advance_requests')
        ->leftJoinSub($allBreakdownsSubquery, 'dashboard_breakdowns', function ($join) {
            $join->on('dashboard_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
        })
        ->where('cash_advance_requests.requester_id', $currentUserId)
        ->whereBetween('cash_advance_requests.request_date', [$monthStart, $monthEnd])
        ->whereRaw('COALESCE(dashboard_breakdowns.breakdown_count, 0) = 0')
        ->whereNotNull('cash_advance_requests.category')
        ->where('cash_advance_requests.category', '<>', '')
        ->groupBy('cash_advance_requests.category')
        ->selectRaw("\n            cash_advance_requests.category as category_name,\n            SUM({$transactionAmountSql}) as total_amount,\n            COUNT(*) as transaction_count\n        ");

    $categorySummaries = DB::query()
        ->fromSub($breakdownCategoryQuery->unionAll($transactionCategoryQuery), 'category_allocations')
        ->selectRaw('category_name, SUM(total_amount) as total_amount, SUM(transaction_count) as transaction_count')
        ->groupBy('category_name')
        ->orderByDesc('total_amount')
        ->get();

    $topCategories = $categorySummaries->take(5)->values();
    $totalCategoryAmount = (float) $categorySummaries->sum('total_amount');
    $monthlyTransactionSummary->total_liquidated = $totalCategoryAmount;
    $monthlyTransactionSummary->transaction_count = $liquidationCount;
    $distribution = $categorySummaries->take(4)->map(function ($category) use ($totalCategoryAmount) {
        return [
            'category_name' => $category->category_name,
            'total_amount' => (float) $category->total_amount,
            'percentage' => $totalCategoryAmount > 0 ? ((float) $category->total_amount / $totalCategoryAmount) * 100 : 0,
        ];
    })->values();

    $otherCategoryAmount = max(0, $totalCategoryAmount - (float) $distribution->sum('total_amount'));
    if ($otherCategoryAmount > 0) {
        $distribution->push([
            'category_name' => 'Others',
            'total_amount' => $otherCategoryAmount,
            'percentage' => $totalCategoryAmount > 0 ? ($otherCategoryAmount / $totalCategoryAmount) * 100 : 0,
        ]);
    }

    $trendStart = $selectedMonth->copy()->subMonths(5)->startOfMonth();
    $trendEnd = $selectedMonth->copy()->endOfMonth();

    $trendBreakdownQuery = DB::table('cash_advance_requests')
        ->join('liquidation_expenses', 'liquidation_expenses.cash_advance_request_id', '=', 'cash_advance_requests.id')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->where('cash_advance_requests.requester_id', $currentUserId)
        ->whereBetween('cash_advance_requests.request_date', [$trendStart->toDateString(), $trendEnd->toDateString()])
        ->whereNotNull('categories.particulars_category')
        ->groupBy('month_key', 'categories.particulars_category')
        ->selectRaw("\n            DATE_FORMAT(cash_advance_requests.request_date, '%Y-%m') as month_key,\n            categories.particulars_category as category_name,\n            SUM(liquidation_expenses.amount) as total_amount\n        ");

    $trendTransactionCategoryQuery = DB::table('cash_advance_requests')
        ->leftJoinSub($allBreakdownsSubquery, 'dashboard_breakdowns', function ($join) {
            $join->on('dashboard_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
        })
        ->where('cash_advance_requests.requester_id', $currentUserId)
        ->whereBetween('cash_advance_requests.request_date', [$trendStart->toDateString(), $trendEnd->toDateString()])
        ->whereRaw('COALESCE(dashboard_breakdowns.breakdown_count, 0) = 0')
        ->whereNotNull('cash_advance_requests.category')
        ->where('cash_advance_requests.category', '<>', '')
        ->groupBy('month_key', 'cash_advance_requests.category')
        ->selectRaw("\n            DATE_FORMAT(cash_advance_requests.request_date, '%Y-%m') as month_key,\n            cash_advance_requests.category as category_name,\n            SUM({$transactionAmountSql}) as total_amount\n        ");

    $trendRows = DB::query()
        ->fromSub($trendBreakdownQuery->unionAll($trendTransactionCategoryQuery), 'trend_allocations')
        ->selectRaw('month_key, category_name, SUM(total_amount) as total_amount')
        ->groupBy('month_key', 'category_name')
        ->get();

    $trendCategoryNames = $topCategories->pluck('category_name')->take(4)->values();
    $monthlyTrend = collect(range(5, 0))->map(function (int $monthsAgo) use ($selectedMonth, $trendRows, $trendCategoryNames) {
        $monthDate = $selectedMonth->copy()->subMonths($monthsAgo)->startOfMonth();
        $monthKey = $monthDate->format('Y-m');
        $monthRows = $trendRows->where('month_key', $monthKey);
        $categoryRows = $trendCategoryNames->map(function (string $categoryName) use ($monthRows) {
            return [
                'category_name' => $categoryName,
                'total_amount' => (float) optional($monthRows->firstWhere('category_name', $categoryName))->total_amount,
            ];
        });
        $otherAmount = max(0, (float) $monthRows->sum('total_amount') - (float) $categoryRows->sum('total_amount'));

        if ($otherAmount > 0) {
            $categoryRows->push([
                'category_name' => 'Others',
                'total_amount' => $otherAmount,
            ]);
        }

        return [
            'month_key' => $monthKey,
            'label' => $monthDate->format('M Y'),
            'categories' => $categoryRows->values(),
            'total_amount' => (float) $monthRows->sum('total_amount'),
        ];
    });

    return view('admin.dashboard', compact(
        'availableMonths',
        'selectedMonthKey',
        'selectedMonth',
        'monthlyTransactionSummary',
        'categorySummaries',
        'topCategories',
        'distribution',
        'totalCategoryAmount',
        'monthlyTrend'
    ));
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

    $availableMonths = collect(range(1, 12))
        ->map(function (int $month) {
            $date = \Carbon\Carbon::createFromDate(2026, $month, 1);

            return [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y'),
            ];
        });

    $currentMonthKey = now()->year === 2026 ? now()->format('Y-m') : '2026-12';
    $availableMonthKeys = $availableMonths->pluck('value');
    $requestedMonthKey = (string) request()->query('month', '');
    $selectedMonthKey = $availableMonthKeys->contains($requestedMonthKey)
        ? $requestedMonthKey
        : ($availableMonthKeys->contains($currentMonthKey) ? $currentMonthKey : '2026-12');

    $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonthKey)->startOfMonth();
    $monthStart = $selectedMonth->toDateString();
    $monthEnd = $selectedMonth->copy()->endOfMonth()->toDateString();
    $transactionAmountSql = 'COALESCE(cash_advance_requests.approved_amount, cash_advance_requests.requested_amount, 0)';
    $debitWhereSql = "LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) NOT LIKE ?";
    $creditWhereSql = "LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE ?";

    $currentMonthlyBalance = (object) AccountingMonthlyBalance::forMonth($selectedMonth);

    $allBreakdownsSubquery = DB::table('liquidation_expenses')
        ->whereNotNull('cash_advance_request_id')
        ->groupBy('cash_advance_request_id')
        ->selectRaw('cash_advance_request_id, COUNT(*) as breakdown_count');

    $monthlyTransactionSummary = DB::table('cash_advance_requests')
        ->whereBetween('request_date', [$monthStart, $monthEnd])
        ->selectRaw("
            COUNT(*) as transaction_count,
            SUM(CASE WHEN {$creditWhereSql} THEN {$transactionAmountSql} ELSE 0 END) as total_credits,
            SUM(CASE WHEN {$debitWhereSql} THEN {$transactionAmountSql} ELSE 0 END) as total_debits
        ", ['%manual credit entry%', '%manual credit entry%'])
        ->first();

    if ((int) ($monthlyTransactionSummary->transaction_count ?? 0) === 0) {
        $currentMonthlyBalance = (object) [
            'opening_balance' => 0,
            'remaining_balance' => 0,
            'ending_balance' => 0,
        ];
    }

    $breakdownCategoryQuery = DB::table('cash_advance_requests')
        ->join('liquidation_expenses', 'liquidation_expenses.cash_advance_request_id', '=', 'cash_advance_requests.id')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->whereBetween('request_date', [$monthStart, $monthEnd])
        ->whereRaw($debitWhereSql, ['%manual credit entry%'])
        ->whereNotNull('categories.particulars_category')
        ->groupBy('categories.particulars_category')
        ->selectRaw("
            categories.particulars_category as category_name,
            SUM(liquidation_expenses.amount) as total_amount,
            COUNT(DISTINCT cash_advance_requests.id) as transaction_count
        ");

    $transactionCategoryQuery = DB::table('cash_advance_requests')
        ->leftJoinSub($allBreakdownsSubquery, 'dashboard_breakdowns', function ($join) {
            $join->on('dashboard_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
        })
        ->whereBetween('cash_advance_requests.request_date', [$monthStart, $monthEnd])
        ->whereRaw($debitWhereSql, ['%manual credit entry%'])
        ->whereRaw('COALESCE(dashboard_breakdowns.breakdown_count, 0) = 0')
        ->whereNotNull('cash_advance_requests.category')
        ->where('cash_advance_requests.category', '<>', '')
        ->groupBy('cash_advance_requests.category')
        ->selectRaw("
            cash_advance_requests.category as category_name,
            SUM({$transactionAmountSql}) as total_amount,
            COUNT(*) as transaction_count
        ");

    $categorySummaries = DB::query()
        ->fromSub($breakdownCategoryQuery->unionAll($transactionCategoryQuery), 'category_allocations')
        ->selectRaw('category_name, SUM(total_amount) as total_amount, SUM(transaction_count) as transaction_count')
        ->groupBy('category_name')
        ->orderByDesc('total_amount')
        ->get();

    $topCategories = $categorySummaries->take(5)->values();
    $totalCategoryAmount = (float) $categorySummaries->sum('total_amount');
    $distribution = $categorySummaries->take(4)->map(function ($category) use ($totalCategoryAmount) {
        return [
            'category_name' => $category->category_name,
            'total_amount' => (float) $category->total_amount,
            'percentage' => $totalCategoryAmount > 0 ? ((float) $category->total_amount / $totalCategoryAmount) * 100 : 0,
        ];
    })->values();

    $otherCategoryAmount = max(0, $totalCategoryAmount - (float) $distribution->sum('total_amount'));
    if ($otherCategoryAmount > 0) {
        $distribution->push([
            'category_name' => 'Others',
            'total_amount' => $otherCategoryAmount,
            'percentage' => $totalCategoryAmount > 0 ? ($otherCategoryAmount / $totalCategoryAmount) * 100 : 0,
        ]);
    }

    $trendStart = $selectedMonth->copy()->subMonths(5)->startOfMonth();
    $trendEnd = $selectedMonth->copy()->endOfMonth();

    $trendBreakdownQuery = DB::table('cash_advance_requests')
        ->join('liquidation_expenses', 'liquidation_expenses.cash_advance_request_id', '=', 'cash_advance_requests.id')
        ->leftJoin('categories', 'liquidation_expenses.category_id', '=', 'categories.id')
        ->whereBetween('cash_advance_requests.request_date', [$trendStart->toDateString(), $trendEnd->toDateString()])
        ->whereRaw($debitWhereSql, ['%manual credit entry%'])
        ->whereNotNull('categories.particulars_category')
        ->groupBy('month_key', 'categories.particulars_category')
        ->selectRaw("
            DATE_FORMAT(cash_advance_requests.request_date, '%Y-%m') as month_key,
            categories.particulars_category as category_name,
            SUM(liquidation_expenses.amount) as total_amount
        ");

    $trendTransactionCategoryQuery = DB::table('cash_advance_requests')
        ->leftJoinSub($allBreakdownsSubquery, 'dashboard_breakdowns', function ($join) {
            $join->on('dashboard_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
        })
        ->whereBetween('cash_advance_requests.request_date', [$trendStart->toDateString(), $trendEnd->toDateString()])
        ->whereRaw($debitWhereSql, ['%manual credit entry%'])
        ->whereRaw('COALESCE(dashboard_breakdowns.breakdown_count, 0) = 0')
        ->whereNotNull('cash_advance_requests.category')
        ->where('cash_advance_requests.category', '<>', '')
        ->groupBy('month_key', 'cash_advance_requests.category')
        ->selectRaw("
            DATE_FORMAT(cash_advance_requests.request_date, '%Y-%m') as month_key,
            cash_advance_requests.category as category_name,
            SUM({$transactionAmountSql}) as total_amount
        ");

    $trendRows = DB::query()
        ->fromSub($trendBreakdownQuery->unionAll($trendTransactionCategoryQuery), 'trend_allocations')
        ->selectRaw('month_key, category_name, SUM(total_amount) as total_amount')
        ->groupBy('month_key', 'category_name')
        ->get();

    $trendCategoryNames = $topCategories->pluck('category_name')->take(4)->values();
    $monthlyTrend = collect(range(5, 0))->map(function (int $monthsAgo) use ($selectedMonth, $trendRows, $trendCategoryNames) {
        $monthDate = $selectedMonth->copy()->subMonths($monthsAgo)->startOfMonth();
        $monthKey = $monthDate->format('Y-m');
        $monthRows = $trendRows->where('month_key', $monthKey);
        $categoryRows = $trendCategoryNames->map(function (string $categoryName) use ($monthRows) {
            return [
                'category_name' => $categoryName,
                'total_amount' => (float) optional($monthRows->firstWhere('category_name', $categoryName))->total_amount,
            ];
        });
        $otherAmount = max(0, (float) $monthRows->sum('total_amount') - (float) $categoryRows->sum('total_amount'));

        if ($otherAmount > 0) {
            $categoryRows->push([
                'category_name' => 'Others',
                'total_amount' => $otherAmount,
            ]);
        }

        return [
            'month_key' => $monthKey,
            'label' => $monthDate->format('M Y'),
            'categories' => $categoryRows->values(),
            'total_amount' => (float) $monthRows->sum('total_amount'),
        ];
    });

    return view('accounting.dashboard', compact(
        'availableMonths',
        'selectedMonthKey',
        'selectedMonth',
        'currentMonthlyBalance',
        'monthlyTransactionSummary',
        'categorySummaries',
        'topCategories',
        'distribution',
        'totalCategoryAmount',
        'monthlyTrend'
    ));
})->middleware('auth')->name('accounting.dashboard');

Route::get('/accounting/send-cash', function () {
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

    return view('accounting.send-cash', compact('employees', 'currentMonthlyBalance'));
})->middleware('auth')->name('accounting.send-cash');

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

    $liquidations = buildLiquidationQueueRecords()
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
        ->first();

    abort_if(!$selectedEmployee, 404);

    $liquidationRecords = buildLiquidationTrackingRecords((int) $selectedEmployee->id);

    return view('accounting.liquidation', compact('liquidationRecords', 'selectedEmployee'));
})->middleware('auth')->name('accounting.liquidation.employee');

Route::get('/accounting/liquidate-expenses', [\App\Http\Controllers\AccountingController::class, 'liquidateExpenses'])->middleware('auth')->name('accounting.liquidate-expenses');
Route::post('/accounting/liquidate-expenses/expense', [\App\Http\Controllers\AccountingController::class, 'storeExpense'])->middleware('auth')->name('accounting.store-expense');
Route::post('/accounting/liquidate-expenses/import', [\App\Http\Controllers\AccountingController::class, 'importExpenses'])->middleware('auth')->name('accounting.import-expenses');
Route::get('/accounting/liquidate-expenses/expense/{id}/breakdown', [\App\Http\Controllers\AccountingController::class, 'showExpenseBreakdown'])->middleware('auth')->name('accounting.show-expense-breakdown');
Route::patch('/accounting/liquidate-expenses/expense/{id}/category', [\App\Http\Controllers\AccountingController::class, 'updateExpenseCategory'])->middleware('auth')->name('accounting.update-expense-category');
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

    $summaryMonths = collect(range(1, 12))
        ->map(function (int $month) {
            $date = \Carbon\Carbon::createFromDate(2026, $month, 1);

            return [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y'),
            ];
        });
    $defaultSummaryMonth = now()->year === 2026
        ? now()->format('Y-m')
        : '2026-01';

    return view('accounting.summary', compact('employees', 'categories', 'summaryMonths', 'defaultSummaryMonth'));
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
    $transactionTypeSql = "CASE WHEN LOWER(COALESCE(cash_advance_requests.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END";
    $transactionAmountSql = 'COALESCE(cash_advance_requests.approved_amount, cash_advance_requests.requested_amount, 0)';
    $categoryName = null;

    try {
        $periodDate = $fromDate
            ? \Carbon\Carbon::parse($fromDate)->startOfMonth()
            : (now()->year === 2026 ? now()->startOfMonth() : \Carbon\Carbon::createFromDate(2026, 1, 1));
    } catch (\Throwable $exception) {
        $periodDate = \Carbon\Carbon::createFromDate(2026, 1, 1);
    }

    if ((int) $periodDate->year !== 2026) {
        $periodDate = \Carbon\Carbon::createFromDate(2026, 1, 1);
    }

    $fromDate = $periodDate->toDateString();
    $toDate = $periodDate->copy()->endOfMonth()->toDateString();

    if ($categoryId) {
        $categoryName = DB::table('categories')
            ->where('id', $categoryId)
            ->value('particulars_category');
    }

    // Match the Recorded Transactions source used by Accounting > Liquidate Expenses.
    $expenseQuery = DB::table('cash_advance_requests')
        ->leftJoin('users', 'cash_advance_requests.requester_id', '=', 'users.id');

    // Apply filters
    if ($employeeId) {
        $expenseQuery->where('cash_advance_requests.requester_id', $employeeId);
    }

    $expenseQuery->whereBetween('cash_advance_requests.request_date', [$fromDate, $toDate]);

    $effectiveAmountSql = $transactionAmountSql;
    $categoryParticularSql = 'cash_advance_requests.purpose';
    $categoryDescriptionSql = 'cash_advance_requests.accounting_remarks';
    $categoryNameSql = 'cash_advance_requests.category';

    if ($categoryId) {
        if ($categoryName) {
            $allBreakdownsSubquery = DB::table('liquidation_expenses')
                ->whereNotNull('cash_advance_request_id')
                ->groupBy('cash_advance_request_id')
                ->selectRaw('
                    cash_advance_request_id,
                    COUNT(*) as breakdown_count,
                    SUM(amount) as breakdown_amount
                ');

            $categoryBreakdownsSubquery = DB::table('liquidation_expenses')
                ->whereNotNull('cash_advance_request_id')
                ->where('category_id', $categoryId)
                ->groupBy('cash_advance_request_id')
                ->selectRaw("
                    cash_advance_request_id,
                    COUNT(*) as matching_count,
                    SUM(amount) as matching_amount,
                    GROUP_CONCAT(NULLIF(transaction_details, '') ORDER BY id SEPARATOR ', ') as matching_particular_name,
                    GROUP_CONCAT(NULLIF(description, '') ORDER BY id SEPARATOR ', ') as matching_description
                ");

            $expenseQuery
                ->leftJoinSub($allBreakdownsSubquery, 'summary_breakdowns', function ($join) {
                    $join->on('summary_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
                })
                ->leftJoinSub($categoryBreakdownsSubquery, 'summary_category_breakdowns', function ($join) {
                    $join->on('summary_category_breakdowns.cash_advance_request_id', '=', 'cash_advance_requests.id');
                })
                ->where(function ($query) use ($categoryName) {
                    $query->where(function ($breakdownQuery) {
                        $breakdownQuery->whereRaw('COALESCE(summary_breakdowns.breakdown_count, 0) > 0')
                            ->whereRaw('COALESCE(summary_category_breakdowns.matching_amount, 0) > 0');
                    })->orWhere(function ($transactionQuery) use ($categoryName) {
                        $transactionQuery->whereRaw('COALESCE(summary_breakdowns.breakdown_count, 0) = 0')
                            ->where('cash_advance_requests.category', $categoryName);
                    });
                });

            $hasBreakdownSql = 'COALESCE(summary_breakdowns.breakdown_count, 0) > 0';
            $effectiveAmountSql = "CASE WHEN {$hasBreakdownSql} THEN COALESCE(summary_category_breakdowns.matching_amount, 0) ELSE {$transactionAmountSql} END";
            $categoryParticularSql = "CASE WHEN {$hasBreakdownSql} THEN COALESCE(summary_category_breakdowns.matching_particular_name, cash_advance_requests.purpose) ELSE cash_advance_requests.purpose END";
            $categoryDescriptionSql = "CASE WHEN {$hasBreakdownSql} THEN COALESCE(summary_category_breakdowns.matching_description, cash_advance_requests.accounting_remarks) ELSE cash_advance_requests.accounting_remarks END";
            $categoryNameSql = "'" . str_replace("'", "''", $categoryName) . "'";
        } else {
            $expenseQuery->whereRaw('1 = 0');
        }
    }

    // Get summary data before pagination
    $summaryQuery = clone $expenseQuery;
    $summary = $summaryQuery->selectRaw("
        COUNT(*) as total_count,
        SUM(CASE WHEN {$transactionTypeSql} = 'credit' THEN {$effectiveAmountSql} ELSE 0 END) as total_credits,
        SUM(CASE WHEN {$transactionTypeSql} = 'debit' THEN {$effectiveAmountSql} ELSE 0 END) as total_debits
    ")->first();

    $summary->total_credits = $summary->total_credits ?? 0;
    $summary->total_debits = $summary->total_debits ?? 0;
    $summary->net_amount = $summary->total_debits - $summary->total_credits;
    $summary->total_category_amount = (float) $summary->total_debits + (float) $summary->total_credits;
    $summary->selected_category_name = $categoryName;

    // Get paginated expenses
    $totalExpenses = (clone $expenseQuery)->count();
    $totalPages = max(1, (int) ceil($totalExpenses / $perPage));
    $page = min($page, $totalPages);
    $balance = AccountingMonthlyBalance::forMonth($periodDate);

    if ($categoryId) {
        $balance['debit_total'] = round((float) $summary->total_debits, 2);
        $balance['credit_total'] = round((float) $summary->total_credits, 2);
        $balance['expense_total'] = round((float) $summary->net_amount, 2);
        $balance['remaining_balance'] = round((float) $balance['opening_balance'] - (float) $summary->net_amount, 2);
        $balance['ending_balance'] = $balance['remaining_balance'];
    }

    $expensesQuery = (clone $expenseQuery)
        ->select(
            'cash_advance_requests.request_date as expense_date',
            'users.name as employee_name',
            DB::raw("{$transactionTypeSql} as transaction_type"),
            DB::raw("{$categoryDescriptionSql} as description"),
            DB::raw("{$categoryParticularSql} as transaction_details"),
            DB::raw("{$effectiveAmountSql} as amount"),
            DB::raw("{$categoryParticularSql} as particular_name"),
            DB::raw("{$categoryNameSql} as category_name"),
            DB::raw("CASE WHEN {$transactionTypeSql} = 'credit' THEN {$effectiveAmountSql} ELSE 0 END as credit"),
            DB::raw("CASE WHEN {$transactionTypeSql} = 'debit' THEN {$effectiveAmountSql} ELSE 0 END as debit")
        )
        ->orderByDesc('cash_advance_requests.request_date')
        ->orderByDesc('cash_advance_requests.id');

    $expenses = $showAll
        ? $expensesQuery->get()
        : $expensesQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();

    return response()->json([
        'expenses' => $expenses,
        'summary' => $summary,
        'balance' => $balance,
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
