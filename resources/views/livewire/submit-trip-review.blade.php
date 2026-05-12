<?php

use App\Models\Testimonial;
use App\Models\Trip;
use Livewire\Volt\Component;

new class extends Component
{
    public Trip $trip;

    public bool $showForm = false;

    public string $name = '';

    public string $email = '';

    public string $location = '';

    public string $quote = '';

    public int $rating = 5;

    public function setRating(int $rating): void
    {
        $this->rating = max(1, min(5, $rating));
    }

    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string', 'min:20'],
        ]);

        Testimonial::query()->create([
            'trip_id' => $this->trip->id,
            'name' => $validated['name'],
            'location' => $validated['location'] !== '' ? $validated['location'] : 'Traveller',
            'quote' => $validated['quote'],
            'rating' => $validated['rating'],
            'is_approved' => false,
            'avatar' => null,
        ]);

        $this->reset(['name', 'email', 'location', 'quote']);
        $this->rating = 5;
        $this->showForm = false;

        session()->flash('review_success', 'Thank you! Your review will appear after approval.');
    }
}; ?>

<section class="rounded-3xl border border-[#1a3a2a]/10 bg-white p-8 font-sans">
    @if (session('review_success'))
        <div class="mb-6 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-900">{{ session('review_success') }}</div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-3xl font-bold text-[#1a3a2a]">Share Your Experience</h2>
            <p class="mt-2 text-sm text-[#1a3a2a]/70">Submit your review. It will appear after admin approval.</p>
        </div>
        <button
            type="button"
            class="tn-btn-secondary w-full shrink-0 sm:w-auto"
            wire:click="toggleForm"
            aria-expanded="{{ $showForm ? 'true' : 'false' }}"
        >
            {{ $showForm ? 'Hide form' : 'Write a Review' }}
        </button>
    </div>

    <div
        x-data="{ open: @entangle('showForm') }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mt-6"
        style="display: none;"
    >
        <form wire:submit="submit" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Name</label>
                    <input type="text" wire:model.live="name" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Email</label>
                    <input type="email" wire:model.live="email" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Location</label>
                    <input type="text" wire:model.live="location" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Rating</label>
                    <div class="flex gap-1 pt-1" role="group" aria-label="Star rating">
                        @for ($s = 1; $s <= 5; $s++)
                            <button
                                type="button"
                                class="rounded text-2xl transition hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[#c8860a] focus:ring-offset-2"
                                wire:click="setRating({{ $s }})"
                                aria-label="{{ $s }} stars"
                            >
                                <span class="{{ $rating >= $s ? 'text-[#c8860a]' : 'text-[#1a3a2a]/20' }}">★</span>
                            </button>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Review</label>
                <textarea wire:model.live="quote" rows="4" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm"></textarea>
                @error('quote')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="tn-btn-primary w-full px-6 py-3 transition hover:brightness-110" wire:loading.attr="disabled">
                <span wire:loading.remove>Submit Review</span>
                <span wire:loading>Submitting...</span>
            </button>
        </form>
    </div>
</section>
