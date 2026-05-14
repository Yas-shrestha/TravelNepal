<?php

use App\Models\Category;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Trip;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('loads homepage successfully', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
});

it('shows featured trips on homepage', function () {
    $category = Category::factory()->create();
    $trip = Trip::factory()->featured()->create(['category_id' => $category->id]);
    Package::factory()->standard()->create(['trip_id' => $trip->id]);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee($trip->title);
});

it('shows categories on homepage', function () {
    $category = Category::factory()->create(['name' => 'Trekking']);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('Trekking');
});

it('shows approved testimonials on homepage', function () {
    $testimonial = Testimonial::factory()->create([
        'name'        => 'John Nepal',
        'is_approved' => true,
    ]);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('John Nepal');
});

it('does not show unapproved testimonials', function () {
    $testimonial = Testimonial::factory()->pending()->create([
        'name' => 'Hidden User',
    ]);

    $response = $this->get(route('home'));
    $response->assertDontSee('Hidden User');
});
