<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Tables', 'description' => 'All types of tables', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Chairs', 'description' => 'All types of chairs', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Cabinets', 'description' => 'All types of cabinets', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Desks', 'description' => 'Office and home desks', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Shelving', 'description' => 'Storage shelving units', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('item_categories')->insert($categories);
    }
}
