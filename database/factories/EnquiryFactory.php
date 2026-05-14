<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EnquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name(),
            'email'   => $this->faker->email(),
            'phone'   => $this->faker->optional()->phoneNumber(),
            'subject' => $this->faker->randomElement([
                'General Enquiry',
                'Trip Question',
                'Custom Itinerary',
                'Other'
            ]),
            'message' => $this->faker->paragraph(),
            'status'  => 'unread',
            'trip_id' => null,
        ];
    }
}
