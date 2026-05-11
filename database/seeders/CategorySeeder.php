<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Motorbike Tours',
                'slug' => 'motorbike-tours',
                'icon' => 'heroicon-o-map',
                'description' => 'Royal Enfield and off-road motorcycle adventures across Nepal\'s highways and mountain passes.',
            ],
            [
                'name' => 'Trekking',
                'slug' => 'trekking',
                'icon' => 'heroicon-o-arrow-trending-up',
                'description' => 'Multi-day Himalayan treks to base camps, circuits, and remote valleys.',
            ],
            [
                'name' => 'Cycling',
                'slug' => 'cycling',
                'icon' => 'heroicon-o-bolt',
                'description' => 'Road and mountain biking routes through Nepal\'s terrain.',
            ],
            [
                'name' => 'Cultural Tours',
                'slug' => 'cultural-tours',
                'icon' => 'heroicon-o-building-library',
                'description' => 'Heritage, temple, and village experiences exploring Nepal\'s history and traditions.',
            ],
            [
                'name' => 'Hikes',
                'slug' => 'hikes',
                'icon' => 'heroicon-o-sun',
                'description' => 'Shorter day and multi-day hikes accessible to all fitness levels.',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
