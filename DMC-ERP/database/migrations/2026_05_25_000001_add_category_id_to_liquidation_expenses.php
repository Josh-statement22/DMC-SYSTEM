<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('liquidation_expenses')) {
            return;
        }

        if (!Schema::hasColumn('liquidation_expenses', 'category_id')) {
            Schema::table('liquidation_expenses', function (Blueprint $table) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('expense_date')
                    ->constrained('categories')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('liquidation_expenses', 'category_id')) {
            Schema::table('liquidation_expenses', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
    }
};
