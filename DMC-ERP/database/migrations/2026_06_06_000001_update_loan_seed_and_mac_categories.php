<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        if (DB::table('categories')->where('particulars_category', 'Loan')->exists()) {
            DB::table('categories')
                ->where('particulars_category', 'Loan')
                ->update(['updated_at' => $now]);
        } else {
            DB::table('categories')->insert([
                'particulars_category' => 'Loan',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $seed = $this->categoryByExactName('seed');
        $capitalizedSeed = $this->categoryByExactName('Seed');

        if ($seed && $capitalizedSeed && $seed->id !== $capitalizedSeed->id) {
            DB::table('cash_advance_requests')->where('category_id', $seed->id)->update(['category_id' => $capitalizedSeed->id]);
            DB::table('liquidation_expenses')->where('category_id', $seed->id)->update(['category_id' => $capitalizedSeed->id]);
            DB::table('particulars')->where('category_id', $seed->id)->update(['category_id' => $capitalizedSeed->id]);
            DB::table('categories')->where('id', $seed->id)->delete();
            DB::table('categories')->where('id', $capitalizedSeed->id)->update(['updated_at' => $now]);
        } elseif ($seed && $capitalizedSeed) {
            DB::table('categories')
                ->where('id', $seed->id)
                ->update([
                    'particulars_category' => 'Seed',
                    'updated_at' => $now,
                ]);
        } elseif ($seed) {
            DB::table('categories')
                ->where('id', $seed->id)
                ->update([
                    'particulars_category' => 'Seed',
                    'updated_at' => $now,
                ]);
        } elseif ($capitalizedSeed) {
            DB::table('categories')->where('id', $capitalizedSeed->id)->update(['updated_at' => $now]);
        }

        $macId = DB::table('categories')->where('particulars_category', 'MAC')->value('id');

        if ($macId) {
            DB::table('cash_advance_requests')->where('category_id', $macId)->update(['category_id' => null]);
            DB::table('liquidation_expenses')->where('category_id', $macId)->update(['category_id' => null]);
            DB::table('particulars')->where('category_id', $macId)->update(['category_id' => null]);
            DB::table('categories')->where('id', $macId)->delete();
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('particulars_category', 'Loan')
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

        if (! DB::table('categories')->where('particulars_category', 'MAC')->exists()) {
            DB::table('categories')->insert([
                'particulars_category' => 'MAC',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (
            $this->categoryByExactName('Seed')
            && ! $this->categoryByExactName('seed')
        ) {
            $seed = $this->categoryByExactName('Seed');

            DB::table('categories')
                ->where('id', $seed->id)
                ->update([
                    'particulars_category' => 'seed',
                    'updated_at' => now(),
                ]);
        }
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
