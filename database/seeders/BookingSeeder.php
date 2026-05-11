<?php

namespace Database\Seeders;

use App\Models\BikeRental;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBikeRentalsForMotorbikeTrips();

        Booking::query()->delete();

        $trip = static fn (string $slug): Trip => Trip::query()->where('slug', $slug)->firstOrFail();

        $packageId = static fn (int $tripId, string $tier = 'standard'): int => (int) Package::query()
            ->where('trip_id', $tripId)
            ->where('tier', $tier)
            ->value('id');

        $bikeForTrip = static fn (int $tripId): BikeRental => BikeRental::query()
            ->where('trip_id', $tripId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->firstOrFail();

        $rentalCost = static function (Trip $t, BikeRental $bike): float {
            $days = (int) $t->duration_days;
            $perDay = (float) $bike->price_per_day_usd;

            return round($perDay * $days, 2);
        };

        $baseDate = Carbon::now()->addWeeks(2)->startOfDay();

        $rows = [
            [
                'status' => 'pending',
                'trip_slug' => 'kathmandu-to-mustang-motorbike-expedition',
                'package_tier' => 'premium',
                'name' => 'Rajesh Koirala',
                'email' => 'rajesh.koirala@gmail.com',
                'phone' => '+977 984-112-3344',
                'preferred_date' => $baseDate->copy()->addDays(14),
                'group_size' => 2,
                'message' => 'Two riders, both with international licences. Prefer staggered start from Kathmandu if possible.',
                'motorbike' => true,
                'has_own_bike' => true,
                'own_bike_model' => 'Royal Enfield Himalayan 411 (personal)',
                'license_number' => 'NP-MC-2019-88421',
                'license_country' => 'Nepal',
                'license_type' => 'local',
            ],
            [
                'status' => 'pending',
                'trip_slug' => 'everest-base-camp-trek',
                'package_tier' => 'standard',
                'name' => 'Emily Chen',
                'email' => 'emily.chen@outlook.co.nz',
                'phone' => '+64 21 555 9012',
                'preferred_date' => $baseDate->copy()->addDays(45),
                'group_size' => 4,
                'message' => 'First Himalayan trek for our group. Vegetarian meals where possible.',
                'motorbike' => false,
            ],
            [
                'status' => 'pending',
                'trip_slug' => 'everest-highway-royal-enfield-ride',
                'package_tier' => 'standard',
                'name' => 'Stefano Ricci',
                'email' => 'stefano.ricci@libero.it',
                'phone' => '+39 347 889 2210',
                'preferred_date' => $baseDate->copy()->addDays(21),
                'group_size' => 1,
                'message' => 'Solo rider; happy to join another small group departure.',
                'motorbike' => true,
                'has_own_bike' => false,
                'license_number' => 'U1R92KLM',
                'license_country' => 'Italy',
                'license_type' => 'international',
            ],
            [
                'status' => 'pending',
                'trip_slug' => 'langtang-valley-trek',
                'package_tier' => 'luxury',
                'name' => 'Hannah O\'Brien',
                'email' => 'h.obrien@icloud.com',
                'phone' => '+353 87 221 4455',
                'preferred_date' => $baseDate->copy()->addDays(60),
                'group_size' => 3,
                'message' => 'Celebrating a milestone birthday — private guide preferred if available.',
                'motorbike' => false,
            ],
            [
                'status' => 'confirmed',
                'trip_slug' => 'nepal-himalaya-circuit-by-motorbike',
                'package_tier' => 'luxury',
                'name' => 'Michael Torres',
                'email' => 'm.torres@yahoo.com',
                'phone' => '+1 415 555 0198',
                'preferred_date' => $baseDate->copy()->addDays(90),
                'group_size' => 2,
                'message' => 'Experienced riders; please confirm spare tube kit on support truck.',
                'motorbike' => true,
                'has_own_bike' => true,
                'own_bike_model' => 'Royal Enfield Interceptor 650',
                'license_number' => 'D1234567',
                'license_country' => 'United States',
                'license_type' => 'international',
            ],
            [
                'status' => 'confirmed',
                'trip_slug' => 'annapurna-circuit-trek',
                'package_tier' => 'premium',
                'name' => 'Yuki Tanaka',
                'email' => 'yuki.tanaka@docomo.ne.jp',
                'phone' => '+81 90-8844-2211',
                'preferred_date' => $baseDate->copy()->addDays(55),
                'group_size' => 6,
                'message' => 'Group of six from Osaka hiking club. Flexible on exact start ±3 days.',
                'motorbike' => false,
            ],
            [
                'status' => 'confirmed',
                'trip_slug' => 'kathmandu-to-mustang-motorbike-expedition',
                'package_tier' => 'standard',
                'name' => 'Oliver Schmidt',
                'email' => 'oliver.schmidt@posteo.de',
                'phone' => '+49 176 4422 9910',
                'preferred_date' => $baseDate->copy()->addDays(30),
                'group_size' => 2,
                'message' => 'Need rental bikes for both riders; same model if possible.',
                'motorbike' => true,
                'has_own_bike' => false,
                'license_number' => 'B-MT-998877',
                'license_country' => 'Germany',
                'license_type' => 'international',
            ],
            [
                'status' => 'cancelled',
                'trip_slug' => 'manaslu-circuit-trek',
                'package_tier' => 'standard',
                'name' => 'Claire MacLeod',
                'email' => 'claire.macleod@proton.me',
                'phone' => '+44 7700 900321',
                'preferred_date' => $baseDate->copy()->addDays(10),
                'group_size' => 5,
                'message' => 'Cancelled due to work — hope to rebook for next season.',
                'motorbike' => false,
            ],
            [
                'status' => 'cancelled',
                'trip_slug' => 'everest-highway-royal-enfield-ride',
                'package_tier' => 'premium',
                'name' => 'Anita Gurung',
                'email' => 'anita.gurung@hotmail.com',
                'phone' => '+977 981-332-1100',
                'preferred_date' => $baseDate->copy()->addDays(7),
                'group_size' => 1,
                'message' => 'Flight schedule conflict — requesting cancellation per policy.',
                'motorbike' => true,
                'has_own_bike' => true,
                'own_bike_model' => 'Royal Enfield Classic 350',
                'license_number' => 'NP-BRT-445566',
                'license_country' => 'Nepal',
                'license_type' => 'local',
            ],
            [
                'status' => 'cancelled',
                'trip_slug' => 'everest-base-camp-trek',
                'package_tier' => 'luxury',
                'name' => 'David Okonkwo',
                'email' => 'david.okonkwo@gmail.com',
                'phone' => '+234 803 555 4411',
                'preferred_date' => $baseDate->copy()->addDays(120),
                'group_size' => 8,
                'message' => 'Family trip postponed; will contact again for new dates.',
                'motorbike' => false,
            ],
        ];

        foreach ($rows as $row) {
            $t = $trip($row['trip_slug']);
            $pkgId = $packageId($t->id, $row['package_tier']);

            $attributes = [
                'trip_id' => $t->id,
                'package_id' => $pkgId,
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'preferred_date' => $row['preferred_date'],
                'group_size' => $row['group_size'],
                'message' => $row['message'],
                'status' => $row['status'],
            ];

            if (($row['motorbike'] ?? false) === true) {
                $bike = $bikeForTrip($t->id);
                $attributes['has_license'] = true;
                $attributes['license_number'] = $row['license_number'];
                $attributes['license_country'] = $row['license_country'];
                $attributes['license_type'] = $row['license_type'];
                $attributes['license_image'] = 'bookings/licenses/sample.jpg';
                $attributes['has_own_bike'] = $row['has_own_bike'];
                if ($row['has_own_bike']) {
                    $attributes['own_bike_model'] = $row['own_bike_model'];
                    $attributes['rental_bike_id'] = null;
                    $attributes['rental_cost_usd'] = null;
                } else {
                    $attributes['own_bike_model'] = null;
                    $attributes['rental_bike_id'] = $bike->id;
                    $attributes['rental_cost_usd'] = $rentalCost($t, $bike);
                }
            } else {
                $attributes['has_license'] = null;
                $attributes['license_number'] = null;
                $attributes['license_country'] = null;
                $attributes['license_type'] = null;
                $attributes['license_image'] = null;
                $attributes['has_own_bike'] = null;
                $attributes['own_bike_model'] = null;
                $attributes['rental_bike_id'] = null;
                $attributes['rental_cost_usd'] = null;
            }

            Booking::query()->create($attributes);
        }
    }

    private function seedBikeRentalsForMotorbikeTrips(): void
    {
        $motorbikeTrips = Trip::query()->where('requires_bike', true)->orderBy('id')->get();

        foreach ($motorbikeTrips as $trip) {
            BikeRental::query()->firstOrCreate(
                [
                    'trip_id' => $trip->id,
                    'model' => 'Royal Enfield Himalayan 411',
                ],
                [
                    'engine_cc' => 411,
                    'price_per_day_usd' => 42.00,
                    'is_available' => true,
                    'sort_order' => 0,
                ]
            );

            BikeRental::query()->firstOrCreate(
                [
                    'trip_id' => $trip->id,
                    'model' => 'Royal Enfield Meteor 350',
                ],
                [
                    'engine_cc' => 349,
                    'price_per_day_usd' => 33.50,
                    'is_available' => true,
                    'sort_order' => 1,
                ]
            );
        }
    }
}
