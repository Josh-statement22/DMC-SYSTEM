<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingSummaryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_filter_uses_recorded_transaction_source_even_with_legacy_liquidation_rows(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1001',
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2001',
            'name' => 'Accounting User',
            'email' => 'accounting@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $travelCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Travel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $suppliesCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Supplies',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $suppliesParticularId = DB::table('particulars')->insertGetId([
            'category_id' => $suppliesCategoryId,
            'particular_name' => 'Printer ink',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cash_advance_requests')->insert([
            'reference_no' => 'CA-001',
            'requester_id' => $employee->id,
            'requested_amount' => 1200,
            'approved_amount' => 1200,
            'purpose' => 'Client visit',
            'category' => 'Travel',
            'category_id' => $travelCategoryId,
            'request_date' => '2026-06-10',
            'date_needed' => '2026-06-10',
            'status' => 'approved',
            'accounting_remarks' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $legacyLiquidationId = DB::table('liquidations')->insertGetId([
            'user_id' => $employee->id,
            'cutoff_period' => 'June 2026',
            'amount' => 500,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('liquidation_expenses')->insert([
            'liquidation_id' => $legacyLiquidationId,
            'cash_advance_request_id' => null,
            'expense_date' => '2026-06-12',
            'particular_id' => $suppliesParticularId,
            'category_id' => $suppliesCategoryId,
            'transaction_type' => 'debit',
            'transaction_details' => 'Printer ink',
            'description' => null,
            'amount' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($accounting)
            ->getJson(route('accounting.summary.data', [
                'from_date' => '2026-06-01',
                'to_date' => '2026-06-30',
                'category_id' => $travelCategoryId,
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('summary.total_count', 1)
            ->assertJsonPath('summary.total_debits', 1200)
            ->assertJsonPath('summary.selected_category_name', 'Travel')
            ->assertJsonPath('expenses.0.employee_name', 'Maria Santos')
            ->assertJsonPath('expenses.0.category_name', 'Travel')
            ->assertJsonPath('expenses.0.debit', 1200);
    }

    public function test_category_filter_can_be_combined_with_credit_type(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1002',
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2002',
            'name' => 'Accounting User',
            'email' => 'accounting-credit@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $refundCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Refund',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cash_advance_requests')->insert([
            'reference_no' => 'CR-001',
            'requester_id' => $employee->id,
            'requested_amount' => 250,
            'approved_amount' => 250,
            'purpose' => 'Returned excess cash',
            'category' => 'Refund',
            'category_id' => $refundCategoryId,
            'request_date' => '2026-06-14',
            'date_needed' => '2026-06-14',
            'status' => 'approved',
            'accounting_remarks' => 'Manual credit entry',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($accounting)
            ->getJson(route('accounting.summary.data', [
                'from_date' => '2026-06-01',
                'to_date' => '2026-06-30',
                'category_id' => $refundCategoryId,
                'type' => 'credit',
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('summary.total_count', 1)
            ->assertJsonPath('summary.total_credits', 250)
            ->assertJsonPath('summary.total_debits', 0)
            ->assertJsonPath('expenses.0.category_name', 'Refund')
            ->assertJsonPath('expenses.0.transaction_type', 'credit')
            ->assertJsonPath('expenses.0.credit', 250);
    }

    public function test_category_filter_matches_breakdowns_by_particular_category_when_expense_category_id_is_missing(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1003',
            'name' => 'Ana Reyes',
            'email' => 'ana@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2003',
            'name' => 'Accounting User',
            'email' => 'accounting-breakdown@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $travelCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Travel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $suppliesCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Supplies',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $travelParticularId = DB::table('particulars')->insertGetId([
            'category_id' => $travelCategoryId,
            'particular_name' => 'Taxi fare',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cashAdvanceRequestId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => 'CA-002',
            'requester_id' => $employee->id,
            'requested_amount' => 1000,
            'approved_amount' => 1000,
            'purpose' => 'Field work',
            'category' => null,
            'category_id' => null,
            'request_date' => '2026-06-18',
            'date_needed' => '2026-06-18',
            'status' => 'approved',
            'accounting_remarks' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cash_advance_requests')->insert([
            'reference_no' => 'CA-003',
            'requester_id' => $employee->id,
            'requested_amount' => 300,
            'approved_amount' => 300,
            'purpose' => 'Office supplies',
            'category' => 'Supplies',
            'category_id' => $suppliesCategoryId,
            'request_date' => '2026-06-19',
            'date_needed' => '2026-06-19',
            'status' => 'approved',
            'accounting_remarks' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $liquidationId = DB::table('liquidations')->insertGetId([
            'user_id' => $employee->id,
            'cutoff_period' => 'June 2026',
            'amount' => 1000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('liquidation_expenses')->insert([
            'liquidation_id' => $liquidationId,
            'cash_advance_request_id' => $cashAdvanceRequestId,
            'expense_date' => '2026-06-18',
            'particular_id' => $travelParticularId,
            'category_id' => null,
            'transaction_type' => 'debit',
            'transaction_details' => 'Taxi fare',
            'description' => 'Client site visit',
            'amount' => 700,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($accounting)
            ->getJson(route('accounting.summary.data', [
                'from_date' => '2026-06-01',
                'to_date' => '2026-06-30',
                'category_id' => $travelCategoryId,
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('summary.total_count', 1)
            ->assertJsonPath('summary.total_debits', 700)
            ->assertJsonPath('summary.selected_category_name', 'Travel')
            ->assertJsonPath('expenses.0.employee_name', 'Ana Reyes')
            ->assertJsonPath('expenses.0.category_name', 'Travel')
            ->assertJsonPath('expenses.0.particular_name', 'Taxi fare')
            ->assertJsonPath('expenses.0.debit', 700);
    }

    public function test_summary_filters_virtual_borrow_not_yet_spent_for_multiple_employees(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employeeOne = User::query()->create([
            'employee_id' => '1004',
            'name' => 'Liza Cruz',
            'email' => 'liza@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $employeeTwo = User::query()->create([
            'employee_id' => '1005',
            'name' => 'Mark Tan',
            'email' => 'mark@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $employeeThree = User::query()->create([
            'employee_id' => '1006',
            'name' => 'Outside Employee',
            'email' => 'outside@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2004',
            'name' => 'Accounting User',
            'email' => 'accounting-borrow-summary@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $borrowCategoryId = DB::table('categories')->insertGetId([
            'particulars_category' => 'Borrow',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $borrowParticularId = DB::table('particulars')->insertGetId([
            'category_id' => $borrowCategoryId,
            'particular_name' => 'Borrow',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cashAdvanceIds = collect([$employeeOne, $employeeTwo, $employeeThree])->map(function (User $employee, int $index) use ($borrowCategoryId) {
            return DB::table('cash_advance_requests')->insertGetId([
                'reference_no' => 'BR-SUM-' . ($index + 1),
                'requester_id' => $employee->id,
                'requested_amount' => 5000,
                'approved_amount' => 5000,
                'purpose' => 'Borrow tracking',
                'category' => 'Borrow',
                'category_id' => $borrowCategoryId,
                'request_date' => '2026-06-20',
                'date_needed' => '2026-06-20',
                'status' => 'approved',
                'accounting_remarks' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        foreach ([$employeeOne, $employeeTwo, $employeeThree] as $index => $employee) {
            $liquidationId = DB::table('liquidations')->insertGetId([
                'user_id' => $employee->id,
                'cutoff_period' => 'June 2026',
                'amount' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('liquidation_expenses')->insert([
                'liquidation_id' => $liquidationId,
                'cash_advance_request_id' => $cashAdvanceIds[$index],
                'expense_date' => '2026-06-20',
                'particular_id' => $borrowParticularId,
                'category_id' => $borrowCategoryId,
                'transaction_type' => 'debit',
                'transaction_details' => 'Not yet returned',
                'description' => null,
                'amount' => 1000 * ($index + 1),
                'borrow_return_status' => 'not_yet_returned',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this
            ->actingAs($accounting)
            ->getJson(route('accounting.summary.data', [
                'from_date' => '2026-06-01',
                'to_date' => '2026-06-30',
                'category_key' => 'borrow_not_yet_spent',
                'employee_ids' => [$employeeOne->id, $employeeTwo->id],
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('summary.total_count', 2)
            ->assertJsonPath('summary.total_debits', 3000)
            ->assertJsonPath('summary.selected_category_name', 'Borrow / Not yet spent')
            ->assertJsonPath('summary.selected_employee_name', 'Liza Cruz (1004), Mark Tan (1005)')
            ->assertJsonPath('expenses.0.category_name', 'Borrow / Not yet spent');
    }
}
