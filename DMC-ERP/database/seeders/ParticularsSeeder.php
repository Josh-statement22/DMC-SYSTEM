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
            'Adjustment',
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
            'Loan',
            'Advances',
            'Office Expense',
            'Parking',
            'Purchases',
            'Qarrah',
            'Representation',
            'Salary',
            'Seed',
            'Taxes and Licences',
            'Tollgate',
            'Transportation',
            'Utilities',
            'Vehicle Maintenance',
            'Visa',
        ];

        foreach ($particulars as $name) {
            if (DB::table('categories')->where('particulars_category', $name)->exists()) {
                continue;
            }

            DB::table('categories')->insert([
                'particulars_category' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
