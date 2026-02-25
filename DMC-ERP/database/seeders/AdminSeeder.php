<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
    $superadmin = Role::firstOrCreate(['name' => 'Superadmin']);
    $staff = Role::firstOrCreate(['name' => 'Staff']);

    // Create superadmin user
    User::create([
        'employee_id' => '202600001',
        'name' => 'Superadmin',
        'email' => 'admin@dmc.com',
        'password' => Hash::make('password123'),
        'role_id' => $superadmin->id
    ]);
    }
}
