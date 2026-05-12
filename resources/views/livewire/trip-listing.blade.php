<?php

use App\Models\Category;
use App\Models\Trip;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    protected string $tripListingMetaTitle = 'All Trips | TravelNepal';

    #[Url(except: '')]
    public string $category = '';

    #[Url(except: '')]
    public string $difficulty = '';

    #[Url(except: '')]
    public string $duration = '';

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedDifficulty(): void
    {
        $this->resetPage();
    }

    public function updatedDuration(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['category', 'difficulty', 'duration']);
        $this->resetPage();
    }

    public function with(): array
    {
        $durationRange = match ($this->duration) {
            'under-7' => [1, 6],
            '7-14' => [7, 14],
            '14-plus' => [15, 365],
            default => null,
        };

        $trips = Trip::query()
            ->forListing()
            ->when($this->category !== '', fn ($query) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $this->category)))
            ->when($this->difficulty !== '', fn ($query) => $query->where('difficulty', $this->difficulty))
            ->when($durationRange, fn ($query) => $query->whereBetween('duration_days', $durationRange))
            ->paginate(12);

        $categories = Category::query()
            ->withCount(['trips' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $activeFilterLabel = collect([
            $this->category !== '' ? $categories->firstWhere('slug', $this->category)?->name : null,
            $this->difficulty !== '' ? ucfirst($this->difficulty) : null,
            $this->duration !== '' ? str_replace('-', ' ', $this->duration) : null,
        ])->filter()->implode(' | ');

        $this->tripListingMetaTitle = $activeFilterLabel !== ''
            ? "Trips: {$activeFilterLabel} | TravelNepal"
            : 'All Trips | TravelNepal';

        return [
            'trips' => $trips,
            'categories' => $categories,
            'category' => $this->category,
            'difficulty' => $this->difficulty,
            'duration' => $this->duration,
        ];
    }

    public function render(): mixed
    {
        return parent::render()->layout('layouts.app', [
            'metaTitle' => $this->tripListingMetaTitle,
            'metaDescription' => 'Browse active Nepal adventure trips by category, difficulty, and duration.',
            'metaImage' => null,
        ]);
    }
}; ?>

@php
    $durationLabels = [
        'under-7' => 'Under 7 days',
        '7-14' => '7–14 days',
        '14-plus' => '14+ days',
    ];
    $activeCategoryName = $category !== '' ? $categories->firstWhere('slug', $category)?->name : null;
    $hasActiveFilters = $category !== '' || $difficulty !== '' || $duration !== '';
@endphp

<div class="min-h-screen bg-adventure-beige pb-16">
    <section class="relative h-[380px] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2000&auto=format&fit=crop" alt="All Nepal trips" class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/70"></div>
        <div class="relative z-10 mx-auto flex h-full w-full max-w-7xl flex-col justify-end px-4 pb-12 md:px-6">
            <div class="mb-4 flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-white/70">
                <a href="{{ route('home') }}">Home</a>
                <span>›</span>
                <span class="text-[#c8860a]">All Trips</span>
            </div>
            <h1 class="break-words font-display text-3xl font-bold text-white sm:text-5xl md:text-7xl">All Trips</h1>
        </div>
    </section>

    <div class="sticky top-[72px] z-40 border-b border-[#1a3a2a]/10 bg-adventure-beige/95 shadow-sm backdrop-blur-md">
        <section class="mx-auto w-full max-w-7xl px-4 py-4 md:px-6" wire:key="trip-filters">
            <div class="rounded-2xl border border-[#1a3a2a]/10 bg-white p-4 md:p-6">
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="$set('category', '')" class="min-h-11 rounded-full px-4 py-2 text-sm transition hover:brightness-110 {{ $category === '' ? 'bg-[#1a3a2a] text-white' : 'bg-adventure-beige text-[#1a3a2a]' }}">All</button>
                    @foreach ($categories as $item)
                        <button type="button" wire:click="$set('category', '{{ $item->slug }}')" class="min-h-11 max-w-full break-words rounded-full px-4 py-2 text-left text-sm transition hover:brightness-110 {{ $category === $item->slug ? 'bg-[#1a3a2a] text-white' : 'bg-adventure-beige text-[#1a3a2a]' }}">{{ $item->name }}</button>
                    @endforeach
                </div>
                <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                    <select wire:model.live="difficulty" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 font-sans text-sm">
                        <option value="">All difficulty</option>
                        <option value="easy">Easy</option>
                        <option value="moderate">Moderate</option>
                        <option value="challenging">Challenging</option>
                        <option value="extreme">Extreme</option>
                    </select>
                    <select wire:model.live="duration" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 font-sans text-sm">
                        <option value="">All durations</option>
                        <option value="under-7">Under 7 days</option>
                        <option value="7-14">7–14 days</option>
                        <option value="14-plus">14+ days</option>
                    </select>
                    <button type="button" wire:click="resetFilters" class="min-h-11 w-full rounded-xl border border-[#c8860a] px-4 py-3 font-sans text-sm font-semibold text-[#c8860a] transition hover:brightness-110 md:w-auto">Reset filters</button>
                </div>
            </div>
        </section>
    </div>

    <section class="mx-auto mt-8 w-full max-w-7xl px-4 md:px-6">
        @if ($hasActiveFilters)
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="font-sans text-sm font-medium text-[#1a3a2a]/70">Active filters:</span>
                @if ($activeCategoryName)
                    <button type="button" wire:click="$set('category', '')" class="inline-flex min-h-9 max-w-full items-center gap-1 break-words rounded-full bg-[#1a3a2a]/10 px-3 py-2 font-sans text-sm text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        <span>{{ $activeCategoryName }}</span>
                        <span class="text-[#c8860a]" aria-hidden="true">×</span>
                    </button>
                @endif
                @if ($difficulty !== '')
                    <button type="button" wire:click="$set('difficulty', '')" class="inline-flex min-h-9 max-w-full items-center gap-1 break-words rounded-full bg-[#1a3a2a]/10 px-3 py-2 font-sans text-sm text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        <span>{{ ucfirst($difficulty) }}</span>
                        <span class="text-[#c8860a]" aria-hidden="true">×</span>
                    </button>
                @endif
                @if ($duration !== '')
                    <button type="button" wire:click="$set('duration', '')" class="inline-flex min-h-9 max-w-full items-center gap-1 break-words rounded-full bg-[#1a3a2a]/10 px-3 py-2 font-sans text-sm text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        <span>{{ $durationLabels[$duration] ?? $duration }}</span>
                        <span class="text-[#c8860a]" aria-hidden="true">×</span>
                    </button>
                @endif
                <button type="button" wire:click="resetFilters" class="font-sans text-sm font-semibold text-[#c8860a] underline decoration-[#c8860a]/50 underline-offset-2 transition hover:brightness-110">Reset all</button>
            </div>
        @endif

        @if ($trips->count() === 0)
            <div class="rounded-2xl border border-[#1a3a2a]/10 bg-white p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-adventure-beige text-[#c8860a]">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <h2 class="mt-6 font-display text-3xl font-bold text-[#1a3a2a]">No trips found</h2>
                <p class="mt-3 font-sans text-[#1a3a2a]/70">Try adjusting your filters</p>
                <button type="button" wire:click="resetFilters" class="mx-auto mt-8 bg-[#c8860a] px-6 py-3 text-sm font-semibold uppercase tracking-wider text-white transition hover:brightness-110 sm:inline-flex sm:w-auto">Reset Filters</button>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($trips as $trip)
                    @php
                        $imageUrl = Illuminate\Support\Str::startsWith($trip->cover_image, ['http://', 'https://'])
                            ? $trip->cover_image
                            : Storage::url($trip->cover_image);
                        $difficultyColor = match ($trip->difficulty) {
                            'easy' => 'bg-green-100 text-green-800',
                            'moderate' => 'bg-yellow-100 text-yellow-800',
                            'challenging' => 'bg-orange-100 text-orange-800',
                            'extreme' => 'bg-red-100 text-red-800',
                            default => 'bg-red-100 text-red-800',
                        };
                    @endphp
                    <article class="overflow-hidden rounded-3xl border border-[#1a3a2a]/10 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="relative">
                            <img src="{{ $imageUrl }}" alt="{{ $trip->title }}" class="h-48 w-full object-cover" />
                            @if ($trip->category)
                                <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 font-sans text-xs font-semibold uppercase tracking-wider text-[#1a3a2a] shadow">{{ $trip->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-2xl font-bold">{{ $trip->title }}</h3>
                            <div class="mt-3 flex items-center gap-3 font-sans text-sm text-[#1a3a2a]/70">
                                <span>{{ $trip->duration_days }} days</span>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $difficultyColor }}">{{ ucfirst($trip->difficulty) }}</span>
                            </div>
                            <p class="mt-2 font-sans text-sm text-[#1a3a2a]/70">Best season: {{ $trip->best_season }}</p>
                            <p class="mt-4 font-sans text-lg font-bold text-[#c8860a]">From ${{ number_format((float) $trip->starting_price, 2) }}</p>
                            <div class="mt-5 flex w-full flex-col gap-3 sm:flex-row">
                                <a href="{{ route('trips.show', $trip->slug) }}" class="tn-btn-secondary w-full justify-center sm:w-auto">View Trip</a>
                                <button type="button" x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }} })" class="tn-btn-primary w-full justify-center sm:w-auto">Book Now</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="tn-pagination mt-10 flex justify-center">
                {{ $trips->links() }}
            </div>
        @endif
    </section>
</div>
