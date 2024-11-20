<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat user default untuk login
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => '1',
            'password' => Hash::make('password'), // Gantilah dengan password yang aman
        ]);

        User::create([
            'name' => 'User Test',
            'email' => 'noc@gmail.com',
            'role' => '2',
            'password' => Hash::make('userpassword'), // Gantilah dengan password yang aman
        ]);
    }
}