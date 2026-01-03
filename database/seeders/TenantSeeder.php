<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'phone' => '0977777777',
            'password' => Hash::make('tenant123'),
            'role' => 'tenant',
            'approval_status' => 'approved',
            'first_name' => 'Yara',
            'last_name' => 'Tenant',
            'birth_date' => '1998-03-15',
            'personal_image' => 'default.png',
            'identity_image' => 'default.png',
        ]);
    }
}
