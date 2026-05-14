<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trip_id'     => Trip::factory(),
            'tier'        => $this->faker->randomElement(['standard', 'premium', 'luxury']),
            'title'       => $this->faker->words(3, true),
            'price_usd'   => $this->faker->randomFloat(2, 500, 3000),
            'is_popular'  => false,
            'is_active'   => true,
            'description' => $this->faker->sentence(),
        ];
    }

    public function standard(): static
    {
        return $this->state(['tier' => 'standard', 'price_usd' => 800]);
    }

    public function premium(): static
    {
        return $this->state(['tier' => 'premium', 'price_usd' => 1200, 'is_popular' => true]);
    }

    public function luxury(): static
    {
        return $this->state(['tier' => 'luxury', 'price_usd' => 2000]);
    }
}
