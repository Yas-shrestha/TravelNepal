<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('pages.faq', [
            'faqs' => Faq::query()->orderBy('sort_order')->get(),
            'metaTitle' => 'Frequently Asked Questions | TravelNepal',
            'metaDescription' => 'Answers to common questions about TravelNepal trips, bookings, permits, and logistics.',
        ]);
    }
}
