<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Analysis for January 2026
$month = 'January 2026';
$monthDate = Carbon::createFromFormat('F Y', $month)->startOfMonth();
$monthStart = $monthDate->toDateString();
$monthEnd = $monthDate->endOfMonth()->toDateString();

echo "\n";
echo str_repeat("=", 90);
echo "\nFORENSIC RECONCILIATION ANALYSIS - $month\n";
echo str_repeat("=", 90);
echo "\n\n";

// ============================================================================
// 1. IDENTIFY ALL TRANSACTIONS FROM BOTH SOURCES
// ============================================================================
echo "1. TRANSACTION SOURCE ANALYSIS\n";
echo str_repeat("-", 90);

// Get cash_advance_requests for the period (source: GoTyme)
$cashAdvanceRequests = DB::table('cash_advance_requests as car')
    ->leftJoin('users', 'car.requester_id', '=', 'users.id')
    ->whereBetween('car.request_date', [$monthStart, $monthEnd])
    ->select(
        'car.id',
        'car.reference_no',
        'car.request_date',
        'car.requested_amount',
        'car.approved_amount',
        'car.status',
        DB::raw("CASE WHEN LOWER(COALESCE(car.accounting_remarks, '')) LIKE '%manual credit entry%' THEN 'credit' ELSE 'debit' END as transaction_type"),
        'car.purpose',
        'car.accounting_remarks',
        'users.name as employee_name',
        DB::raw("'cash_advance_requests' as source")
    )
    ->orderBy('car.request_date')
    ->get();

// Get liquidation_expenses (source: Liquidation Summary)
$liquidationExpenses = DB::table('liquidation_expenses as le')
    ->leftJoin('liquidations', 'le.liquidation_id', '=', 'liquidations.id')
    ->leftJoin('users', 'liquidations.user_id', '=', 'users.id')
    ->leftJoin('categories', 'le.category_id', '=', 'categories.id')
    ->whereBetween('le.expense_date', [$monthStart, $monthEnd])
    ->select(
        'le.id',
        'le.liquidation_id',
        'le.expense_date',
        'le.amount',
        'le.transaction_type',
        'le.transaction_details',
        'le.description',
        'le.cash_advance_request_id',
        'categories.particulars_category as category_name',
        'users.name as employee_name',
        DB::raw("'liquidation_expenses' as source")
    )
    ->orderBy('le.expense_date')
    ->get();

echo "\nCash Advance Requests (GoTyme Source): " . count($cashAdvanceRequests) . " transactions\n";
echo "Liquidation Expenses (Summary Source): " . count($liquidationExpenses) . " transactions\n\n";

// ============================================================================
// 2. OPENING BALANCE VERIFICATION
// ============================================================================
echo "\n2. OPENING BALANCE VERIFICATION\n";
echo str_repeat("-", 90);

$storedBalance = DB::table('cash_advance_monthly_balances')
    ->where('year', $monthDate->year)
    ->where('month', $monthDate->month)
    ->first();

$openingBalance = $storedBalance->opening_balance ?? 0;
echo "Stored Opening Balance: PHP " . number_format($openingBalance, 2) . "\n";
echo "GoTyme Expected Opening Balance: PHP 274,647.48\n";
echo "Match: " . ($openingBalance == 274647.48 ? "✓ YES" : "✗ NO") . "\n";

// ============================================================================
// 3. CALCULATE TOTALS BOTH WAYS
// ============================================================================
echo "\n3. BALANCE CALCULATION - MULTIPLE METHODS\n";
echo str_repeat("-", 90);

// METHOD A: From cash_advance_requests
$debitsFromCAR = $cashAdvanceRequests
    ->where('transaction_type', 'debit')
    ->sum(function($item) {
        return (float)($item->approved_amount ?? $item->requested_amount ?? 0);
    });

$creditsFromCAR = $cashAdvanceRequests
    ->where('transaction_type', 'credit')
    ->sum(function($item) {
        return (float)($item->approved_amount ?? $item->requested_amount ?? 0);
    });

$endingFromCAR = $openingBalance - ($debitsFromCAR - $creditsFromCAR);

echo "\nMETHOD A: From cash_advance_requests\n";
echo "  Debits (cash out):  PHP " . number_format($debitsFromCAR, 2) . "\n";
echo "  Credits (cash in):  PHP " . number_format($creditsFromCAR, 2) . "\n";
echo "  Expense Total:      PHP " . number_format($debitsFromCAR - $creditsFromCAR, 2) . "\n";
echo "  Ending Balance:     PHP " . number_format($endingFromCAR, 2) . "\n";

// METHOD B: From liquidation_expenses
$debitsFromLE = $liquidationExpenses
    ->where('transaction_type', 'debit')
    ->sum('amount');

$creditsFromLE = $liquidationExpenses
    ->where('transaction_type', 'credit')
    ->sum('amount');

$endingFromLE = $openingBalance - ($debitsFromLE - $creditsFromLE);

echo "\nMETHOD B: From liquidation_expenses\n";
echo "  Debits (cash out):  PHP " . number_format($debitsFromLE, 2) . "\n";
echo "  Credits (cash in):  PHP " . number_format($creditsFromLE, 2) . "\n";
echo "  Expense Total:      PHP " . number_format($debitsFromLE - $creditsFromLE, 2) . "\n";
echo "  Ending Balance:     PHP " . number_format($endingFromLE, 2) . "\n";

// Expected vs Actual
echo "\nEXPECTED vs ACTUAL:\n";
echo "  GoTyme Expected Ending:       PHP 117,425.98\n";
echo "  Liquidation Summary Ending:   PHP 107,425.98\n";
echo "  Variance (exact):             PHP 10,000.00\n";

echo "\n  Calculated from CAR:          PHP " . number_format($endingFromCAR, 2) . "\n";
echo "  Calculated from LE:           PHP " . number_format($endingFromLE, 2) . "\n";
echo "  Difference between methods:   PHP " . number_format(abs($endingFromCAR - $endingFromLE), 2) . "\n";

// ============================================================================
// 4. IDENTIFY PHP 10,000 TRANSACTIONS
// ============================================================================
echo "\n4. TRANSACTIONS MATCHING PHP 10,000\n";
echo str_repeat("-", 90);

$tenThousandCAR = $cashAdvanceRequests->filter(function($item) {
    $amount = (float)($item->approved_amount ?? $item->requested_amount ?? 0);
    return abs($amount - 10000) < 0.01;
});

$tenThousandLE = $liquidationExpenses->filter(function($item) {
    return abs((float)$item->amount - 10000) < 0.01;
});

echo "\nCash Advance Requests with PHP 10,000:\n";
foreach ($tenThousandCAR as $idx => $item) {
    echo "  " . ($idx + 1) . ". Date: " . $item->request_date . " | Type: " . $item->transaction_type 
        . " | Emp: " . $item->employee_name . " | Purpose: " . substr($item->purpose, 0, 40) . "\n";
    echo "     Status: " . $item->status . " | Remarks: " . substr($item->accounting_remarks ?? '', 0, 50) . "\n";
}

echo "\nLiquidation Expenses with PHP 10,000:\n";
foreach ($tenThousandLE as $idx => $item) {
    echo "  " . ($idx + 1) . ". Date: " . $item->expense_date . " | Type: " . $item->transaction_type 
        . " | Emp: " . $item->employee_name . "\n";
    echo "     Details: " . substr($item->transaction_details ?? '', 0, 40) 
        . " | CAR ID: " . ($item->cash_advance_request_id ?? 'NULL') . "\n";
}

// ============================================================================
// 5. DUPLICATE DETECTION
// ============================================================================
echo "\n5. DUPLICATE & LINKAGE ANALYSIS\n";
echo str_repeat("-", 90);

// Check for transactions linked via cash_advance_request_id
$linkedTransactions = DB::table('liquidation_expenses')
    ->whereNotNull('cash_advance_request_id')
    ->whereBetween('expense_date', [$monthStart, $monthEnd])
    ->select('cash_advance_request_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
    ->groupBy('cash_advance_request_id')
    ->having('count', '>', 1)
    ->get();

if (count($linkedTransactions) > 0) {
    echo "\nTransactions with Multiple Liquidation Expenses (possible split):\n";
    foreach ($linkedTransactions as $link) {
        $carInfo = DB::table('cash_advance_requests')
            ->where('id', $link->cash_advance_request_id)
            ->select('id', 'reference_no', 'approved_amount', 'requested_amount')
            ->first();
        echo "  CAR ID {$link->cash_advance_request_id}: {$link->count} expenses totaling PHP " 
            . number_format($link->total, 2) 
            . " (CAR Amount: PHP " . number_format($carInfo->approved_amount ?? $carInfo->requested_amount, 2) . ")\n";
    }
}

// ============================================================================
// 6. ORPHAN TRANSACTIONS
// ============================================================================
echo "\n6. ORPHAN TRANSACTIONS (Possible Issues)\n";
echo str_repeat("-", 90);

// Liquidation expenses without cash_advance_request_id
$orphanLE = DB::table('liquidation_expenses as le')
    ->join('liquidations', 'le.liquidation_id', '=', 'liquidations.id')
    ->leftJoin('users', 'liquidations.user_id', '=', 'users.id')
    ->whereNull('le.cash_advance_request_id')
    ->whereBetween('le.expense_date', [$monthStart, $monthEnd])
    ->select('le.id', 'le.expense_date', 'le.amount', 'le.transaction_type', 
             'le.transaction_details', 'users.name as employee_name')
    ->get();

if (count($orphanLE) > 0) {
    echo "\nLiquidation Expenses without cash_advance_request_id link:\n";
    $orphanTotal = 0;
    foreach ($orphanLE as $item) {
        echo "  - " . $item->expense_date . " | " . $item->employee_name 
            . " | PHP " . number_format($item->amount, 2) 
            . " | Type: " . $item->transaction_type . " | Details: " . substr($item->transaction_details ?? '', 0, 30) . "\n";
        $orphanTotal += $item->amount;
    }
    echo "  TOTAL ORPHAN EXPENSES: PHP " . number_format($orphanTotal, 2) . "\n";
} else {
    echo "\nNo orphan liquidation expenses found.\n";
}

// ============================================================================
// 7. SIGN ERROR ANALYSIS
// ============================================================================
echo "\n7. SIGN ERROR ANALYSIS (Debit vs Credit Mismatch)\n";
echo str_repeat("-", 90);

$debitsIncludingOrphans = $debitsFromLE + $orphanLE->where('transaction_type', 'debit')->sum('amount');
$creditsIncludingOrphans = $creditsFromLE + $orphanLE->where('transaction_type', 'credit')->sum('amount');

echo "\nDebit Totals:\n";
echo "  From CAR (expected):        PHP " . number_format($debitsFromCAR, 2) . "\n";
echo "  From LE:                    PHP " . number_format($debitsFromLE, 2) . "\n";
echo "  Difference:                 PHP " . number_format($debitsFromCAR - $debitsFromLE, 2) . "\n";

echo "\nCredit Totals:\n";
echo "  From CAR (expected):        PHP " . number_format($creditsFromCAR, 2) . "\n";
echo "  From LE:                    PHP " . number_format($creditsFromLE, 2) . "\n";
echo "  Difference:                 PHP " . number_format($creditsFromCAR - $creditsFromLE, 2) . "\n";

// ============================================================================
// 8. DETAILED TRANSACTION LISTING
// ============================================================================
echo "\n8. DETAILED TRANSACTION LISTINGS\n";
echo str_repeat("-", 90);

echo "\nCASH ADVANCE REQUESTS (GoTyme Source):\n";
echo str_repeat("-", 90);
printf("%-3s | %-10s | %-6s | %-10s | %-20s | %-30s\n", "ID", "Date", "Type", "Amount", "Employee", "Purpose");
echo str_repeat("-", 90);
foreach ($cashAdvanceRequests as $item) {
    $amount = (float)($item->approved_amount ?? $item->requested_amount ?? 0);
    printf("%-3s | %-10s | %-6s | %10.2f | %-20s | %-30s\n",
        $item->id,
        $item->request_date,
        strtoupper(substr($item->transaction_type, 0, 1)),
        $amount,
        substr($item->employee_name ?? 'N/A', 0, 20),
        substr($item->purpose, 0, 30)
    );
}

echo "\n\nLIQUIDATION EXPENSES (Summary Source):\n";
echo str_repeat("-", 90);
printf("%-3s | %-10s | %-6s | %10s | %-20s | %-30s | %-5s\n", "ID", "Date", "Type", "Amount", "Employee", "Details", "CARID");
echo str_repeat("-", 90);
foreach ($liquidationExpenses as $item) {
    printf("%-3s | %-10s | %-6s | %10.2f | %-20s | %-30s | %-5s\n",
        $item->id,
        $item->expense_date,
        strtoupper(substr($item->transaction_type, 0, 1)),
        (float)$item->amount,
        substr($item->employee_name ?? 'N/A', 0, 20),
        substr($item->transaction_details ?? 'N/A', 0, 30),
        $item->cash_advance_request_id ?? 'NULL'
    );
}

// ============================================================================
// 9. MATCHING ANALYSIS
// ============================================================================
echo "\n\n9. MATCHING ANALYSIS\n";
echo str_repeat("-", 90);

$matchedPairs = [];
$unmatchedCAR = clone $cashAdvanceRequests;
$unmatchedLE = clone $liquidationExpenses;

foreach ($cashAdvanceRequests as $car) {
    $carAmount = (float)($car->approved_amount ?? $car->requested_amount ?? 0);
    
    foreach ($liquidationExpenses as $le) {
        $leAmount = (float)$le->amount;
        
        // Check if amounts match and cash_advance_request_id is set
        if (abs($carAmount - $leAmount) < 0.01 && $le->cash_advance_request_id == $car->id) {
            $matchedPairs[] = [
                'car_id' => $car->id,
                'le_id' => $le->id,
                'amount' => $carAmount,
                'car_date' => $car->request_date,
                'le_date' => $le->expense_date,
                'type_match' => $car->transaction_type === $le->transaction_type
            ];
            $unmatchedCAR = $unmatchedCAR->where('id', '!=', $car->id)->values();
            $unmatchedLE = $unmatchedLE->where('id', '!=', $le->id)->values();
            break;
        }
    }
}

echo "\nMatched Pairs (CAR <-> LE): " . count($matchedPairs) . "\n";
echo "Unmatched CAR Records: " . count($unmatchedCAR) . "\n";
echo "Unmatched LE Records: " . count($unmatchedLE) . "\n";

if (count($unmatchedCAR) > 0) {
    echo "\nUnmatched CAR Transactions:\n";
    foreach ($unmatchedCAR as $car) {
        $amount = (float)($car->approved_amount ?? $car->requested_amount ?? 0);
        echo "  CAR {$car->id} - " . $car->request_date . " - PHP " . number_format($amount, 2) 
            . " ({$car->transaction_type}) - " . ($car->employee_name ?? 'N/A') . "\n";
    }
}

if (count($unmatchedLE) > 0) {
    echo "\nUnmatched LE Transactions:\n";
    foreach ($unmatchedLE as $le) {
        echo "  LE {$le->id} - " . $le->expense_date . " - PHP " . number_format($le->amount, 2) 
            . " ({$le->transaction_type}) - CARID: " . ($le->cash_advance_request_id ?? 'NULL') . "\n";
    }
}

// ============================================================================
// 10. ROOT CAUSE HYPOTHESIS
// ============================================================================
echo "\n\n10. ROOT CAUSE ANALYSIS\n";
echo str_repeat("-", 90);

$variance = abs($endingFromCAR - $endingFromLE);
echo "\nPrimary Variance: PHP " . number_format($variance, 2) . "\n";
echo "Variance as % of opening balance: " . number_format(($variance / $openingBalance) * 100, 2) . "%\n";

$unaccountedDebits = $debitsFromCAR - $debitsFromLE;
$unaccountedCredits = $creditsFromCAR - $creditsFromLE;

if (abs($unaccountedDebits - 10000) < 0.01) {
    echo "\n⚠ HYPOTHESIS: Missing PHP 10,000 DEBIT in liquidation_expenses\n";
    echo "   - GoTyme has PHP " . number_format($debitsFromCAR, 2) . " in debits\n";
    echo "   - Summary has PHP " . number_format($debitsFromLE, 2) . " in debits\n";
    echo "   - Missing: PHP " . number_format($unaccountedDebits, 2) . "\n";
} elseif (abs($unaccountedCredits + 10000) < 0.01) {
    echo "\n⚠ HYPOTHESIS: Extra PHP 10,000 CREDIT in liquidation_expenses\n";
    echo "   - GoTyme has PHP " . number_format($creditsFromCAR, 2) . " in credits\n";
    echo "   - Summary has PHP " . number_format($creditsFromLE, 2) . " in credits\n";
    echo "   - Extra: PHP " . number_format($creditsFromLE - $creditsFromCAR, 2) . "\n";
}

echo "\n" . str_repeat("=", 90) . "\n";
echo "END OF FORENSIC ANALYSIS\n";
echo str_repeat("=", 90) . "\n\n";
