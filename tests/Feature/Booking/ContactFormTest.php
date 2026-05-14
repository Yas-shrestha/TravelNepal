<?php

use App\Models\Enquiry;

use Illuminate\Support\Facades\Queue;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Queue::fake(); // ✅ App is fully booted here
});
it('stores valid enquiry to database', function () {
    \Livewire\Volt\Volt::test('contact-form')
        ->set('name', 'Jane Doe')
        ->set('email', 'jane@example.com')
        ->set('subject', 'General Enquiry')
        ->set('message', 'I would like to know more about your trips.')
        ->call('submit');

    expect(Enquiry::where('email', 'jane@example.com')->exists())->toBeTrue();
});

it('requires name, email and message', function () {
    \Livewire\Volt\Volt::test('contact-form')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message']);
});

it('requires valid email format', function () {
    \Livewire\Volt\Volt::test('contact-form')
        ->set('name', 'Jane Doe')
        ->set('email', 'not-an-email')
        ->set('message', 'Hello')
        ->call('submit')
        ->assertHasErrors(['email']);
});
