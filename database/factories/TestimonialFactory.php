<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trip_id'     => null,
            'name'        => $this->faker->name(),
            'location'    => $this->faker->city() . ', ' . $this->faker->country(),
            'quote'       => $this->faker->paragraph(),
            'rating'      => $this->faker->numberBetween(4, 5),
            'is_approved' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(['is_approved' => false]);
    }
}
