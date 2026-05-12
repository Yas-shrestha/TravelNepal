<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use  Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    public function show(string $slug): View
    {
        $trip = Trip::findBySlug($slug);
        $galleryUrls = $trip->images
            ->map(fn($image) => Str::startsWith($image->image_path, ['http://', 'https://']) ? $image->image_path : Storage::url($image->image_path))
            ->values()
            ->all();

        $galleryCaptions = $trip->images->map(fn($image) => $image->caption ?? $trip->title)->values()->all();
        return view('pages.trips.show', [
            'trip' => $trip,
            'metaTitle' => "{$trip->title} | TravelNepal",
            'metaDescription' => $trip->overview,
            'metaImage' => $trip->cover_image,
            'galleryUrls' => $galleryUrls,
            'galleryCaptions' => $galleryCaptions,
        ]);
    }
}
