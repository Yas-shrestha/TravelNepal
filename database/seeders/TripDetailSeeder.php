<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\ItineraryDay;
use App\Models\TripAttraction;
use App\Models\TripFaq;
use Illuminate\Database\Seeder;

class TripDetailSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedItineraryDays();
        $this->seedAttractions();
        $this->seedFaqs();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ITINERARY DAYS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedItineraryDays(): void
    {
        $itineraries = [
            'kathmandu-to-mustang-motorbike-expedition' => [
                ['day_number' => 1, 'title' => 'Arrive Kathmandu — Bike Inspection & Briefing', 'description' => 'Meet the team at our Thamel office for a full equipment check and route briefing.', 'accommodation' => 'Hotel in Thamel', 'meals_included' => 'Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0,],
                ['day_number' => 2, 'title' => 'Kathmandu to Mugling', 'description' => 'Depart early for a warm-up ride along the Trishuli River.', 'accommodation' => 'Guesthouse, Mugling', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 110, 'elevation_gain_m' => 400],
                ['day_number' => 3, 'title' => 'Mugling to Pokhara', 'description' => 'Continue west with improving Annapurna views.', 'accommodation' => 'Hotel, Lakeside Pokhara', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 95, 'elevation_gain_m' => 200],
                ['day_number' => 4, 'title' => 'Pokhara to Beni', 'description' => 'Head north-east on sealed road to the gateway of the valley.', 'accommodation' => 'Lodge, Beni', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 65, 'elevation_gain_m' => 550],
                ['day_number' => 5, 'title' => 'Beni to Tatopani', 'description' => 'The road becomes unpaved; rewarding hot springs at Tatopani.', 'accommodation' => 'Guesthouse, Tatopani', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 72, 'elevation_gain_m' => 800,],
                ['day_number' => 6, 'title' => 'Tatopani to Jomsom', 'description' => 'Dramatic shift to the dry Tibetan plateau feel.', 'accommodation' => 'Hotel, Jomsom', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 78, 'elevation_gain_m' => 1350],
                ['day_number' => 7, 'title' => 'Jomsom Rest Day', 'description' => 'Acclimatisation and light ride to Kagbeni.', 'accommodation' => 'Hotel, Jomsom', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 30, 'elevation_gain_m' => 150],
                ['day_number' => 8, 'title' => 'Jomsom to Lo Manthang', 'description' => 'Enter the Forbidden Kingdom on restricted permits.', 'accommodation' => 'Hotel, Lo Manthang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 82, 'elevation_gain_m' => 1100],
                ['day_number' => 9, 'title' => 'Lo Manthang Exploration', 'description' => 'Visit ancient Gompas and the royal palace.', 'accommodation' => 'Hotel, Lo Manthang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 200],
                ['day_number' => 10, 'title' => 'Lo Manthang to Chele', 'description' => 'Head south via Ghiling and Charang.', 'accommodation' => 'Guesthouse, Chele', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 68, 'elevation_gain_m' => 300],
                ['day_number' => 11, 'title' => 'Chele to Pokhara', 'description' => 'Long ride back to the comfort of Lakeside.', 'accommodation' => 'Hotel, Lakeside Pokhara', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 170, 'elevation_gain_m' => 400],
                ['day_number' => 12, 'title' => 'Pokhara to Kathmandu', 'description' => 'Final ride and farewell dinner.', 'accommodation' => 'Hotel, Kathmandu', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 205, 'elevation_gain_m' => 650],
            ],

            'everest-base-camp-trek' => [
                ['day_number' => 1, 'title' => 'Fly Kathmandu–Lukla, Trek to Phakding', 'description' => 'Scenic 35-minute flight and gentle trek along the Dudh Koshi.', 'accommodation' => 'Tea House, Phakding', 'meals_included' => 'Lunch, Dinner', 'distance_km' => 8, 'elevation_gain_m' => 60],
                ['day_number' => 2, 'title' => 'Phakding to Namche Bazaar', 'description' => 'Climb to the Sherpa capital at 3,440 m.', 'accommodation' => 'Tea House, Namche Bazaar', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 11, 'elevation_gain_m' => 800],
                ['day_number' => 3, 'title' => 'Namche Acclimatisation Day', 'description' => 'Rest day with a hike to Everest View Hotel.', 'accommodation' => 'Tea House, Namche Bazaar', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 6, 'elevation_gain_m' => 440],
                ['day_number' => 4, 'title' => 'Namche to Tengboche', 'description' => 'Visit the famous Tengboche Monastery.', 'accommodation' => 'Tea House, Tengboche', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 13, 'elevation_gain_m' => 700],
                ['day_number' => 5, 'title' => 'Tengboche to Dingboche', 'description' => 'Enter the high alpine zone.', 'accommodation' => 'Tea House, Dingboche', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 11, 'elevation_gain_m' => 700],
                ['day_number' => 6, 'title' => 'Dingboche Acclimatisation', 'description' => 'Hike to Nangkartshang viewpoint (5,083 m).', 'accommodation' => 'Tea House, Dingboche', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 8, 'elevation_gain_m' => 680],
                ['day_number' => 7, 'title' => 'Dingboche to Lobuche', 'description' => 'Pass the Khumbu memorial site.', 'accommodation' => 'Tea House, Lobuche', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 10, 'elevation_gain_m' => 530],
                ['day_number' => 8, 'title' => 'Lobuche to Gorak Shep & EBC', 'description' => 'The highlight: reaching Everest Base Camp (5,364 m).', 'accommodation' => 'Tea House, Gorak Shep', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 14, 'elevation_gain_m' => 500],
                ['day_number' => 9, 'title' => 'Kala Patthar & Pheriche', 'description' => 'Sunrise views from 5,545 m then descend.', 'accommodation' => 'Tea House, Pheriche', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 400],
                ['day_number' => 10, 'title' => 'Pheriche to Namche', 'description' => 'Long descent back to thicker air and cafes.', 'accommodation' => 'Tea House, Namche Bazaar', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 19, 'elevation_gain_m' => 300],
                ['day_number' => 11, 'title' => 'Namche to Lukla', 'description' => 'Final trekking day and celebration with crew.', 'accommodation' => 'Tea House, Lukla', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 18, 'elevation_gain_m' => 250],
                ['day_number' => 12, 'title' => 'Fly to Kathmandu', 'description' => 'Morning flight and leisure time in Kathmandu.', 'accommodation' => 'Hotel, Kathmandu', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],
            ],
        ];

        foreach ($itineraries as $slug => $days) {
            $trip = Trip::where('slug', $slug)->first();
            if ($trip) {
                foreach ($days as $dayData) {
                    ItineraryDay::updateOrCreate(
                        ['trip_id' => $trip->id, 'day_number' => $dayData['day_number']],
                        $dayData
                    );
                }
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ATTRACTIONS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedAttractions(): void
    {
        $attractions = [
            'kathmandu-to-mustang-motorbike-expedition' => [
                ['name' => 'Muktinath Temple', 'description' => 'Sacred pilgrimage site for both Hindus and Buddhists.', 'image_path' => 'attractions/muktinath.jpg'],
                ['name' => 'Kagbeni Village', 'description' => 'A medieval gateway to Upper Mustang with ancient mud-brick architecture.', 'image_path' => 'attractions/kagbeni.jpg'],
            ],
            'everest-base-camp-trek' => [
                ['name' => 'Khumbu Icefall', 'description' => 'A breathtaking and ever-shifting glacier visible from Base Camp.', 'image_path' => 'attractions/khumbu-icefall.jpg'],
                ['name' => 'Tengboche Monastery', 'description' => 'The largest and most significant gompa in the Khumbu region.', 'image_path' => 'attractions/tengboche-monastery.jpg'],
            ]
        ];

        foreach ($attractions as $slug => $list) {
            $trip = Trip::where('slug', $slug)->first();
            if ($trip) {
                foreach ($list as $attr) {
                    TripAttraction::updateOrCreate(
                        ['trip_id' => $trip->id, 'name' => $attr['name']],
                        $attr
                    );
                }
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FAQS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedFaqs(): void
    {
        $faqs = [
            'kathmandu-to-mustang-motorbike-expedition' => [
                ['question' => 'What license do I need?', 'answer' => 'You need a valid motorcycle license from your home country and an International Driving Permit (IDP).'],
                ['question' => 'What bike is provided?', 'answer' => 'Typically a Royal Enfield Himalayan 411cc or 450cc, maintained for high-altitude terrain.'],
            ],
            'everest-base-camp-trek' => [
                ['question' => 'How difficult is the trek?', 'answer' => 'It is a challenging trek requiring good cardiovascular fitness, but no technical climbing skills.'],
                ['question' => 'Is travel insurance mandatory?', 'answer' => 'Yes, your insurance must specifically cover high-altitude trekking up to 6,000m and emergency helicopter evacuation.'],
            ]
        ];

        foreach ($faqs as $slug => $questions) {
            $trip = Trip::where('slug', $slug)->first();
            if ($trip) {
                foreach ($questions as $faq) {
                    TripFaq::updateOrCreate(
                        ['trip_id' => $trip->id, 'question' => $faq['question']],
                        $faq
                    );
                }
            }
        }
    }
}
