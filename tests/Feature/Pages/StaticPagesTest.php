<?php

use App\Models\Faq;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('loads about page', function () {
    $this->get(route('about'))->assertStatus(200);
});

it('loads faq page', function () {
    Faq::factory()->create(['question' => 'What is the best season?']);

    $response = $this->get(route('faq'));
    $response->assertStatus(200);
    $response->assertSee('What is the best season?');
});

it('loads contact page', function () {
    $this->get(route('contact'))->assertStatus(200);
});
