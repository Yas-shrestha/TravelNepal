<?php

use App\Models\Booking;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Package;
use App\Models\PackageInclusion;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Trip;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\FaqSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\TripSeeder;

it('seeds categories, trips, packages, faqs, settings, and testimonials', function () {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
        BookingSeeder::class,
        FaqSeeder::class,
        SettingSeeder::class,
        TestimonialSeeder::class,
    ]);

    expect(Category::query()->count())->toBe(5)
        ->and(Trip::query()->count())->toBe(13)
        ->and(Trip::query()->where('is_featured', true)->count())->toBe(6)
        ->and(Trip::query()->where('requires_bike', true)->count())->toBe(3)
        ->and(Package::query()->count())->toBe(39)
        ->and(PackageInclusion::query()->count())->toBe(312)
        ->and(Booking::query()->count())->toBe(10)
        ->and(Booking::query()->where('status', 'pending')->count())->toBe(4)
        ->and(Booking::query()->where('status', 'confirmed')->count())->toBe(3)
        ->and(Booking::query()->where('status', 'cancelled')->count())->toBe(3)
        ->and(Booking::query()->whereNotNull('has_license')->count())->toBe(5)
        ->and(Booking::query()->where('has_own_bike', false)->whereNotNull('rental_bike_id')->count())->toBe(2)
        ->and(Faq::query()->count())->toBe(8)
        ->and(Setting::query()->count())->toBe(10)
        ->and(Testimonial::query()->where('is_approved', true)->count())->toBe(5);
});
