<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticularsDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $particularsData = [
            'Accommodation' => [
                'Hotel',
                'Hostel',
                'Resort',
            ],
            'Office Expense' => [
                'Office Supplies',
                'Stationery',
                'Equipment',
                'Furniture',
            ],
            'Fuel / Gas' => [
                'Gasoline',
                'Diesel',
                'Vehicle Fuel',
            ],
            'Transportation' => [
                'Taxi',
                'Bus',
                'Car Rental',
                'Airfare',
            ],
            'Food' => [
                'Meals',
                'Snacks',
                'Beverages',
                'Team Lunch',
            ],
            'Utilities' => [
                'Electricity',
                'Water',
                'Internet',
                'Phone',
            ],
            'Vehicle Maintenance' => [
                'Oil Change',
                'Tire Replacement',
                'Repairs',
                'Cleaning',
            ],
            'Labor' => [
                'Hourly Labor',
                'Contractor',
                'Consultant',
            ],
            'Commission' => [
                'Sales Commission',
                'Agent Fee',
                'Broker Fee',
            ],
            'Cash Advance' => [
                'Employee Advance',
                'Project Advance',
                'Working Capital',
            ],
        ];

        foreach ($particularsData as $categoryName => $particulars) {
            $category = DB::table('categories')
                ->where('particulars_category', $categoryName)
                ->first();

            if ($category) {
                $rows = array_map(fn($particular) => [
                    'category_id' => $category->id,
                    'particular_name' => $particular,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $particulars);

                DB::table('particulars')->insert($rows);
            }
        }
    }
}
