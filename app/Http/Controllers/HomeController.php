<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Trip;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredTrips = Trip::query()
            ->featuredWithRelations()
            ->take(6)
            ->get();

        $categories = Category::query()
            ->withCount(['trips' => fn ($query) => $query->where('is_active', true)])
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_approved', true)
            ->with(['trip' => fn ($query) => $query->select('id', 'title', 'slug')])
            ->latest()
            ->take(3)
            ->get();

        $settings = Setting::query()
            ->pluck('value', 'key');

        return view('pages.home', [
            'featuredTrips' => $featuredTrips,
            'categories' => $categories,
            'testimonials' => $testimonials,
            'settings' => $settings,
            'metaTitle' => 'TravelNepal | Adventure Tours in Nepal',
            'metaDescription' => 'Discover premium trekking, motorbike, cycling, and cultural adventures across Nepal.',
            'metaImage' => $featuredTrips->first()?->cover_image,
        ]);
    }
}
