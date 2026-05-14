<?php

use App\Models\Category;
use App\Models\Package;
use App\Models\Trip;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('loads trip detail for valid slug', function () {
    $category = Category::factory()->create();
    $trip     = Trip::factory()->create([
        'category_id' => $category->id,
        'slug'        => 'everest-base-camp-trek',
        'title'       => 'Everest Base Camp Trek',
    ]);
    Package::factory()->standard()->create(['trip_id' => $trip->id]);

    $response = $this->get(route('trips.show', 'everest-base-camp-trek'));
    $response->assertStatus(200);
    $response->assertSee('Everest Base Camp Trek');
});

it('returns 404 for invalid slug', function () {
    $response = $this->get(route('trips.show', 'non-existent-trip'));
    $response->assertStatus(404);
});

it('returns 404 for inactive trip', function () {
    $category = Category::factory()->create();
    Trip::factory()->inactive()->create([
        'category_id' => $category->id,
        'slug'        => 'inactive-trip',
    ]);

    $response = $this->get(route('trips.show', 'inactive-trip'));
    $response->assertStatus(404);
});
