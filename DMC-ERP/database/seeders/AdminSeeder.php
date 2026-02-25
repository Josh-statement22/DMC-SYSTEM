<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'employee_id' => '202600001',
                'password' => Hash::make('superadmin123'),
                'role_id' => 1,
                'name' => 'Superadmin',
                'email' => 'superadmin@example.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employee_id' => '202600002',
                'password' => Hash::make('admin123'),
                'role_id' => 2,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}