<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            'site_name'    => ['value' => 'TravelNepal',                                          'group' => 'general'],
            'site_tagline' => ['value' => 'Nepal\'s home for adventure — on two wheels or two feet.', 'group' => 'general'],

            // Contact
            'email'        => ['value' => 'hello@travelnepal.example',                            'group' => 'contact'],
            'phone'        => ['value' => '+977 1-4444-0123',                                     'group' => 'contact'],
            'address'      => ['value' => 'Thamel, Kathmandu 44600, Nepal',                       'group' => 'contact'],
            'office_hours' => ['value' => 'Sunday–Friday 9:00–18:00 NPT; Saturday by appointment', 'group' => 'contact'],

            // Social
            'facebook_url'  => ['value' => 'https://facebook.com/travelnepal',                    'group' => 'social'],
            'instagram_url' => ['value' => 'https://instagram.com/travelnepal',                   'group' => 'social'],
            'tiktok_url'    => ['value' => 'https://tiktok.com/@travelnepal',                     'group' => 'social'],
            'youtube_url'   => ['value' => 'https://youtube.com/@travelnepal',                    'group' => 'social'],
        ];

        foreach ($settings as $key => $data) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $data['value'], 'group' => $data['group']]
            );
        }
    }
}
