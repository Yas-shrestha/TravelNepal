<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredTrips = Trip::query()
            ->featuredWithRelations()
            ->take(6)
            ->get();

        $categories = Category::query()
            ->withCount(['trips' => fn($query) => $query->where('is_active', true)])
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_approved', true)
            ->with(['trip' => fn($query) => $query->select('id', 'title', 'slug')])
            ->latest()
            ->take(3)
            ->get();

        $settings = Setting::query()
            ->pluck('value', 'key');
        $whyChooseUsItems = [
            ['title' => 'Licensed Adventure Guides', 'text' => 'Government-licensed leaders who know Nepal’s trails, roads, and weather windows.', 'icon' => 'shield'],
            ['title' => 'Small Groups', 'text' => 'Intimate departures for safer pacing, better support, and a richer experience.', 'icon' => 'users'],
            ['title' => 'Motorbikes & Gear Provided', 'text' => 'Reliable Royal Enfield–style touring setups and practical gear when you need it.', 'icon' => 'wrench'],
            ['title' => '24/7 On-Road Support', 'text' => 'Kathmandu office and field crew on-call when you are moving between regions.', 'icon' => 'phone'],
        ];
        $heroImage = $featuredTrips->first()?->cover_image;
        $heroImageUrl = Str::startsWith($heroImage ?? '', ['http://', 'https://'])
            ? $heroImage
            : ($heroImage ? Storage::url($heroImage) : 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=2070&q=80&auto=format&fit=crop');
        $testimonialCount = $testimonials->count();
        return view('pages.home', [
            'featuredTrips' => $featuredTrips,
            'categories' => $categories,
            'testimonials' => $testimonials,
            'settings' => $settings,
            'whyChooseUsItems' => $whyChooseUsItems,
            'metaTitle' => 'TravelNepal | Adventure Tours in Nepal',
            'metaDescription' => 'Discover premium trekking, motorbike, cycling, and cultural adventures across Nepal.',
            'metaImage' => $featuredTrips->first()?->cover_image,
            'heroImageUrl' => $heroImageUrl,
            'testimonialCount' => $testimonialCount,

        ]);
    }
}
