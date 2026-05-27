<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('liquidation_expenses') || Schema::hasColumn('liquidation_expenses', 'receipt_path')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->string('receipt_path')->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('liquidation_expenses') || !Schema::hasColumn('liquidation_expenses', 'receipt_path')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropColumn('receipt_path');
        });
    }
};
