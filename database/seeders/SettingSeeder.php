<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'TravelNepal',
            'site_tagline' => 'Nepal\'s home for adventure — on two wheels or two feet.',
            'email' => 'hello@travelnepal.example',
            'phone' => '+977 1-4444-0123',
            'address' => 'Thamel, Kathmandu 44600, Nepal',
            'office_hours' => 'Sunday–Friday 9:00–18:00 NPT; Saturday by appointment',
            'facebook_url' => 'https://facebook.com/travelnepal',
            'instagram_url' => 'https://instagram.com/travelnepal',
            'tiktok_url' => 'https://tiktok.com/@travelnepal',
            'youtube_url' => 'https://youtube.com/@travelnepal',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }
    }
}
