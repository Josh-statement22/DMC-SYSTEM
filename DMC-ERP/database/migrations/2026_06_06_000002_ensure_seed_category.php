<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $seed = $this->categoryByExactName('seed');
        $capitalizedSeed = $this->categoryByExactName('Seed');

        if ($capitalizedSeed) {
            DB::table('categories')->where('id', $capitalizedSeed->id)->update(['updated_at' => $now]);

            return;
        }

        if ($seed) {
            DB::table('categories')
                ->where('id', $seed->id)
                ->update([
                    'particulars_category' => 'Seed',
                    'updated_at' => $now,
                ]);

            return;
        }

        DB::table('categories')->insert([
            'particulars_category' => 'Seed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('id', optional($this->categoryByExactName('Seed'))->id)
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

    private function categoryByExactName(string $name): ?object
    {
        $query = DB::table('categories');

        if (DB::connection()->getDriverName() === 'sqlite') {
            return $query->where('particulars_category', $name)->first();
        }

        return $query->whereRaw('BINARY particulars_category = ?', [$name])->first();
    }
};
