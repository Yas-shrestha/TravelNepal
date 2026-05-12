<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id', 'slug')->all();

        $trips = [
            [
                'title' => 'Kathmandu to Mustang Motorbike Expedition',
                'category_slug' => 'motorbike-tours',
                // Motorbike on a high mountain dirt road in Nepal/Himalaya
                'cover_image' => 'https://images.unsplash.com/photo-1558981285-6f0c94958bb6?w=800',
                'difficulty' => 'challenging',
                'duration_days' => 12,
                'max_altitude_m' => null,
                'route_distance_km' => 720.00,
                'best_season' => 'March-May, September-November',
                'overview' => 'Ride the legendary road from Kathmandu into the rain-shadow kingdom of Mustang, crossing high passes and ancient Tibetan-influenced villages. Our Royal Enfield-led expedition balances big-mile days with rest stops and full mechanical backup along the route.',
                'highlights' => [
                    'Cross the Thorong La approach roads and Kagbeni gateway to Upper Mustang',
                    'Ride beside the Kali Gandaki, one of the world\'s deepest river gorges',
                    'Tea-house stays and acclimatisation built into the itinerary',
                    'Experienced road captain and support vehicle on select sectors',
                    'Permits and TIMS arranged for a hassle-free border-to-border run',
                ],
            ],
            [
                'title' => 'Everest Highway Royal Enfield Ride',
                'category_slug' => 'motorbike-tours',
                // Royal Enfield motorcycle on a winding Himalayan highway
                'cover_image' => 'https://images.unsplash.com/photo-1609137144813-7d9921338f24?w=800',
                'difficulty' => 'moderate',
                'duration_days' => 10,
                'max_altitude_m' => null,
                'route_distance_km' => 480.00,
                'best_season' => 'March-May, October-November',
                'overview' => 'Follow the winding Arun and Sun Kosi valleys toward the Everest region on paved and mixed surfaces ideal for Himalayan 411s and Classics. This tour focuses on dramatic river gorges, ridge viewpoints, and warm Sherpa hospitality without committing to a full trek.',
                'highlights' => [
                    'Scenic riding days with manageable daily distances',
                    'Optional viewpoints toward Everest massif on clear mornings',
                    'Mix of guesthouses and small hotels with hearty local meals',
                    'Daily briefing on road conditions and safety',
                    'Fuel and major permits coordinated by our office team',
                ],
            ],
            [
                'title' => 'Nepal Himalaya Circuit by Motorbike',
                'category_slug' => 'motorbike-tours',
                // Motorbike convoy on a remote Himalayan mountain road
                'cover_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'difficulty' => 'extreme',
                'duration_days' => 18,
                'max_altitude_m' => null,
                'route_distance_km' => 1280.00,
                'best_season' => 'April-May, October',
                'overview' => 'Our flagship long-loop motorbike odyssey stitches together Nepal\'s middle hills, river valleys, and high passes for riders who want maximum variety. Expect long saddle days, weather windows at altitude, and a tight crew focused on safety and bike readiness.',
                'highlights' => [
                    '18-day grand circuit with rest and bike-service days built in',
                    'Dedicated mechanic support and spare-parts inventory on the truck',
                    'High-pass sections tackled only after acclimatisation',
                    'Cultural stops in hill towns rarely visited on shorter tours',
                    'Small-group format to keep the convoy nimble on mountain roads',
                ],
            ],
            [
                'title' => 'Everest Base Camp Trek',
                'category_slug' => 'trekking',
                // Classic Everest Base Camp trail with Khumbu glacier and Everest in background
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                'difficulty' => 'challenging',
                'duration_days' => 14,
                'max_altitude_m' => 5545,
                'route_distance_km' => null,
                'best_season' => 'March-May, September-November',
                'overview' => 'Walk in the footsteps of expedition teams from Lukla to Everest Base Camp, with proper acclimatisation in Namche and Dingboche. Your government-licensed guide handles logistics while you focus on the Khumbu\'s glaciers, monasteries, and iconic peaks.',
                'highlights' => [
                    'Stand at Everest Base Camp with views of the Khumbu Icefall',
                    'Climb Kala Patthar for sunrise over Everest, Lhotse, and Nuptse',
                    'Sagarmatha National Park permits and TIMS included',
                    'Tea-house trekking with advice on altitude hygiene',
                    'Porter option available on Premium and Luxury tiers',
                ],
            ],
            [
                'title' => 'Annapurna Circuit Trek',
                'category_slug' => 'trekking',
                // Annapurna range with trekker on trail, classic circuit view
                'cover_image' => 'https://images.unsplash.com/photo-1494548162494-384bba4ab999?w=800',
                'difficulty' => 'challenging',
                'duration_days' => 18,
                'max_altitude_m' => 5416,
                'route_distance_km' => null,
                'best_season' => 'March-May, October-November',
                'overview' => 'Circle the Annapurna massif from subtropical forests to the arid Tibetan plateau side of Mustang, crossing Thorong La. This classic teahouse trek rewards patience with ever-changing landscapes and deep Gurung and Manangi culture.',
                'highlights' => [
                    'Thorong La crossing with acclimatisation days in Manang',
                    'Descent through Muktinath and the Kali Gandaki gorge',
                    'Hot springs at Tatopani and lakeside Pokhara finish',
                    'All trekking permits and conservation fees arranged',
                    'Flexible rest days if weather delays the pass crossing',
                ],
            ],
            [
                'title' => 'Langtang Valley Trek',
                'category_slug' => 'trekking',
                // Langtang valley alpine meadow with snow-capped peaks
                'cover_image' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800',
                'difficulty' => 'moderate',
                'duration_days' => 10,
                'max_altitude_m' => 4984,
                'route_distance_km' => null,
                'best_season' => 'March-May, September-November',
                'overview' => 'Closer to Kathmandu than Everest or Annapurna, Langtang offers alpine meadows, Tamang villages, and big mountain views without long domestic flights. The valley rebuild after 2015 has restored trails and lodges with renewed community spirit.',
                'highlights' => [
                    'Langtang National Park entry and TIMS handled for you',
                    'Kyanjin Gompa day hikes toward glaciers and viewpoints',
                    'Tamang heritage, cheese factories, and monastery visits',
                    'Moderate altitude profile ideal for first-time Himalayan trekkers',
                    'Road access from Kathmandu keeps logistics straightforward',
                ],
            ],
            [
                'title' => 'Manaslu Circuit Trek',
                'category_slug' => 'trekking',
                // Remote Himalayan trekking trail with dramatic mountain scenery
                'cover_image' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800',
                'difficulty' => 'extreme',
                'duration_days' => 16,
                'max_altitude_m' => 5160,
                'route_distance_km' => null,
                'best_season' => 'March-May, October-November',
                'overview' => 'Trek a restricted trail around the world\'s eighth-highest peak with fewer crowds than Annapurna or Everest. Manaslu demands fitness and commitment: long days, remote lodges, and the dramatic Larkya La crossing make this a serious Himalayan objective.',
                'highlights' => [
                    'Restricted-area permits and group-size rules managed by our team',
                    'Larkya La glacier and peak panorama on a clear crossing day',
                    'Budhi Gandaki gorge and Tibetan-border cultural corridor',
                    'Experienced guides trained in remote evacuation protocols',
                    'Optional side trips to Pungen Gompa and Manaslu base camp viewpoints',
                ],
            ],
            [
                'title' => 'Kathmandu Valley Cycling Tour',
                'category_slug' => 'cycling',
                // Cyclist on a quiet backroad with Himalayan foothills and terraced fields
                'cover_image' => 'https://images.unsplash.com/photo-1544191696-102dbdaeeaa0?w=800',
                'difficulty' => 'easy',
                'duration_days' => 5,
                'max_altitude_m' => null,
                'route_distance_km' => 165.00,
                'best_season' => 'October-March (cooler, clearer skies)',
                'overview' => 'Spin through UNESCO towns, ridge-top villages, and terraced fields on quiet backroads around the Kathmandu rim. This short tour suits riders who want culture, temples, and gentle climbs without committing to a high-altitude expedition.',
                'highlights' => [
                    'Bhaktapur, Patan, and hidden valley routes away from main traffic',
                    'Support vehicle on call for longer climbs or bad weather',
                    'Quality hardtail or hybrid rental options in Kathmandu',
                    'Family-friendly pacing with plenty of photo stops',
                    'Evenings in comfortable 3-star hotels in the valley',
                ],
            ],
            [
                'title' => 'Pokhara to Chitwan Cycling Adventure',
                'category_slug' => 'cycling',
                // Cyclist descending a scenic hill road toward lush subtropical valley
                'cover_image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800',
                'difficulty' => 'moderate',
                'duration_days' => 7,
                'max_altitude_m' => null,
                'route_distance_km' => 235.00,
                'best_season' => 'October-April',
                'overview' => 'Descend from lakeside Pokhara through middle-hill towns toward the Terai and Chitwan National Park. Expect rolling days, river crossings, and a safari finish—Nepal\'s geography in one unforgettable week on two wheels.',
                'highlights' => [
                    'Annapurna skyline views on clear mornings above Pokhara',
                    'Traffic-aware routing with guide and sweep rider',
                    'Chitwan jungle activities: jeep safari or canoe (tier-dependent)',
                    'Mechanic-supported convoy for punctures and tune-ups',
                    'Mix of guesthouses and resort-style lodging near the park',
                ],
            ],
            [
                'title' => 'Upper Mustang Cultural Tour',
                'category_slug' => 'cultural-tours',
                // Ochre cliffs and ancient cave dwellings of Upper Mustang / Lo Manthang
                'cover_image' => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=800',
                'difficulty' => 'moderate',
                'duration_days' => 12,
                'max_altitude_m' => null,
                'route_distance_km' => null,
                'best_season' => 'April-November (Upper Mustang dry season)',
                'overview' => 'Fly to Jomsom and enter the once-forbidden kingdom of Lo, where ochre cliffs, gompas, and chortens define the landscape. Travel by 4WD and short walks between villages with a cultural guide who explains Bon and Tibetan Buddhist traditions in depth.',
                'highlights' => [
                    'Lo Manthang walled town and royal heritage context',
                    'Special restricted-area permits arranged in advance',
                    'Private vehicle with experienced mountain driver',
                    'Monastery visits timed to avoid prayer clashes',
                    'Comfortable lodges where available in the high desert',
                ],
            ],
            [
                'title' => 'Kathmandu Heritage Valley Tour',
                'category_slug' => 'cultural-tours',
                // Boudhanath stupa or Pashupatinath — iconic Kathmandu spiritual heritage
                'cover_image' => 'https://images.unsplash.com/photo-1582654291086-b015cbae0db0?w=800',
                'difficulty' => 'easy',
                'duration_days' => 4,
                'max_altitude_m' => null,
                'route_distance_km' => null,
                'best_season' => 'Year-round (monsoon afternoons may shift timing)',
                'overview' => 'Unpack once in Kathmandu and explore the living museum of the valley: Durbar squares, stupas beneath swaying prayer flags, and artisan quarters. Perfect for first-time visitors who want depth without altitude or long road days.',
                'highlights' => [
                    'Swayambhunath and Boudhanath with storytelling guides',
                    'Patan and Bhaktapur heritage walks with monument tickets included',
                    'Optional cooking class or evening cultural show add-ons',
                    'Airport transfers and private transport between cities',
                    'Hand-picked 3-4 star hotels in central locations',
                ],
            ],
            [
                'title' => 'Nagarkot Sunrise Hike',
                'category_slug' => 'hikes',
                // Golden sunrise over the Himalayan range viewed from a ridge — Nagarkot style
                'cover_image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=800',
                'difficulty' => 'easy',
                'duration_days' => 1,
                'max_altitude_m' => 2175,
                'route_distance_km' => null,
                'best_season' => 'October-April for clearest Himalayan views',
                'overview' => 'Leave pre-dawn from Kathmandu for a short ridge walk to Nagarkot\'s viewpoints, where—on a clear day—Langtang to Everest appears as a wall of snow. Return by midday with breakfast overlooking the hills.',
                'highlights' => [
                    'Private vehicle and guide for a tight one-day schedule',
                    'Gentle trails suitable for most fitness levels',
                    'Hotel breakfast option on the ridge (tier-dependent)',
                    'Ideal add-on before or after a longer trek or tour',
                    'Hotel pick-up and drop-off in the Kathmandu ring road area',
                ],
            ],
            [
                'title' => 'Poon Hill Hike',
                'category_slug' => 'hikes',
                // Iconic Poon Hill sunrise panorama — Dhaulagiri and Annapurna in pink light
                'cover_image' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
                'difficulty' => 'easy',
                'duration_days' => 4,
                'max_altitude_m' => 3210,
                'route_distance_km' => null,
                'best_season' => 'March-May, September-November',
                'overview' => 'The Ghorepani-Poon Hill mini-circuit is Nepal\'s favourite short trek: rhododendron forests, Gurung villages, and a sunrise panorama over Dhaulagiri and Annapurna. Four days keeps the walking moderate while still delivering big views.',
                'highlights' => [
                    'Sunrise from Poon Hill with iconic Annapurna and Dhaulagiri vistas',
                    'Comfortable tea houses in Ghandruk and Ghorepani',
                    'ACAP permit and TIMS included',
                    'Pokhara start with private transport to trailhead',
                    'Pacing suited to families and first-time hill walkers',
                ],
            ],
        ];

        foreach ($trips as $index => $row) {
            $slug = Str::slug($row['title']);
            $isMotorbike = $row['category_slug'] === 'motorbike-tours';

            Trip::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categoryIds[$row['category_slug']],
                    'title' => $row['title'],
                    'difficulty' => $row['difficulty'],
                    'duration_days' => $row['duration_days'],
                    'max_altitude_m' => $row['max_altitude_m'],
                    'route_distance_km' => $row['route_distance_km'],
                    'min_group_size' => 2,
                    'max_group_size' => 12,
                    'best_season' => $row['best_season'],
                    'overview' => $row['overview'],
                    'highlights' => $row['highlights'],
                    'cover_image' => $row['cover_image'],
                    'is_featured' => $index < 6,
                    'is_active' => true,
                    'requires_bike' => $isMotorbike,
                    'bike_rental_available' => $isMotorbike,
                ]
            );
        }
    }
}
