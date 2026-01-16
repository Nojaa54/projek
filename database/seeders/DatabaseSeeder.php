<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Penjual (Seller)
        User::create([
            'name' => 'Seller One',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'role' => 'penjual',
        ]);

        // Pembeli (Buyer)
        User::create([
            'name' => 'Buyer One',
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
        ]);
    }
}
