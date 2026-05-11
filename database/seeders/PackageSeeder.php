<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use App\Models\PackageInclusion;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $categorySlugs = Category::query()->pluck('slug', 'id')->all();

        foreach (Trip::query()->orderBy('id')->cursor() as $trip) {
            $categorySlug = $categorySlugs[$trip->category_id] ?? 'trekking';
            $duration = (int) $trip->duration_days;
            $difficulty = (string) $trip->difficulty;

            $dailyStandard = $this->resolveStandardDailyUsd($categorySlug, $difficulty);
            $standardTotal = round($dailyStandard * $duration, 2);
            $premiumTotal = round($standardTotal * 1.38, 2);
            $luxuryTotal = round($standardTotal * 1.72, 2);

            $packages = [
                [
                    'tier' => 'standard',
                    'title' => $trip->title.' — Standard',
                    'price_usd' => $standardTotal,
                    'is_popular' => false,
                    'description' => 'Core inclusions for an authentic Nepal experience: licensed guide, key permits, and comfortable tea-house or hotel stays matched to this itinerary.',
                ],
                [
                    'tier' => 'premium',
                    'title' => $trip->title.' — Premium',
                    'price_usd' => $premiumTotal,
                    'is_popular' => true,
                    'description' => 'Elevated comfort and support—better lodging where available, more meals included, and extras like private transfers on select legs.',
                ],
                [
                    'tier' => 'luxury',
                    'title' => $trip->title.' — Luxury',
                    'price_usd' => $luxuryTotal,
                    'is_popular' => false,
                    'description' => 'Our top tier: best available rooms, maximum meal coverage, priority support, and curated cultural touches for a seamless journey.',
                ],
            ];

            foreach ($packages as $pkg) {
                $package = Package::query()->updateOrCreate(
                    [
                        'trip_id' => $trip->id,
                        'tier' => $pkg['tier'],
                    ],
                    [
                        'title' => $pkg['title'],
                        'price_usd' => $pkg['price_usd'],
                        'is_popular' => $pkg['is_popular'],
                        'is_active' => true,
                        'description' => $pkg['description'],
                    ]
                );

                $package->inclusions()->delete();

                $inclusions = $this->inclusionLines($categorySlug, $pkg['tier']);
                $exclusions = $this->exclusionLines($categorySlug);

                $sort = 0;
                foreach ($inclusions as $line) {
                    PackageInclusion::query()->create([
                        'package_id' => $package->id,
                        'description' => $line,
                        'type' => 'inclusion',
                        'sort_order' => $sort++,
                    ]);
                }
                foreach ($exclusions as $line) {
                    PackageInclusion::query()->create([
                        'package_id' => $package->id,
                        'description' => $line,
                        'type' => 'exclusion',
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function inclusionLines(string $categorySlug, string $tier): array
    {
        $tierNote = match ($tier) {
            'premium' => 'Upgrade: ',
            'luxury' => 'Luxury: ',
            default => '',
        };

        return match ($categorySlug) {
            'motorbike-tours' => [
                $tierNote.'Airport arrival and departure transfers in Kathmandu',
                $tierNote.'Government-licensed tour leader and daily route briefing',
                $tierNote.'Support vehicle on select sectors with spare fuel and tools',
                $tierNote.'Accommodation in guesthouses or hotels as per itinerary',
                $tierNote.'TIMS and required road permits arranged by TravelNepal',
            ],
            'trekking' => [
                $tierNote.'Licensed trekking guide and group safety briefing',
                $tierNote.'Tea-house or lodge accommodation along the route',
                $tierNote.'Sagarmatha / ACAP / Manaslu permits and TIMS as required',
                $tierNote.'Breakfast and dinner daily on trekking days (as listed)',
                $tierNote.'First-aid kit and emergency coordination from Kathmandu office',
            ],
            'cycling' => [
                $tierNote.'Cycling guide and sweep rider on road sections',
                $tierNote.'Support vehicle for luggage, snacks, and mechanical backup',
                $tierNote.'Quality bike rental option or BYO bike storage assistance',
                $tierNote.'Hotel or guesthouse nights with hearty rider breakfasts',
                $tierNote.'Route maps, GPX tracks, and daily distance/elevation plan',
            ],
            'cultural-tours' => [
                $tierNote.'Private vehicle with experienced driver throughout',
                $tierNote.'Licensed cultural guide for monuments and heritage walks',
                $tierNote.'Monument and heritage-site entry fees as per itinerary',
                $tierNote.'Hand-picked hotels with daily breakfast',
                $tierNote.'Airport meet-and-greet and bottled water in vehicle',
            ],
            'hikes' => [
                $tierNote.'English-speaking hiking guide for the full programme',
                $tierNote.'Tea-house or hotel nights as suited to the trail',
                $tierNote.'ACAP or relevant permits and TIMS where applicable',
                $tierNote.'Private ground transfers from Pokhara or Kathmandu',
                $tierNote.'Packed snacks or trail lunch on longer walking days',
            ],
            default => [
                $tierNote.'Licensed guide for the full programme',
                $tierNote.'Comfortable accommodation as described in your quote',
                $tierNote.'Key permits and paperwork handled by our office',
                $tierNote.'Daily breakfast (and additional meals where stated)',
                $tierNote.'24-hour Kathmandu office support line',
            ],
        };
    }

    /**
     * @return array<int, string>
     */
    private function exclusionLines(string $categorySlug): array
    {
        return match ($categorySlug) {
            'motorbike-tours' => [
                'International flights to and from Kathmandu',
                'Personal travel, medical, and motorcycle riding insurance',
                'Royal Enfield daily rental, spare parts beyond normal wear, and tips for crew',
            ],
            'trekking' => [
                'International and domestic flights unless quoted separately',
                'Travel insurance including helicopter evacuation cover',
                'Lunches on trekking days, bottled drinks, and hot showers where lodges charge extra',
            ],
            'cycling' => [
                'International flights and bike excess baggage fees',
                'Cycling-specific travel insurance and bike damage cover',
                'Lunch stops in towns, energy gels, and bike upgrades beyond agreed rental grade',
            ],
            'cultural-tours' => [
                'International flights and Nepal visa fees',
                'Personal travel insurance',
                'Lunches and dinners unless explicitly listed, plus personal shopping',
            ],
            'hikes' => [
                'International flights and travel insurance',
                'Lunches and personal snacks on the trail',
                'Tips for guides and drivers at your discretion',
            ],
            default => [
                'International flights',
                'Personal travel insurance',
                'Meals and services not listed in the inclusions',
            ],
        };
    }

    private function resolveStandardDailyUsd(string $categorySlug, string $difficulty): float
    {
        $difficultyMultiplier = match ($difficulty) {
            'extreme' => 1.15,
            'challenging' => 1.08,
            'moderate' => 1.0,
            default => 0.92,
        };

        $base = match ($categorySlug) {
            'motorbike-tours' => 92.0,
            'trekking' => 74.0,
            'cycling' => 118.0,
            'cultural-tours' => 68.0,
            'hikes' => 52.0,
            default => 70.0,
        };

        return round($base * $difficultyMultiplier, 2);
    }
}
