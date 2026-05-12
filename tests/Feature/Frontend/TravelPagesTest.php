<?php

use App\Models\Trip;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TripSeeder;

it('loads public marketing pages', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $this->get(route('home'))->assertSuccessful();
    $this->get(route('about'))->assertSuccessful();
    $this->get(route('contact'))->assertSuccessful();
    $this->get(route('faq'))->assertSuccessful();
    $this->get(route('trips.index'))->assertSuccessful();
});

it('loads trip detail page by slug', function (): void {
    $this->seed([
        CategorySeeder::class,
        TripSeeder::class,
        PackageSeeder::class,
    ]);

    $trip = Trip::query()->where('slug', 'everest-base-camp-trek')->firstOrFail();

    $this->get(route('trips.show', $trip->slug))
        ->assertSuccessful()
        ->assertSee($trip->title);
});
