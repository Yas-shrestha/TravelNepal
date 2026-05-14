<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trip_id'        => Trip::factory(),
            'package_id'     => Package::factory(),
            'name'           => $this->faker->name(),
            'email'          => $this->faker->email(),
            'phone'          => $this->faker->phoneNumber(),
            'preferred_date' => $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'group_size'     => $this->faker->numberBetween(1, 8),
            'message'        => $this->faker->optional()->sentence(),
            'status'         => 'pending',
            'has_own_bike'   => null,
            'has_license'    => null,
        ];
    }
}
