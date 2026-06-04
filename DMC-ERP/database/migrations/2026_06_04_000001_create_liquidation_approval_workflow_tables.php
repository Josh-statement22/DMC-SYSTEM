<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('liquidation_breakdowns')) {
            Schema::create('liquidation_breakdowns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('liquidation_id')->constrained('liquidations')->cascadeOnDelete();
                $table->foreignId('liquidation_expense_id')->nullable()->constrained('liquidation_expenses')->cascadeOnDelete();
                $table->date('date');
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('category')->nullable();
                $table->string('particulars');
                $table->decimal('amount', 12, 2);
                $table->text('remarks')->nullable();
                $table->foreignId('posted_cash_advance_request_id')->nullable()->constrained('cash_advance_requests')->nullOnDelete();
                $table->timestamps();

                $table->index(['liquidation_id', 'liquidation_expense_id'], 'idx_liq_breakdowns_parent');
                $table->index('posted_cash_advance_request_id', 'idx_liq_breakdowns_posted_request');
            });
        }

        if (! Schema::hasTable('liquidation_submissions')) {
            Schema::create('liquidation_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('liquidation_id')->constrained('liquidations')->cascadeOnDelete();
                $table->foreignId('liquidation_expense_id')->nullable()->constrained('liquidation_expenses')->cascadeOnDelete();
                $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status')->default('Draft');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();

                $table->unique('liquidation_expense_id', 'uniq_liq_submission_expense');
                $table->index(['status', 'created_at'], 'idx_liq_submission_status_created');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidation_submissions');
        Schema::dropIfExists('liquidation_breakdowns');
    }
};
