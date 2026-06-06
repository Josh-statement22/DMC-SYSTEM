<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('liquidation_expenses')
            || Schema::hasColumn('liquidation_expenses', 'category_id')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('expense_date');
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('liquidation_expenses', function (Blueprint $table) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('liquidation_expenses')
            || ! Schema::hasColumn('liquidation_expenses', 'category_id')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            if (DB::connection()->getDriverName() !== 'sqlite') {
                $table->dropForeign(['category_id']);
            }

            $table->dropColumn('category_id');
        });
    }
};
