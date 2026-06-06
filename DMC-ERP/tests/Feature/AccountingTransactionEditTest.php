<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingTransactionEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_accounting_can_edit_recorded_transaction_fields(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1004',
            'name' => 'Christine R.',
            'email' => 'christine@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2004',
            'name' => 'Accounting User',
            'email' => 'accounting-edit@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $categoryId = DB::table('categories')
            ->where('particulars_category', 'Purchases')
            ->value('id');

        $transactionId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => 'EDIT-001',
            'requester_id' => $employee->id,
            'requested_amount' => 100000,
            'approved_amount' => 100000,
            'purpose' => 'borrow',
            'category' => null,
            'category_id' => null,
            'request_date' => '2026-01-26',
            'date_needed' => '2026-01-26',
            'status' => 'approved',
            'accounting_remarks' => 'Manual Credit Entry - Imported from Excel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->actingAs($accounting)
            ->patchJson(route('accounting.update-expense', $transactionId), [
                'expense_date' => '2026-01-27',
                'employee_id' => $employee->id,
                'transaction_type' => 'credit',
                'purpose' => 'corrected borrow',
                'category_id' => $categoryId,
                'remarks' => 'Imported correction',
                'amount' => 125000,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('expense.transaction_type', 'credit')
            ->assertJsonPath('expense.category', 'Purchases')
            ->assertJsonPath('expense.amount', 125000);

        $this->assertDatabaseHas('cash_advance_requests', [
            'id' => $transactionId,
            'requester_id' => $employee->id,
            'requested_amount' => 125000,
            'approved_amount' => 125000,
            'purpose' => 'corrected borrow',
            'category_id' => $categoryId,
            'category' => 'Purchases',
            'request_date' => '2026-01-27',
            'date_needed' => '2026-01-27',
            'accounting_remarks' => 'Manual Credit Entry - Imported correction',
        ]);
    }

    public function test_debit_transaction_edit_requires_category(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1005',
            'name' => 'Juan Dela Cruz',
            'email' => 'juan-edit@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2005',
            'name' => 'Accounting User',
            'email' => 'accounting-edit-debit@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $transactionId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => 'EDIT-002',
            'requester_id' => $employee->id,
            'requested_amount' => 500,
            'approved_amount' => 500,
            'purpose' => 'cash advance',
            'category' => null,
            'category_id' => null,
            'request_date' => '2026-01-26',
            'date_needed' => '2026-01-26',
            'status' => 'approved',
            'accounting_remarks' => 'Manually Recorded',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this
            ->actingAs($accounting)
            ->patchJson(route('accounting.update-expense', $transactionId), [
                'expense_date' => '2026-01-26',
                'employee_id' => $employee->id,
                'transaction_type' => 'debit',
                'purpose' => 'cash advance',
                'category_id' => null,
                'remarks' => 'No category',
                'amount' => 500,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');
    }

    public function test_view_breakdown_only_returns_rows_linked_to_selected_cash_advance(): void
    {
        DB::table('roles')->insert([
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $employee = User::query()->create([
            'employee_id' => '1006',
            'name' => 'Breakdown Employee',
            'email' => 'breakdown-employee@example.com',
            'password' => 'password',
            'role_id' => 2,
        ]);

        $accounting = User::query()->create([
            'employee_id' => '2006',
            'name' => 'Accounting User',
            'email' => 'accounting-breakdown-view@example.com',
            'password' => 'password',
            'role_id' => 3,
        ]);

        $categoryId = DB::table('categories')
            ->where('particulars_category', 'Purchases')
            ->value('id');
        $particularId = DB::table('particulars')->insertGetId([
            'category_id' => $categoryId,
            'particular_name' => 'Test Particular',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cashAdvanceId = DB::table('cash_advance_requests')->insertGetId([
            'reference_no' => 'BD-001',
            'requester_id' => $employee->id,
            'requested_amount' => 10000,
            'approved_amount' => 10000,
            'purpose' => 'project funds',
            'category' => 'Purchases',
            'category_id' => $categoryId,
            'request_date' => '2026-06-01',
            'date_needed' => '2026-06-01',
            'status' => 'approved',
            'accounting_remarks' => 'Manually Recorded',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $liquidationId = DB::table('liquidations')->insertGetId([
            'user_id' => $employee->id,
            'cutoff_period' => 'June 2026',
            'amount' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('liquidation_expenses')->insert([
            [
                'liquidation_id' => $liquidationId,
                'cash_advance_request_id' => $cashAdvanceId,
                'expense_date' => '2026-06-01',
                'category_id' => $categoryId,
                'particular_id' => $particularId,
                'transaction_details' => 'linked expense',
                'description' => 'belongs to selected cash advance',
                'amount' => 2500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'liquidation_id' => $liquidationId,
                'cash_advance_request_id' => null,
                'expense_date' => '2026-06-01',
                'category_id' => $categoryId,
                'particular_id' => $particularId,
                'transaction_details' => 'legacy null-linked expense',
                'description' => 'must not appear in selected breakdown',
                'amount' => 1200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this
            ->actingAs($accounting)
            ->getJson(route('accounting.show-expense-breakdown', $cashAdvanceId))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'breakdowns')
            ->assertJsonPath('breakdowns.0.transaction_details', 'linked expense')
            ->assertJsonMissing([
                'transaction_details' => 'legacy null-linked expense',
            ]);
    }
}
