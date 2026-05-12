<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Contracts\View\View;

class TripController extends Controller
{
    public function show(string $slug): View
    {
        $trip = Trip::findBySlug($slug);

        return view('pages.trips.show', [
            'trip' => $trip,
            'metaTitle' => "{$trip->title} | TravelNepal",
            'metaDescription' => $trip->overview,
            'metaImage' => $trip->cover_image,
        ]);
    }
}
