<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;

class TripController extends Controller
{
    public function show(string $slug): View
    {
        $trip = Trip::findBySlug($slug);

        // Build gallery arrays from eager-loaded images — no extra queries.
        $galleryUrls     = $trip->images->map(fn($img) => $img->image_url)->values()->all();
        $galleryCaptions = $trip->images->map(fn($img) => $img->caption ?? $trip->title)->values()->all();

        return view('pages.trips.show', [
            'trip'            => $trip,
            'galleryUrls'     => $galleryUrls,
            'galleryCaptions' => $galleryCaptions,
            'metaTitle'       => "{$trip->title} | TravelNepal",
            'metaDescription' => $trip->overview,
            'metaImage'       => $trip->image_url, // full URL via accessor — not raw cover_image
        ]);
    }
}
