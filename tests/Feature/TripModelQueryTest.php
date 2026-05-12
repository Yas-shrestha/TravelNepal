<?php

use App\Models\Trip;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TripSeeder;

it('findBySlug eager loads trip detail relations', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $trip = Trip::findBySlug('everest-base-camp-trek');

    expect($trip->relationLoaded('category'))->toBeTrue()
        ->and($trip->relationLoaded('itineraryDays'))->toBeTrue()
        ->and($trip->relationLoaded('attractions'))->toBeTrue()
        ->and($trip->relationLoaded('images'))->toBeTrue()
        ->and($trip->relationLoaded('faqs'))->toBeTrue()
        ->and($trip->relationLoaded('packages'))->toBeTrue()
        ->and($trip->relationLoaded('testimonials'))->toBeTrue()
        ->and($trip->relationLoaded('bikeRentals'))->toBeTrue();
});

it('withEagerLoading matches findBySlug relation keys', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $trip = Trip::query()->withEagerLoading()->where('slug', 'everest-base-camp-trek')->firstOrFail();

    expect($trip->relationLoaded('category'))->toBeTrue()
        ->and($trip->relationLoaded('packages'))->toBeTrue();
});

it('featuredWithRelations loads category and packages', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $trip = Trip::query()->featuredWithRelations()->first();

    expect($trip)->not->toBeNull()
        ->and($trip->relationLoaded('category'))->toBeTrue()
        ->and($trip->relationLoaded('packages'))->toBeTrue();
});

it('forListing loads category and packages for starting_price', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $trip = Trip::query()->forListing()->where('slug', 'everest-base-camp-trek')->firstOrFail();

    expect($trip->relationLoaded('category'))->toBeTrue()
        ->and($trip->relationLoaded('packages'))->toBeTrue()
        ->and($trip->starting_price)->toBeFloat();
});
