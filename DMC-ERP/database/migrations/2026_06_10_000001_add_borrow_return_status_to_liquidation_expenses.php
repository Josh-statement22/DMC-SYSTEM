<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('liquidation_expenses')
            || Schema::hasColumn('liquidation_expenses', 'borrow_return_status')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->string('borrow_return_status', 30)->nullable()->after('receipt_path');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('liquidation_expenses')
            || ! Schema::hasColumn('liquidation_expenses', 'borrow_return_status')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropColumn('borrow_return_status');
        });
    }
};
