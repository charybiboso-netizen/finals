<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cleanswift.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '09123456789',
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@cleanswift.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => 'active',
            'phone' => '09123456788',
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@cleanswift.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '09123456787',
            'address' => '123 Customer St, City',
        ]);

        $services = [
            ['name' => 'Wash Only', 'slug' => 'wash-only', 'description' => 'Professional washing service for your clothes.', 'price' => 30.00, 'unit' => 'per_kg', 'type' => 'wash', 'estimated_hours' => 24],
            ['name' => 'Dry Only', 'slug' => 'dry-only', 'description' => 'Machine drying service for your laundry.', 'price' => 25.00, 'unit' => 'per_kg', 'type' => 'dry', 'estimated_hours' => 12],
            ['name' => 'Fold Only', 'slug' => 'fold-only', 'description' => 'Neat folding and ironing service.', 'price' => 20.00, 'unit' => 'per_kg', 'type' => 'fold', 'estimated_hours' => 12],
            ['name' => 'Iron Only', 'slug' => 'iron-only', 'description' => 'Professional ironing service for wrinkle-free clothes.', 'price' => 35.00, 'unit' => 'per_piece', 'type' => 'iron', 'estimated_hours' => 24],
            ['name' => 'Wash & Dry', 'slug' => 'wash-dry', 'description' => 'Complete wash and dry service.', 'price' => 50.00, 'unit' => 'per_kg', 'type' => 'wash_dry_fold', 'estimated_hours' => 24],
            ['name' => 'Wash & Iron', 'slug' => 'wash-iron', 'description' => 'Wash and iron your clothes to perfection.', 'price' => 60.00, 'unit' => 'per_kg', 'type' => 'wash_iron', 'estimated_hours' => 36],
            ['name' => 'Full Service', 'slug' => 'full-service', 'description' => 'Complete laundry service - wash, dry, fold, and iron.', 'price' => 80.00, 'unit' => 'per_kg', 'type' => 'full_service', 'estimated_hours' => 48],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
