<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'name' => 'James Whitmore',
                'location' => 'Brisbane, Australia',
                'trip_slug' => 'everest-base-camp-trek',
                'quote' => 'Everest Base Camp with TravelNepal exceeded every expectation. Our guide knew every lodge owner by name, paced acclimatisation perfectly, and the Khumbu views at sunrise from Kala Patthar still give me goosebumps.',
                'rating' => 5,
            ],
            [
                'name' => 'Priya Natarajan',
                'location' => 'Chennai, India',
                'trip_slug' => 'annapurna-circuit-trek',
                'quote' => 'The Annapurna Circuit was organised flawlessly—from permits to Thorong La timing. Felt safe the entire way and the teahouse recommendations were spot on.',
                'rating' => 5,
            ],
            [
                'name' => 'Marco De Luca',
                'location' => 'Milan, Italy',
                'trip_slug' => 'kathmandu-to-mustang-motorbike-expedition',
                'quote' => 'Mustang on a Royal Enfield was the trip of a lifetime. Support crew, mechanics, and road captains were professional; the high desert landscapes are unreal.',
                'rating' => 5,
            ],
            [
                'name' => 'Hannah Collins',
                'location' => 'Vancouver, Canada',
                'trip_slug' => 'pokhara-to-chitwan-cycling-adventure',
                'quote' => 'Cycling Pokhara to Chitwan was the perfect mix of effort and reward—rolling hills, friendly villages, and ending with a safari. Would book again in a heartbeat.',
                'rating' => 4,
            ],
            [
                'name' => 'Tom Fletcher',
                'location' => 'Manchester, UK',
                'trip_slug' => 'poon-hill-hike',
                'quote' => 'Short on time, we did Poon Hill with TravelNepal. Four days, big Annapurna views, zero stress on logistics. Exactly what we needed for a first taste of Nepal.',
                'rating' => 5,
            ],
        ];

        foreach ($rows as $row) {
            $tripId = Trip::query()->where('slug', $row['trip_slug'])->value('id');

            Testimonial::query()->updateOrCreate(
                [
                    'name' => $row['name'],
                    'location' => $row['location'],
                ],
                [
                    'trip_id' => $tripId,
                    'quote' => $row['quote'],
                    'rating' => $row['rating'],
                    'is_approved' => true,
                    'avatar' => null,
                ]
            );
        }
    }
}
