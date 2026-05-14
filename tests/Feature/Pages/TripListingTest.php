<?php

use App\Models\Category;
use App\Models\Package;
use App\Models\Trip;
use Livewire\Volt\Volt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('loads trip listing page', function () {
    $this->get(route('trips.index'))->assertStatus(200);
});
it('shows active trips', function () {
    $category = Category::factory()->create();
    $trip = Trip::factory()->create(['category_id' => $category->id, 'title' => 'Everest Base Camp Trek']);
    Package::factory()->standard()->create(['trip_id' => $trip->id]);

    Volt::test('trip-listing')->assertSee('Everest Base Camp Trek');
});

it('does not show inactive trips', function () {
    $category = Category::factory()->create();
    Trip::factory()->inactive()->create(['category_id' => $category->id, 'title' => 'Hidden Trip']);

    Volt::test('trip-listing')->assertDontSee('Hidden Trip');
});

it('can filter by category', function () {
    $trekking  = Category::factory()->create(['slug' => 'trekking', 'name' => 'Trekking']);
    $motorbike = Category::factory()->create(['slug' => 'motorbike-tours', 'name' => 'Motorbike Tours']);

    Package::factory()->create(['trip_id' => Trip::factory()->create(['category_id' => $trekking->id,  'title' => 'Annapurna Trek'])->id]);
    Package::factory()->create(['trip_id' => Trip::factory()->create(['category_id' => $motorbike->id, 'title' => 'Mustang Ride'])->id]);

    Volt::test('trip-listing')
        ->set('category', 'trekking')
        ->assertSee('Annapurna Trek')
        ->assertDontSee('Mustang Ride');
});

it('can filter by difficulty', function () {
    $category = Category::factory()->create();
    Package::factory()->create(['trip_id' => Trip::factory()->create(['category_id' => $category->id, 'difficulty' => 'easy',    'title' => 'Easy Hike'])->id]);
    Package::factory()->create(['trip_id' => Trip::factory()->create(['category_id' => $category->id, 'difficulty' => 'extreme', 'title' => 'Extreme Climb'])->id]);

    Volt::test('trip-listing')
        ->set('difficulty', 'easy')
        ->assertSee('Easy Hike')
        ->assertDontSee('Extreme Climb');
});

it('shows empty state when no trips match filters', function () {
    Volt::test('trip-listing')
        ->set('difficulty', 'extreme')
        ->assertSee('No trips found');
});
