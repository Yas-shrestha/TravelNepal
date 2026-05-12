<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');

Volt::route('/trips', 'trip-listing')->name('trips.index');

Route::get('/trips/{slug}', [TripController::class, 'show'])->name('trips.show');
Route::view('/about', 'pages.about', [
    'metaTitle' => 'About Us | TravelNepal',
    'metaDescription' => "Learn about TravelNepal, Nepal's home for adventure experiences.",
])->name('about');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::view('/contact', 'pages.contact', [
    'metaTitle' => 'Contact | TravelNepal',
    'metaDescription' => 'Contact TravelNepal for enquiries, custom plans, and trip support.',
])->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
