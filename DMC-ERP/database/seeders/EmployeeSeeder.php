<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'employee_id' => '202600004',
                'password' => Hash::make('employee123'),
                'role_id' => 2,
                'name' => 'John Doe',
                'email' => 'john.doe@dmc.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employee_id' => '202600005',
                'password' => Hash::make('employee123'),
                'role_id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@dmc.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employee_id' => '202600006',
                'password' => Hash::make('employee123'),
                'role_id' => 2,
                'name' => 'Robert Johnson',
                'email' => 'robert.johnson@dmc.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employee_id' => '202600007',
                'password' => Hash::make('employee123'),
                'role_id' => 2,
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@dmc.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employee_id' => '202600008',
                'password' => Hash::make('employee123'),
                'role_id' => 2,
                'name' => 'David Lee',
                'email' => 'david.lee@dmc.com',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
