<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "\n";
echo str_repeat("=", 90);
echo "\nFOCUSED INVESTIGATION: CAR ID 997 vs 765 vs GoTyme Expected Balance\n";
echo str_repeat("=", 90);
echo "\n";

// Get the suspect transaction
$suspectCAR = DB::table('cash_advance_requests')
    ->where('id', 997)
    ->first();

$similarCAR = DB::table('cash_advance_requests')
    ->where('id', 765)
    ->first();

echo "SUSPECT TRANSACTION (CAR ID 997):\n";
echo str_repeat("-", 90);
echo "ID: " . $suspectCAR->id . "\n";
echo "Date: " . $suspectCAR->request_date . "\n";
echo "Employee ID: " . $suspectCAR->requester_id . "\n";
echo "Requested Amount: PHP " . number_format($suspectCAR->requested_amount, 2) . "\n";
echo "Approved Amount: PHP " . number_format($suspectCAR->approved_amount, 2) . "\n";
echo "Purpose: " . $suspectCAR->purpose . "\n";
echo "Status: " . $suspectCAR->status . "\n";
echo "Accounting Remarks: " . ($suspectCAR->accounting_remarks ?? 'NULL') . "\n";
echo "Request Date: " . $suspectCAR->request_date . "\n";
echo "Created At: " . $suspectCAR->created_at . "\n";

echo "\n\nSIMILAR TRANSACTION (CAR ID 765):\n";
echo str_repeat("-", 90);
echo "ID: " . $similarCAR->id . "\n";
echo "Date: " . $similarCAR->request_date . "\n";
echo "Employee ID: " . $similarCAR->requester_id . "\n";
echo "Requested Amount: PHP " . number_format($similarCAR->requested_amount, 2) . "\n";
echo "Approved Amount: PHP " . number_format($similarCAR->approved_amount, 2) . "\n";
echo "Purpose: " . $similarCAR->purpose . "\n";
echo "Status: " . $similarCAR->status . "\n";
echo "Accounting Remarks: " . ($similarCAR->accounting_remarks ?? 'NULL') . "\n";
echo "Request Date: " . $similarCAR->request_date . "\n";
echo "Created At: " . $similarCAR->created_at . "\n";

echo "\n\nCOMPARISON:\n";
echo str_repeat("-", 90);
echo "Same Employee: " . ($suspectCAR->requester_id === $similarCAR->requester_id ? "✓ YES" : "✗ NO") . "\n";
echo "Same Amount: " . ($suspectCAR->approved_amount === $similarCAR->approved_amount ? "✓ YES" : "✗ NO") . "\n";
echo "Same Purpose: " . ($suspectCAR->purpose === $similarCAR->purpose ? "✓ YES" : "✗ NO") . "\n";
echo "Days Apart: " . Carbon::parse($suspectCAR->request_date)->diffInDays(Carbon::parse($similarCAR->request_date)) . " days\n";

// Check if both are used in liquidation expenses
$caridUsage997 = DB::table('liquidation_expenses')
    ->where('cash_advance_request_id', 997)
    ->select(DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
    ->first();

$caridUsage765 = DB::table('liquidation_expenses')
    ->where('cash_advance_request_id', 765)
    ->select(DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
    ->first();

echo "\n\nLIQUIDATION EXPENSE USAGE:\n";
echo str_repeat("-", 90);
echo "CAR 997 used in " . $caridUsage997->count . " liquidation expenses (Total: PHP " . number_format($caridUsage997->total, 2) . ")\n";
echo "CAR 765 used in " . $caridUsage765->count . " liquidation expenses (Total: PHP " . number_format($caridUsage765->total, 2) . ")\n";

// Test deletion impact
echo "\n\nIMPACT OF DELETING CAR ID 997:\n";
echo str_repeat("-", 90);

$monthDate = Carbon::parse('2026-01-01')->startOfMonth();
$monthStart = $monthDate->toDateString();
$monthEnd = $monthDate->endOfMonth()->toDateString();

// Current state
$currentDebits = DB::table('cash_advance_requests')
    ->whereBetween('request_date', [$monthStart, $monthEnd])
    ->whereRaw('LOWER(COALESCE(accounting_remarks, "")) NOT LIKE ?', ['%manual credit entry%'])
    ->sum(DB::raw('COALESCE(approved_amount, requested_amount, 0)'));

$currentCredits = DB::table('cash_advance_requests')
    ->whereBetween('request_date', [$monthStart, $monthEnd])
    ->whereRaw('LOWER(COALESCE(accounting_remarks, "")) LIKE ?', ['%manual credit entry%'])
    ->sum(DB::raw('COALESCE(approved_amount, requested_amount, 0)'));

$openingBalance = 274647.48;
$currentEnding = $openingBalance - ($currentDebits - $currentCredits);

// If 997 is deleted
$withoutDebit = $currentDebits - 10000;
$newEnding = $openingBalance - ($withoutDebit - $currentCredits);

echo "Current state:\n";
echo "  Total Debits: PHP " . number_format($currentDebits, 2) . "\n";
echo "  Total Credits: PHP " . number_format($currentCredits, 2) . "\n";
echo "  Ending Balance: PHP " . number_format($currentEnding, 2) . "\n\n";

echo "After deleting CAR ID 997:\n";
echo "  Total Debits: PHP " . number_format($withoutDebit, 2) . "\n";
echo "  Total Credits: PHP " . number_format($currentCredits, 2) . "\n";
echo "  Ending Balance: PHP " . number_format($newEnding, 2) . "\n\n";

echo "GoTyme Expected: PHP 117,425.98\n";
echo "Match: " . (abs($newEnding - 117425.98) < 0.01 ? "✓ PERFECT MATCH!" : "✗ No match") . "\n";

// Check for other PHP 10,000 manual entries
echo "\n\nALL MANUAL ENTRIES WITH PHP 10,000:\n";
echo str_repeat("-", 90);

$manualTenK = DB::table('cash_advance_requests as car')
    ->join('users', 'car.requester_id', '=', 'users.id')
    ->whereBetween('car.request_date', [$monthStart, $monthEnd])
    ->where(DB::raw('COALESCE(car.approved_amount, car.requested_amount)'), 10000)
    ->whereRaw('LOWER(COALESCE(car.accounting_remarks, "")) LIKE ?', ['%manual%'])
    ->select('car.id', 'car.request_date', 'car.purpose', 'car.accounting_remarks', 'car.approved_amount', 'users.name')
    ->get();

if (count($manualTenK) > 0) {
    foreach ($manualTenK as $entry) {
        echo "CAR " . $entry->id . " - " . $entry->request_date . " - " . $entry->name . " - " . $entry->purpose . "\n";
        echo "  Remarks: " . $entry->accounting_remarks . "\n";
    }
} else {
    echo "Only 1 manual entry found: CAR 997\n";
}

echo "\n" . str_repeat("=", 90);
echo "\nCONCLUSION\n";
echo str_repeat("=", 90);
echo "\n";

if (abs($newEnding - 117425.98) < 0.01) {
    echo "✓ CONFIRMED: Deleting CAR ID 997 will resolve the PHP 10,000 variance!\n";
    echo "\nRECOMMENDATION:\n";
    echo "1. Verify CAR 997 in GoTyme statement - is it listed as a separate transaction?\n";
    echo "2. If GoTyme shows only ONE \"Jan 5 Jennifer M. OPEX PHP 10,000\", delete CAR 997\n";
    echo "3. If GoTyme shows TWO entries (Jan 5 and Jan 7), both are valid - investigate further\n";
} else {
    echo "⚠ CAR 997 deletion alone does not resolve the variance.\n";
    echo "The issue may involve multiple transactions or calculation errors.\n";
}

echo "\n" . str_repeat("=", 90) . "\n\n";
