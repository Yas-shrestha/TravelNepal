<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LayoutComposer
{
    /**
     * Fallback values for every setting key used in the layout/footer.
     * If the DB has no row for a key, these are used instead of null/empty.
     */
    private const SETTING_DEFAULTS = [
        'site_name'     => 'TravelNepal',
        'site_tagline'  => "Nepal's home for adventure — on two wheels or two feet.",
        'address'       => 'Thamel, Kathmandu, Nepal',
        'phone'         => '+977 1 000 0000',
        'email'         => 'hello@travelnepal.com',
        'office_hours'  => 'Sun - Fri, 9 AM - 6 PM NPT',
        'facebook_url'  => null,
        'instagram_url' => null,
        'tiktok_url'    => null,
        'youtube_url'   => null,
    ];

    public function compose(View $view): void
    {
        $view->with('siteSettings', $this->settings());
        $view->with('menuCategories', $this->menuCategories());
    }

    /**
     * Returns a Collection keyed by setting key.
     * Any missing key falls back to SETTING_DEFAULTS.
     * Usage in Blade: $siteSettings->get('site_name')  — never returns null for known keys.
     */
    private function settings(): Collection
    {
        $fromDb = Setting::pluck('value', 'key');

        // Merge defaults underneath DB values so DB always wins,
        // but missing keys are covered.
        return collect(self::SETTING_DEFAULTS)->merge($fromDb);
    }

    /**
     * Categories that have at least one active trip.
     * Returns empty collection (never null) if none exist.
     */
    private function menuCategories(): Collection
    {
        return Category::query()
            ->whereHas('trips', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();
    }
}
