<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        Apartment::create([
            'landlord_id' => 2, 
            'title' => 'شقة فاخرة في دمشق',
            'description' => 'شقة واسعة مع إطلالة جميلة، مجهزة بالكامل.',
            'governorate' => 'Damascus',
            'city' => 'Mazzeh',
            'price_per_night' => 50,
            'features' => ['WiFi', 'Air Conditioning', 'Balcony'],
            'status' => 'available',
        ]);

        Apartment::create([
            'landlord_id' => 2,
            'title' => 'شقة صغيرة في السويداء',
            'description' => 'شقة اقتصادية مناسبة للطلاب.',
            'governorate' => 'As-Suweida',
            'city' => 'City Center',
            'price_per_night' => 20,
            'features' => ['WiFi'],
            'status' => 'available',
        ]);
    }
}
