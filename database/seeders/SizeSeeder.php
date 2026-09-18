<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            [
                'name' => 'XS',
                'slug' => 'xs',
                'display_order' => 1,
            ],
            [
                'name' => 'S',
                'slug' => 's',
                'display_order' => 2,
            ],
            [
                'name' => 'M',
                'slug' => 'm',
                'display_order' => 3,
            ],
            [
                'name' => 'L',
                'slug' => 'l',
                'display_order' => 4,
            ],
            [
                'name' => 'XL',
                'slug' => 'xl',
                'display_order' => 5,
            ],
            [
                'name' => 'XXL',
                'slug' => 'xxl',
                'display_order' => 6,
            ],
        ];

        foreach ($sizes as $size) {
            Size::updateOrCreate(
                ['slug' => $size['slug']],
                [
                    ...$size,
                    'is_active' => true,
                ]
            );
        }
    }
}
