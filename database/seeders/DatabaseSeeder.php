<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@balimotor.test',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
            'current_address' => 'Denpasar, Bali',
            'passport_number' => 'ADMIN001',
            'origin_country' => 'Indonesia',
            'has_license' => true,
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@balimotor.test',
            'password' => Hash::make('password'),
            'phone_number' => '081298765432',
            'current_address' => 'Kuta, Bali',
            'passport_number' => 'P1234567',
            'origin_country' => 'Australia',
            'has_license' => true,
            'role' => 'customer',
            'status' => 'active',
        ]);

        $motorcycles = [
            [
                'brand' => 'Honda',
                'model' => 'Vario 125',
                'type' => 'Matic',
                'year' => 2022,
                'plate_number' => 'DK 1234 AB',
                'price_per_day' => 80000,
                'description' => 'Motor matic nyaman untuk perjalanan harian di Bali.',
                'status' => 'available',
            ],
            [
                'brand' => 'Yamaha',
                'model' => 'NMAX',
                'type' => 'Matic Premium',
                'year' => 2023,
                'plate_number' => 'DK 2345 CD',
                'price_per_day' => 150000,
                'description' => 'Motor premium dengan bagasi luas dan posisi berkendara nyaman.',
                'status' => 'available',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Beat',
                'type' => 'Matic',
                'year' => 2021,
                'plate_number' => 'DK 3456 EF',
                'price_per_day' => 70000,
                'description' => 'Motor ringan dan hemat bahan bakar.',
                'status' => 'available',
            ],
        ];

        foreach ($motorcycles as $motorcycle) {
            Motorcycle::create($motorcycle);
        }
    }
}