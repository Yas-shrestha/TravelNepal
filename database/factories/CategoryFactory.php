<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Motorbike Tours',
            'Trekking',
            'Cycling',
            'Cultural Tours',
            'Hikes'
        ]);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'icon'        => 'heroicon-o-map',
            'description' => $this->faker->sentence(),
        ];
    }
}
