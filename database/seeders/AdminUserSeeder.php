<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'phone' => '0999999999',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'approval_status' => 'approved',
            'first_name' => 'System',
            'last_name' => 'Admin',
            'birth_date' => '1990-01-01',
            'personal_image' => 'default.png',
            'identity_image' => 'default.png',
        ]);
    }
}