<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('liquidation_expenses') || !Schema::hasTable('particulars')) {
            return;
        }

        if (!Schema::hasColumn('liquidation_expenses', 'particular_id')) {
            Schema::table('liquidation_expenses', function (Blueprint $table) {
                $table->foreignId('particular_id')
                    ->nullable()
                    ->after('expense_date')
                    ->constrained('particulars')
                    ->nullOnDelete();
            });
        }

        $defaultParticularId = DB::table('particulars')->orderBy('id')->value('id');

        if ($defaultParticularId) {
            DB::table('liquidation_expenses')
                ->whereNull('particular_id')
                ->update(['particular_id' => $defaultParticularId]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('liquidation_expenses') || !Schema::hasColumn('liquidation_expenses', 'particular_id')) {
            return;
        }

        Schema::table('liquidation_expenses', function (Blueprint $table) {
            $table->dropForeign(['particular_id']);
            $table->dropColumn('particular_id');
        });
    }
};
