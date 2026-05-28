<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('liquidation_expenses')) {
            return;
        }

        if (! Schema::hasColumn('liquidation_expenses', 'cash_advance_request_id')) {
            Schema::table('liquidation_expenses', function (Blueprint $table) {
                $table->foreignId('cash_advance_request_id')
                    ->nullable()
                    ->after('liquidation_id')
                    ->constrained('cash_advance_requests')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('liquidation_expenses') || ! Schema::hasColumn('liquidation_expenses', 'cash_advance_request_id')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropForeign(['cash_advance_request_id']);
            $table->dropColumn('cash_advance_request_id');
        });
    }
};