<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $persons = [
            ['name' => 'Nima Gurung', 'role' => 'Lead Trek Guide', 'bio' => 'High-altitude specialist with 15+ seasons across Everest and Annapurna regions.'],
            ['name' => 'Prakash Thapa', 'role' => 'Motorbike Tour Captain', 'bio' => 'Royal Enfield lead rider focused on safe pacing, route intelligence, and group cohesion.'],
            ['name' => 'Sita Karki', 'role' => 'Operations Manager', 'bio' => 'Coordinates permits, logistics, and 24/7 field support from our Kathmandu office.'],
            ['name' => 'Rohan Lama', 'role' => 'Cultural Trip Specialist', 'bio' => 'Heritage storyteller connecting travellers with temples, festivals, and living traditions.'],
        ];

        return view('pages.about', [
            'persons'   => $persons,
            'metaTitle'       => "About Us | TravelNepal",
            'metaDescription' => "Meet the passionate team behind TravelNepal, dedicated to crafting unforgettable journeys through the Himalayas.",
        ]);
    }
}
