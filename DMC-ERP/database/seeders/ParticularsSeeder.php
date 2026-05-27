<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticularsSeeder extends Seeder
{
    public function run(): void
    {
        $particulars = [
            'Accommodation',
            'Bank Charges',
            'Bid Docs Fee and other Documents',
            'Building Expense',
            'Cash Advance',
            'Commission',
            'Donation',
            'Food',
            'Freight',
            'Fuel / Gas',
            'Kairos',
            'Labor',
            'MAC',
            'Advances',
            'Office Expense',
            'Parking',
            'Qarrah',
            'Representation',
            'Salary',
            'Taxes and Licences',
            'Tollgate',
            'Transportation',
            'Utilities',
            'Vehicle Maintenance',
            'Visa',
        ];

        $rows = array_map(fn($name) => [
            'particulars_category' => $name,
            'created_at'           => now(),
            'updated_at'           => now(),
        ], $particulars);

        DB::table('categories')->insert($rows);
    }
}
