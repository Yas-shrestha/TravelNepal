<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trips/{slug}', [TripController::class, 'show'])->name('trips.show');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Volt::route('/trips', 'trip-listing')->name('trips.index');

Route::view('/contact', 'pages.contact', [
    'metaTitle'       => 'Contact | TravelNepal',
    'metaDescription' => 'Contact TravelNepal for enquiries, custom plans, and trip support.',
])->name('contact');
