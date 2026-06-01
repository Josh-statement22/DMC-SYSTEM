<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_wallet_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_type', 40);
            $table->string('reference', 120);
            $table->string('name');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['account_type', 'reference'], 'accounting_wallet_accounts_type_reference_unique');
        });

        Schema::create('accounting_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('transaction_type', 20);
            $table->unsignedBigInteger('cash_advance_request_id')->nullable()->index();
            $table->unsignedBigInteger('liquidation_expense_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['transaction_type', 'created_at']);
        });

        Schema::create('accounting_journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('accounting_journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounting_wallet_accounts')->restrictOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->index(['account_id', 'journal_entry_id']);
        });

        Schema::create('accounting_wallet_funding_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_account_id')->nullable()->constrained('accounting_wallet_accounts')->cascadeOnDelete();
            $table->foreignId('source_account_id')->constrained('accounting_wallet_accounts')->cascadeOnDelete();
            $table->unsignedSmallInteger('priority')->default(100);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['destination_account_id', 'source_account_id'], 'accounting_wallet_funding_destination_source_unique');
            $table->index(['enabled', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_wallet_funding_sources');
        Schema::dropIfExists('accounting_journal_lines');
        Schema::dropIfExists('accounting_journal_entries');
        Schema::dropIfExists('accounting_wallet_accounts');
    }
};
