<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@lab-fisika.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
        ]);

        // Create Regular Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lab-fisika.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
