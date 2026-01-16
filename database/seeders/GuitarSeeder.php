<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuitarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = \App\Models\User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Seller Account',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'penjual',
            ]
        );

        $guitars = [
            [
                'name' => 'Fender Stratocaster American Professional II',
                'description' => 'The standard for modern guitar performance.',
                'price' => 1699.99,
                'stock' => 5,
                'image_path' => 'products/statocaster.jpg' 
            ],
            [
                'name' => 'Gibson Les Paul Standard 60s',
                'description' => 'The icon of rock and roll.',
                'price' => 2499.00,
                'stock' => 3,
                'image_path' => 'products/lespaul.jpg'
            ],
            [
                'name' => 'Ibanez RG550 Genesis',
                'description' => 'The ultimate shred machine.',
                'price' => 999.00,
                'stock' => 8,
                'image_path' => 'products/ibanez.jpg'
            ],
            [
                'name' => 'PRS Custom 24',
                'description' => 'A modern classic with versatile tones.',
                'price' => 4200.00,
                'stock' => 2,
                'image_path' => 'products/prs.jpg'
            ],
             [
                'name' => 'Epiphone Casino',
                'description' => 'Classic hollow body tone.',
                'price' => 699.00,
                'stock' => 4,
                'image_path' => 'products/casino.jpg'
            ],
            [
                'name' => 'Taylor 814ce',
                'description' => 'Premium acoustic-electric guitar.',
                'price' => 3499.00,
                'stock' => 2,
                'image_path' => 'products/taylor.jpg'
            ],
            [
                'name' => 'Martin D-28',
                'description' => 'The dreadnought by which all others are judged.',
                'price' => 3199.00,
                'stock' => 3,
                'image_path' => 'products/martin.jpg'
            ],
            [
                'name' => 'Gretsch White Falcon',
                'description' => 'The world\'s most beautiful guitar.',
                'price' => 3599.00,
                'stock' => 1,
                'image_path' => 'products/falcon.jpg'
            ],
            [
                'name' => 'Yamaha Pacifica 112V',
                'description' => 'Best value entry-level guitar.',
                'price' => 299.00,
                'stock' => 10,
                'image_path' => 'products/pacifica.jpg'
            ],
            [
                'name' => 'ESP LTD EC-1000',
                'description' => 'Designed for professional performance.',
                'price' => 1099.00,
                'stock' => 6,
                'image_path' => 'products/ltd.jpg'
            ]
        ];

        foreach ($guitars as $data) {
            $guitar = $seller->products()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                // 'image' => $data['image_path'] // Legacy column
            ]);

            // Create fake images
            for($i=1; $i<=3; $i++) {
                $url = "https://placehold.co/600x400/202024/FFF.jpg?text=" . urlencode($data['name'] . " " . $i);
                $contents = file_get_contents($url);
                $name = 'products/' . \Illuminate\Support\Str::slug($data['name']) . '-' . $i . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($name, $contents);

                $guitar->images()->create([
                    'image_path' => $name
                ]);
            }
        }
    }
}
