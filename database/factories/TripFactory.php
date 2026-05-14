<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->words(4, true);

        return [
            'category_id'          => Category::factory(),
            'title'                => $title,
            'slug'                 => Str::slug($title),
            'difficulty'           => $this->faker->randomElement(['easy', 'moderate', 'challenging', 'extreme']),
            'duration_days'        => $this->faker->numberBetween(1, 20),
            'max_altitude_m'       => $this->faker->optional()->numberBetween(1000, 8000),
            'route_distance_km'    => $this->faker->optional()->randomFloat(2, 50, 2000),
            'min_group_size'       => 1,
            'max_group_size'       => 12,
            'best_season'          => 'March-May, Sept-Nov',
            'overview'             => $this->faker->paragraph(),
            'highlights'           => ['Highlight 1', 'Highlight 2', 'Highlight 3'],
            'cover_image'          => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800',
            'is_featured'          => false,
            'is_active'            => true,
            'requires_bike'        => false,
            'bike_rental_available' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function motorbike(): static
    {
        return $this->state([
            'requires_bike'         => true,
            'bike_rental_available' => true,
        ]);
    }
}
