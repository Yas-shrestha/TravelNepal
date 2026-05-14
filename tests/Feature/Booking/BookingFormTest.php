<?php

use App\Models\Booking;
use App\Models\Category;
use App\Models\Package;
use App\Models\Trip;
use Illuminate\Support\Facades\Queue;
use Livewire\Volt\Volt;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Queue::fake(); // ✅ App is fully booted here
});
it('stores valid booking to database', function () {
    $category = Category::factory()->create();
    $trip     = Trip::factory()->create(['category_id' => $category->id]);
    $package  = Package::factory()->standard()->create(['trip_id' => $trip->id]);

    Volt::test('booking-popup')
        ->call('openPopup', $trip->id, $package->id)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('phone', '+977 9800000000')
        ->set('preferred_date', now()->addMonth()->format('Y-m-d'))
        ->set('group_size', 2)
        ->call('submit');

    expect(Booking::where('email', 'john@example.com')->exists())->toBeTrue();
});

it('requires name, email, phone, date and group size', function () {
    $category = Category::factory()->create();
    $trip = Trip::factory()->create(['category_id' => $category->id]);
    Package::factory()->create(['trip_id' => $trip->id]);

    Livewire::test('booking-popup')
        ->set('group_size', 0)   // ✅ triggers min:1
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'phone', 'preferred_date', 'group_size']);
});

it('requires license fields for motorbike trip', function () {
    $category = Category::factory()->create();
    $trip     = Trip::factory()->motorbike()->create(['category_id' => $category->id]);
    $package  = Package::factory()->create(['trip_id' => $trip->id]);

    Volt::test('booking-popup')
        ->call('openPopup', $trip->id, $package->id)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('phone', '+977 9800000000')
        ->set('preferred_date', now()->addMonth()->format('Y-m-d'))
        ->set('group_size', 2)
        ->call('submit')
        ->assertHasErrors(['has_license']);
});
