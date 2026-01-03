<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LandlordSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'phone' => '0988888888',
            'password' => Hash::make('landlord123'),
            'role' => 'landlord',
            'approval_status' => 'approved',
            'first_name' => 'Ahmad',
            'last_name' => 'Landlord',
            'birth_date' => '1985-05-10',
            'personal_image' => 'default.png',
            'identity_image' => 'default.png',
        ]);
    }
}
