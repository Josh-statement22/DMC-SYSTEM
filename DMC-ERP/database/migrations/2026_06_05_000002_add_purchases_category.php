<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('categories')->where('particulars_category', 'Purchases')->exists()) {
            DB::table('categories')
                ->where('particulars_category', 'Purchases')
                ->update(['updated_at' => now()]);

            return;
        }

        DB::table('categories')->insert([
            'particulars_category' => 'Purchases',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('particulars_category', 'Purchases')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cash_advance_requests')
                    ->whereColumn('cash_advance_requests.category_id', 'categories.id');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('liquidation_expenses')
                    ->whereColumn('liquidation_expenses.category_id', 'categories.id');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('particulars')
                    ->whereColumn('particulars.category_id', 'categories.id');
            })
            ->delete();
    }
};
