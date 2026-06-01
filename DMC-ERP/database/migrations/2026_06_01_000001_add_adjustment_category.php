<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('categories')->where('particulars_category', 'Adjustment')->exists()) {
            DB::table('categories')
                ->where('particulars_category', 'Adjustment')
                ->update(['updated_at' => now()]);

            return;
        }

        DB::table('categories')->insert([
            'particulars_category' => 'Adjustment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('particulars_category', 'Adjustment')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('liquidation_expenses')
                    ->whereColumn('liquidation_expenses.category_id', 'categories.id');
            })
            ->delete();
    }
};
