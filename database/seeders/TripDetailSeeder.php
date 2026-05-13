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

            'annapurna-circuit-trek' => [
                ['day_number' => 1, 'title' => 'Drive Kathmandu to Besisahar', 'description' => 'Scenic drive to the Annapurna region gateway.', 'accommodation' => 'Guesthouse, Besisahar', 'meals_included' => 'Lunch, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 150],
                ['day_number' => 2, 'title' => 'Besisahar to Chame', 'description' => 'Drive deeper into the Marsyangdi valley.', 'accommodation' => 'Tea House, Chame', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 1100],
                ['day_number' => 3, 'title' => 'Chame to Pisang', 'description' => 'Trek through pine forests and dramatic cliffs.', 'accommodation' => 'Tea House, Pisang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 13, 'elevation_gain_m' => 550],
                ['day_number' => 4, 'title' => 'Pisang to Manang', 'description' => 'Gradual climb with Annapurna II views.', 'accommodation' => 'Tea House, Manang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 17, 'elevation_gain_m' => 400],
                ['day_number' => 5, 'title' => 'Acclimatisation Day in Manang', 'description' => 'Optional hikes to Ice Lake or Gangapurna.', 'accommodation' => 'Tea House, Manang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 6, 'elevation_gain_m' => 500],
                ['day_number' => 6, 'title' => 'Manang to Yak Kharka', 'description' => 'Enter high alpine grazing country.', 'accommodation' => 'Tea House, Yak Kharka', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 10, 'elevation_gain_m' => 400],
                ['day_number' => 7, 'title' => 'Yak Kharka to Thorong Phedi', 'description' => 'Prepare for the high pass crossing.', 'accommodation' => 'Tea House, Thorong Phedi', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 7, 'elevation_gain_m' => 450],
                ['day_number' => 8, 'title' => 'Cross Thorong La to Muktinath', 'description' => 'Biggest challenge and highlight of the trek.', 'accommodation' => 'Lodge, Muktinath', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 900],
            ],

            'langtang-valley-trek' => [
                ['day_number' => 1, 'title' => 'Drive Kathmandu to Syabrubesi', 'description' => 'Journey north into Langtang National Park.', 'accommodation' => 'Guesthouse, Syabrubesi', 'meals_included' => 'Lunch, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 700],
                ['day_number' => 2, 'title' => 'Syabrubesi to Lama Hotel', 'description' => 'Forest trails beside the Langtang River.', 'accommodation' => 'Tea House, Lama Hotel', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 11, 'elevation_gain_m' => 980],
                ['day_number' => 3, 'title' => 'Lama Hotel to Langtang Village', 'description' => 'Enter alpine terrain with mountain views.', 'accommodation' => 'Tea House, Langtang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 14, 'elevation_gain_m' => 1000],
                ['day_number' => 4, 'title' => 'Langtang to Kyanjin Gompa', 'description' => 'Short scenic trek to the spiritual center of the valley.', 'accommodation' => 'Tea House, Kyanjin Gompa', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 7, 'elevation_gain_m' => 380],
                ['day_number' => 5, 'title' => 'Kyanjin Exploration Day', 'description' => 'Optional glacier or viewpoint hikes.', 'accommodation' => 'Tea House, Kyanjin Gompa', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 8, 'elevation_gain_m' => 700],
                ['day_number' => 6, 'title' => 'Kyanjin to Lama Hotel', 'description' => 'Descend through yak pastures and forests.', 'accommodation' => 'Tea House, Lama Hotel', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 21, 'elevation_gain_m' => 100],
                ['day_number' => 7, 'title' => 'Lama Hotel to Syabrubesi', 'description' => 'Final trekking day.', 'accommodation' => 'Guesthouse, Syabrubesi', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 11, 'elevation_gain_m' => 100],
                ['day_number' => 8, 'title' => 'Drive Back to Kathmandu', 'description' => 'Return to the capital city.', 'accommodation' => 'Hotel, Kathmandu', 'meals_included' => 'Breakfast, Lunch', 'distance_km' => 0, 'elevation_gain_m' => 0],
            ],
            'manaslu-circuit-trek' => [
                ['day_number' => 1, 'title' => 'Drive Kathmandu to Machha Khola', 'description' => 'Long drive to the trailhead.', 'accommodation' => 'Tea House, Machha Khola', 'meals_included' => 'Lunch, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 850],
                ['day_number' => 2, 'title' => 'Machha Khola to Jagat', 'description' => 'Follow the Budhi Gandaki upstream.', 'accommodation' => 'Tea House, Jagat', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 14, 'elevation_gain_m' => 650],
                ['day_number' => 3, 'title' => 'Jagat to Deng', 'description' => 'Cross suspension bridges and remote villages.', 'accommodation' => 'Tea House, Deng', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 20, 'elevation_gain_m' => 700],
                ['day_number' => 4, 'title' => 'Deng to Namrung', 'description' => 'Enter Tibetan-influenced mountain settlements.', 'accommodation' => 'Tea House, Namrung', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 800],
                ['day_number' => 5, 'title' => 'Namrung to Samagaon', 'description' => 'Trek through alpine meadows with Manaslu views.', 'accommodation' => 'Tea House, Samagaon', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 12, 'elevation_gain_m' => 400],
                ['day_number' => 6, 'title' => 'Samagaon to Samdo', 'description' => 'Short day to the holy lake and high camp.', 'accommodation' => 'Tea House, Samdo', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 8, 'elevation_gain_m' => 300],
                ['day_number' => 7, 'title' => 'Samdo to Dharamsala', 'description' => 'Cross the Larkya La (5,160 m) with stunning views.', 'accommodation' => 'Tea House, Dharamsala', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 1200],
                ['day_number' => 8, 'title' => 'Dharamsala to Bimtang', 'description' => 'Descend to the remote village of Bimtang.', 'accommodation' => 'Tea House, Bimtang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 20, 'elevation_gain_m' => 500],
                ['day_number' => 9, 'title' => 'Bimtang to Dharapani', 'description' => 'Long descent through rhododendron forests.', 'accommodation' => 'Tea House, Dharapani', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 25, 'elevation_gain_m' => 300],
                ['day_number' => 10, 'title' => 'Dharapani to Jagat', 'description' => 'Retrace steps back to Jagat.', 'accommodation' => 'Tea House, Jagat', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 18, 'elevation_gain_m' => 200],
                ['day_number' => 11, 'title' => 'Jagat to Machha Khola', 'description' => 'Final trekking day.', 'accommodation' => 'Tea House, Machha Khola', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 14, 'elevation_gain_m' => 150],
                ['day_number' => 12, 'title' => 'Drive Back to Kathmandu', 'description' => 'Return to the capital city.', 'accommodation' => 'Hotel, Kathmandu', 'meals_included' => 'Breakfast, Lunch', 'distance_km' => 0, 'elevation_gain_m' => 0],
            ],
            'Kathmandu Valley Cycling Tour' => [
                ['day_number' => 1, 'title' => 'Arrive Kathmandu & Bike Fitting', 'description' => 'Meet the team and get fitted for your bike.', 'accommodation' => 'Hotel in Thamel', 'meals_included' => 'Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],
                ['day_number' => 2, 'title' => 'Kathmandu to Bhaktapur Loop', 'description' => 'Cycle through ancient cities and UNESCO sites.', 'accommodation' => 'Hotel in Bhaktapur', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 40, 'elevation_gain_m' => 500],
                ['day_number' => 3, 'title' => 'Bhaktapur to Nagarkot', 'description' => 'Climb to Nagarkot for sunset views over the Himalayas.', 'accommodation' => 'Hotel in Nagarkot', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 30, 'elevation_gain_m' => 800],
                ['day_number' => 4, 'title' => 'Nagarkot to Dhulikhel', 'description' => 'Descend and cycle through traditional villages.', 'accommodation' => 'Hotel in Dhulikhel', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 25, 'elevation_gain_m' => 300],
                ['day_number' => 5, 'title' => 'Dhulikhel to Panauti Loop', 'description' => 'Explore the historic town of Panauti.', 'accommodation' => 'Hotel in Dhulikhel', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 35, 'elevation_gain_m' => 400],
                ['day_number' => 6, 'title' => 'Dhulikhel to Kathmandu via Phulchowki', 'description' => 'Final ride back with a climb up Phulchowki for panoramic views.', 'accommodation' => 'Hotel in Kathmandu', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 45, 'elevation_gain_m' => 900],
            ],
            'Pokhara to Chitwan Cycling Adventure' => [
                ['day_number' => 1, 'title' => 'Arrive Pokhara & Bike Fitting', 'description' => 'Meet the team and get fitted for your bike.', 'accommodation' => 'Hotel in Lakeside Pokhara', 'meals_included' => 'Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],
                ['day_number' => 2, 'title' => 'Pokhara to Sarangkot Loop', 'description' => 'Warm-up ride with stunning views of the Annapurna range.', 'accommodation' => 'Hotel in Lakeside Pokhara', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 20, 'elevation_gain_m' => 600],
                ['day_number' => 3, 'title' => 'Pokhara to Tansen', 'description' => 'Cycle through rural landscapes and hill towns.', 'accommodation' => 'Hotel in Tansen', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 80, 'elevation_gain_m' => 1200],
                ['day_number' => 4, 'title' => 'Tansen to Sauraha (Chitwan)', 'description' => 'Descend into the Terai plains and jungle edge.', 'accommodation' => 'Lodge in Sauraha', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 70, 'elevation_gain_m' => 300],
                ['day_number' => 5, 'title' => 'Chitwan National Park Activities', 'description' => 'Jeep safari and canoe trip to spot wildlife.', 'accommodation' => 'Lodge in Sauraha', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],
                ['day_number' => 6, 'title' => 'Sauraha to Pokhara via Devghat', 'description' => 'Final ride back with a stop at the sacred confluence of rivers.', 'accommodation' => 'Hotel in Lakeside Pokhara', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 90, 'elevation_gain_m' => 400],
            ],
            'Upper Mustang Cultural Tour' => [
                ['day_number' => 1, 'title' => 'Arrive Kathmandu & Briefing', 'description' => 'Meet the team and get an introduction to Mustang’s culture and journey ahead.', 'accommodation' => 'Hotel in Thamel', 'meals_included' => 'Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],

                ['day_number' => 2, 'title' => 'Kathmandu Heritage Exploration', 'description' => 'Visit Swayambhunath, Boudhanath, and Patan Durbar Square before the Mustang journey.', 'accommodation' => 'Hotel in Kathmandu', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 20, 'elevation_gain_m' => 150],

                ['day_number' => 3, 'title' => 'Fly to Pokhara', 'description' => 'Scenic Himalayan flight and relaxing lakeside evening in Pokhara.', 'accommodation' => 'Hotel in Pokhara', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],

                ['day_number' => 4, 'title' => 'Fly to Jomsom & Explore', 'description' => 'Short mountain flight and visit Jomsom’s market, monasteries, and Kali Gandaki riverside.', 'accommodation' => 'Hotel in Jomsom', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 10, 'elevation_gain_m' => 120],

                ['day_number' => 5, 'title' => 'Jomsom to Kagbeni', 'description' => 'Travel to the ancient gateway village of Upper Mustang and explore narrow alleys and monasteries.', 'accommodation' => 'Guesthouse in Kagbeni', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 180],

                ['day_number' => 6, 'title' => 'Kagbeni to Ghami', 'description' => 'Drive through dramatic desert landscapes, caves, and traditional Tibetan villages.', 'accommodation' => 'Tea House in Ghami', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 45, 'elevation_gain_m' => 650],

                ['day_number' => 7, 'title' => 'Ghami to Lo Manthang', 'description' => 'Reach the legendary walled capital of Upper Mustang.', 'accommodation' => 'Hotel in Lo Manthang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 35, 'elevation_gain_m' => 450],

                ['day_number' => 8, 'title' => 'Lo Manthang Cultural Exploration', 'description' => 'Visit ancient monasteries, royal palaces, and local cultural sites around Lo Manthang.', 'accommodation' => 'Hotel in Lo Manthang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 15, 'elevation_gain_m' => 200],

                ['day_number' => 9, 'title' => 'Excursion to Chhoser Cave Monasteries', 'description' => 'Explore sky caves, Nyphu Monastery, and remote settlements near the Tibetan border.', 'accommodation' => 'Hotel in Lo Manthang', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 25, 'elevation_gain_m' => 250],

                ['day_number' => 10, 'title' => 'Lo Manthang to Chele', 'description' => 'Descend through Charang and Ghiling villages with spectacular canyon scenery.', 'accommodation' => 'Guesthouse in Chele', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 70, 'elevation_gain_m' => 300],

                ['day_number' => 11, 'title' => 'Chele to Jomsom', 'description' => 'Return along the Kali Gandaki corridor with stops at monasteries and viewpoints.', 'accommodation' => 'Hotel in Jomsom', 'meals_included' => 'Breakfast, Lunch, Dinner', 'distance_km' => 80, 'elevation_gain_m' => 400],

                ['day_number' => 12, 'title' => 'Fly to Pokhara & Kathmandu', 'description' => 'Morning mountain flight to Pokhara and onward connection to Kathmandu for farewell dinner.', 'accommodation' => 'Hotel in Kathmandu', 'meals_included' => 'Breakfast, Dinner', 'distance_km' => 0, 'elevation_gain_m' => 0],

            ]


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

            'everest-highway-royal-enfield-ride' => [
                ['name' => 'Phaplu Viewpoint', 'description' => 'Beautiful Himalayan ridge viewpoint overlooking the lower Everest region.', 'image_path' => 'attractions/phaplu-viewpoint.jpg'],
                ['name' => 'Sherpa Villages', 'description' => 'Traditional mountain settlements rich in Buddhist culture and hospitality.', 'image_path' => 'attractions/sherpa-village.jpg'],
            ],

            'nepal-himalaya-circuit-by-motorbike' => [
                ['name' => 'Kali Gandaki Gorge', 'description' => 'One of the deepest river gorges in the world surrounded by giant Himalayan peaks.', 'image_path' => 'attractions/kali-gandaki.jpg'],
                ['name' => 'Muktinath', 'description' => 'Ancient pilgrimage site blending Hindu and Buddhist traditions.', 'image_path' => 'attractions/muktinath-temple.jpg'],
            ],

            'everest-base-camp-trek' => [
                ['name' => 'Khumbu Icefall', 'description' => 'A breathtaking and ever-shifting glacier visible from Base Camp.', 'image_path' => 'attractions/khumbu-icefall.jpg'],
                ['name' => 'Tengboche Monastery', 'description' => 'The largest and most significant gompa in the Khumbu region.', 'image_path' => 'attractions/tengboche-monastery.jpg'],
            ],

            'annapurna-circuit-trek' => [
                ['name' => 'Thorong La Pass', 'description' => 'One of the world’s most iconic high mountain trekking passes.', 'image_path' => 'attractions/thorong-la.jpg'],
                ['name' => 'Manang Village', 'description' => 'A scenic Himalayan settlement famous for acclimatisation and mountain culture.', 'image_path' => 'attractions/manang.jpg'],
            ],

            'langtang-valley-trek' => [
                ['name' => 'Kyanjin Gompa', 'description' => 'Historic monastery village surrounded by glaciers and snow peaks.', 'image_path' => 'attractions/kyanjin-gompa.jpg'],
                ['name' => 'Langtang National Park', 'description' => 'Protected Himalayan wilderness filled with forests, wildlife, and alpine landscapes.', 'image_path' => 'attractions/langtang-national-park.jpg'],
            ],

            'manaslu-circuit-trek' => [
                ['name' => 'Larkya La Pass', 'description' => 'A dramatic high-altitude crossing with panoramic Himalayan views.', 'image_path' => 'attractions/larkya-la.jpg'],
                ['name' => 'Samagaon', 'description' => 'Traditional Tibetan-influenced village beneath Mount Manaslu.', 'image_path' => 'attractions/samagaon.jpg'],
            ],

            'kathmandu-valley-cycling-tour' => [
                ['name' => 'Bhaktapur Durbar Square', 'description' => 'Ancient royal square filled with temples, courtyards, and Newari culture.', 'image_path' => 'attractions/bhaktapur.jpg'],
                ['name' => 'Nagarkot View Tower', 'description' => 'Popular hill station viewpoint for Himalayan sunrise panoramas.', 'image_path' => 'attractions/nagarkot.jpg'],
            ],

            'pokhara-to-chitwan-cycling-adventure' => [
                ['name' => 'Phewa Lake', 'description' => 'Pokhara’s iconic lakeside destination beneath the Annapurna range.', 'image_path' => 'attractions/phewa-lake.jpg'],
                ['name' => 'Chitwan National Park', 'description' => 'UNESCO-listed jungle famous for rhinos, elephants, and wildlife safaris.', 'image_path' => 'attractions/chitwan.jpg'],
            ],

            'upper-mustang-cultural-tour' => [
                ['name' => 'Lo Manthang', 'description' => 'Ancient walled capital of Upper Mustang rich in Tibetan Buddhist culture.', 'image_path' => 'attractions/lo-manthang.jpg'],
                ['name' => 'Chhoser Sky Caves', 'description' => 'Mysterious cliffside cave complexes carved into Mustang’s rocky landscape.', 'image_path' => 'attractions/chhoser-caves.jpg'],
            ],

            'kathmandu-heritage-valley-tour' => [
                ['name' => 'Boudhanath Stupa', 'description' => 'One of the world’s largest Buddhist stupas and a spiritual center of Kathmandu.', 'image_path' => 'attractions/boudhanath.jpg'],
                ['name' => 'Patan Durbar Square', 'description' => 'Historic royal palace complex showcasing exquisite Newari architecture.', 'image_path' => 'attractions/patan-durbar-square.jpg'],
            ],

            'nagarkot-sunrise-hike' => [
                ['name' => 'Nagarkot Viewpoint', 'description' => 'Famous sunrise viewpoint with panoramic Himalayan vistas.', 'image_path' => 'attractions/nagarkot-viewpoint.jpg'],
                ['name' => 'Terraced Hillsides', 'description' => 'Traditional Nepali countryside landscapes along the hiking trail.', 'image_path' => 'attractions/terraced-fields.jpg'],
            ],

            'poon-hill-hike' => [
                ['name' => 'Poon Hill', 'description' => 'Legendary sunrise viewpoint overlooking Annapurna and Dhaulagiri.', 'image_path' => 'attractions/poon-hill.jpg'],
                ['name' => 'Ghandruk Village', 'description' => 'Beautiful Gurung settlement known for culture and mountain scenery.', 'image_path' => 'attractions/ghandruk.jpg'],
            ],

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

            'everest-highway-royal-enfield-ride' => [
                ['question' => 'Is this ride suitable for beginners?', 'answer' => 'Riders should have prior experience with mountain roads and basic off-road riding skills.'],
                ['question' => 'Do I need to bring riding gear?', 'answer' => 'You may bring your own gear, but helmets, gloves, and riding jackets can also be arranged in Kathmandu.'],
            ],

            'nepal-himalaya-circuit-by-motorbike' => [
                ['question' => 'How physically demanding is the tour?', 'answer' => 'This is a long-distance expedition requiring endurance, confidence on mixed terrain, and long riding days.'],
                ['question' => 'Is mechanic support included?', 'answer' => 'Yes, the tour includes mechanic support, basic spare parts, and backup assistance.'],
            ],

            'everest-base-camp-trek' => [
                ['question' => 'How difficult is the trek?', 'answer' => 'It is a challenging trek requiring good cardiovascular fitness, but no technical climbing skills.'],
                ['question' => 'Is travel insurance mandatory?', 'answer' => 'Yes, your insurance must specifically cover high-altitude trekking up to 6,000m and emergency helicopter evacuation.'],
            ],

            'annapurna-circuit-trek' => [
                ['question' => 'What is the highest point of the trek?', 'answer' => 'Thorong La Pass at 5,416 meters is the highest point of the Annapurna Circuit.'],
                ['question' => 'Do I need previous trekking experience?', 'answer' => 'Previous trekking experience is helpful but not mandatory if you are physically fit and prepared.'],
            ],

            'langtang-valley-trek' => [
                ['question' => 'Is Langtang less crowded than Everest?', 'answer' => 'Yes, Langtang is generally quieter and offers a more peaceful trekking experience.'],
                ['question' => 'Can beginners do this trek?', 'answer' => 'Yes, it is suitable for beginners with a reasonable level of fitness and preparation.'],
            ],

            'manaslu-circuit-trek' => [
                ['question' => 'Do I need a special permit for Manaslu?', 'answer' => 'Yes, Manaslu is a restricted region and requires a special trekking permit and licensed guide.'],
                ['question' => 'Can I trek independently?', 'answer' => 'No, independent trekking is not permitted in the Manaslu restricted area.'],
            ],

            'kathmandu-valley-cycling-tour' => [
                ['question' => 'What type of bicycle is provided?', 'answer' => 'Typically high-quality mountain bikes or hybrid bikes suitable for valley roads and hills.'],
                ['question' => 'Is the cycling tour family-friendly?', 'answer' => 'Yes, the itinerary is designed with manageable daily distances and flexible pacing.'],
            ],

            'pokhara-to-chitwan-cycling-adventure' => [
                ['question' => 'Are support vehicles available?', 'answer' => 'Yes, a support vehicle follows the group for luggage transfer and emergencies.'],
                ['question' => 'What wildlife may I see in Chitwan?', 'answer' => 'You may spot rhinos, deer, crocodiles, elephants, and many bird species during safari activities.'],
            ],

            'upper-mustang-cultural-tour' => [
                ['question' => 'Do I need a restricted area permit?', 'answer' => 'Yes, Upper Mustang requires a special restricted area permit arranged before the tour.'],
                ['question' => 'What makes Upper Mustang unique?', 'answer' => 'Upper Mustang preserves ancient Tibetan Buddhist culture, dramatic desert landscapes, and centuries-old monasteries.'],
            ],

            'kathmandu-heritage-valley-tour' => [
                ['question' => 'Are monument entrance fees included?', 'answer' => 'Yes, all major heritage site entrance fees are included in the package.'],
                ['question' => 'Can the itinerary be customized?', 'answer' => 'Yes, optional activities and additional sightseeing can be added upon request.'],
            ],

            'nagarkot-sunrise-hike' => [
                ['question' => 'How difficult is the hike?', 'answer' => 'The hike is easy and suitable for most fitness levels.'],
                ['question' => 'What mountains can be seen from Nagarkot?', 'answer' => 'On clear days, you may see ranges stretching from Langtang to Everest.'],
            ],

            'poon-hill-hike' => [
                ['question' => 'Is Poon Hill suitable for beginners?', 'answer' => 'Yes, it is one of Nepal’s best short treks for beginner hikers and families.'],
                ['question' => 'When is the best season for Poon Hill?', 'answer' => 'Spring and autumn offer the clearest mountain views and best weather conditions.'],
            ],

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
