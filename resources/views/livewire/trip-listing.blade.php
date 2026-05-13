<?php

use App\Models\Category;
use App\Models\Trip;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

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
            '7-14'    => [7, 14],
            '14-plus' => [15, 365],
            default   => null,
        };

        $trips = Trip::query()
            ->forListing()                          // eager loads category + packages (no N+1)
            ->when(
                $this->category !== '',
                fn($q) => $q->whereHas(
                    'category',
                    fn($cq) => $cq->where('slug', $this->category)
                )
            )
            ->when(
                $this->difficulty !== '',
                fn($q) => $q->where('difficulty', $this->difficulty)
            )
            ->when(
                $durationRange,
                fn($q) => $q->whereBetween('duration_days', $durationRange)
            )
            ->paginate(12);

        // withCount runs a single COUNT subquery per category — no N+1
        $categories = Category::query()
            ->withCount(['trips' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return [
            'trips'      => $trips,
            'categories' => $categories,
        ];
    }
}; ?>

@php
$durationLabels = [
'under-7' => 'Under 7 days',
'7-14' => '7-14 days',
'14-plus' => '14+ days',
];
$activeCategoryName = $category !== ''
? $categories->firstWhere('slug', $category)?->name
: null;
$hasActiveFilters = $category !== '' || $difficulty !== '' || $duration !== '';
@endphp

<div class="min-h-screen bg-adventure-beige pb-16">

    {{-- ░░ HERO ░░ --}}
    <section class="relative h-[420px] overflow-hidden">
        <img
            src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2000&auto=format&fit=crop"
            alt="All Nepal trips"
            class="absolute inset-0 h-full w-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/40 to-black/75"></div>
        <div class="relative z-10 mx-auto flex h-full w-full max-w-7xl flex-col justify-end px-4 pb-14 md:px-6">
            <div class="mb-4 flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-white/70">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span>›</span>
                <span class="text-[#c8860a]">All Trips</span>
            </div>
            <h1 class="font-display text-5xl font-bold text-white sm:text-6xl md:text-7xl">All Trips</h1>
            <p class="mt-3 text-base text-white/70">Explore handpicked adventures across Nepal</p>
        </div>
    </section>

    {{-- ░░ MAIN LAYOUT ░░ --}}
    <div class="mx-auto mt-8 w-full max-w-7xl px-4 md:px-6">
        <div class="flex items-start gap-8">

            {{-- ░░ LEFT SIDEBAR — sticky via inline style (Tailwind purge-safe) ░░ --}}
            <aside
                class="hidden w-64 shrink-0 lg:block"
                style="position: sticky; top: 80px; align-self: flex-start;">

                <div class="rounded-2xl border border-[#1a3a2a]/10 bg-white p-5 shadow-sm">

                    <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-[#1a3a2a]/40">
                        Categories
                    </p>

                    <ul class="flex flex-col gap-0.5">
                        <li>
                            <button
                                type="button"
                                wire:click="$set('category', '')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition
                                    {{ $category === '' ? 'bg-[#1a3a2a] text-white' : 'text-[#1a3a2a] hover:bg-[#1a3a2a]/10' }}">
                                <span>All</span>
                                <span class="text-xs opacity-60">{{ $categories->sum('trips_count') }}</span>
                            </button>
                        </li>
                        @foreach ($categories as $cat)
                        <li>
                            <button
                                type="button"
                                wire:click="$set('category', '{{ $cat->slug }}')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition
                                    {{ $category === $cat->slug ? 'bg-[#1a3a2a] text-white' : 'text-[#1a3a2a] hover:bg-[#1a3a2a]/10' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs opacity-60">{{ $cat->trips_count }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <hr class="my-4 border-[#1a3a2a]/10" />

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-widest text-[#1a3a2a]/40">
                        Difficulty
                    </label>
                    <select
                        wire:model.live="difficulty"
                        class="mb-4 w-full rounded-lg border border-[#1a3a2a]/20 bg-white px-3 py-2.5 text-sm text-[#1a3a2a] focus:outline-none focus:ring-2 focus:ring-[#1a3a2a]/30">
                        <option value="">All difficulties</option>
                        <option value="easy">Easy</option>
                        <option value="moderate">Moderate</option>
                        <option value="challenging">Challenging</option>
                        <option value="extreme">Extreme</option>
                    </select>

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-widest text-[#1a3a2a]/40">
                        Duration
                    </label>
                    <select
                        wire:model.live="duration"
                        class="mb-4 w-full rounded-lg border border-[#1a3a2a]/20 bg-white px-3 py-2.5 text-sm text-[#1a3a2a] focus:outline-none focus:ring-2 focus:ring-[#1a3a2a]/30">
                        <option value="">All durations</option>
                        <option value="under-7">Under 7 days</option>
                        <option value="7-14">7–14 days</option>
                        <option value="14-plus">14+ days</option>
                    </select>

                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="w-full rounded-xl border border-[#c8860a] py-2.5 text-sm font-semibold text-[#c8860a] transition hover:bg-[#c8860a] hover:text-white">
                        Reset Filters
                    </button>
                </div>
            </aside>

            {{-- ░░ MAIN CONTENT ░░ --}}
            <main class="min-w-0 flex-1">

                {{-- Mobile filters (hidden on lg+) --}}
                <div class="mb-4 flex flex-wrap gap-2 lg:hidden">
                    <select wire:model.live="category"
                        class="rounded-lg border border-[#1a3a2a]/20 bg-white px-3 py-2 text-sm text-[#1a3a2a] focus:outline-none">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="difficulty"
                        class="rounded-lg border border-[#1a3a2a]/20 bg-white px-3 py-2 text-sm text-[#1a3a2a] focus:outline-none">
                        <option value="">All difficulties</option>
                        <option value="easy">Easy</option>
                        <option value="moderate">Moderate</option>
                        <option value="challenging">Challenging</option>
                        <option value="extreme">Extreme</option>
                    </select>
                    <select wire:model.live="duration"
                        class="rounded-lg border border-[#1a3a2a]/20 bg-white px-3 py-2 text-sm text-[#1a3a2a] focus:outline-none">
                        <option value="">All durations</option>
                        <option value="under-7">Under 7 days</option>
                        <option value="7-14">7–14 days</option>
                        <option value="14-plus">14+ days</option>
                    </select>
                    <button type="button" wire:click="resetFilters"
                        class="rounded-lg border border-[#c8860a] px-3 py-2 text-sm font-semibold text-[#c8860a] transition hover:bg-[#c8860a] hover:text-white">
                        Reset
                    </button>
                </div>

                {{-- Active filter chips --}}
                @if ($hasActiveFilters)
                <div class="mb-5 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-medium text-[#1a3a2a]/50">Active:</span>

                    @if ($activeCategoryName)
                    <button type="button" wire:click="$set('category', '')"
                        class="inline-flex items-center gap-1 rounded-full bg-[#1a3a2a]/10 px-3 py-1.5 text-xs text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        {{ $activeCategoryName }} <span class="text-[#c8860a]">×</span>
                    </button>
                    @endif

                    @if ($difficulty !== '')
                    <button type="button" wire:click="$set('difficulty', '')"
                        class="inline-flex items-center gap-1 rounded-full bg-[#1a3a2a]/10 px-3 py-1.5 text-xs text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        {{ ucfirst($difficulty) }} <span class="text-[#c8860a]">×</span>
                    </button>
                    @endif

                    @if ($duration !== '')
                    <button type="button" wire:click="$set('duration', '')"
                        class="inline-flex items-center gap-1 rounded-full bg-[#1a3a2a]/10 px-3 py-1.5 text-xs text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20">
                        {{ $durationLabels[$duration] ?? $duration }} <span class="text-[#c8860a]">×</span>
                    </button>
                    @endif

                    <button type="button" wire:click="resetFilters"
                        class="text-xs font-semibold text-[#c8860a] underline underline-offset-2">
                        Reset all
                    </button>
                </div>
                @endif

                {{-- Result count --}}
                @if ($trips->total() > 0)
                <p class="mb-5 text-sm text-[#1a3a2a]/50">
                    Showing {{ $trips->firstItem() }}–{{ $trips->lastItem() }} of {{ $trips->total() }} trips
                </p>
                @endif

                {{-- Empty state --}}
                @if ($trips->count() === 0)
                <div class="rounded-2xl border border-[#1a3a2a]/10 bg-white p-12 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-adventure-beige text-[#c8860a]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 font-display text-3xl font-bold text-[#1a3a2a]">No trips found</h2>
                    <p class="mt-3 text-[#1a3a2a]/70">Try adjusting your filters to find your next adventure.</p>
                    <button type="button" wire:click="resetFilters"
                        class="mx-auto mt-8 inline-flex bg-[#c8860a] px-6 py-3 text-sm font-semibold uppercase tracking-wider text-white transition hover:brightness-110">
                        Reset Filters
                    </button>
                </div>

                {{-- Trip grid --}}
                @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($trips as $trip)

                    <article class="group overflow-hidden rounded-3xl border border-[#1a3a2a]/10 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                        <div class="relative overflow-hidden">
                            <img
                                src="{{ $trip->image_url }}"
                                alt="{{ $trip->title }}"
                                class="h-48 w-full object-cover transition duration-500 group-hover:scale-105" />

                            @if ($trip->category)
                            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#1a3a2a] shadow">
                                {{ $trip->category->name }}
                            </span>
                            @endif

                            @if ($trip->is_featured)
                            <span class="absolute right-3 top-3 rounded-full bg-[#c8860a] px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white shadow">
                                Featured
                            </span>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="font-display text-lg font-bold leading-snug text-[#1a3a2a]">
                                {{ $trip->title }}
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="flex items-center gap-1 text-sm text-[#1a3a2a]/70">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $trip->duration_days }} days
                                </span>
                                {{-- difficulty_badge accessor returns the full Tailwind class string --}}
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $trip->difficulty_badge }}">
                                    {{ $trip->difficulty_label }}
                                </span>
                            </div>

                            <p class="mt-1.5 text-xs text-[#1a3a2a]/50">
                                Best season: {{ $trip->best_season }}
                            </p>

                            <p class="mt-3 text-lg font-bold text-[#c8860a]">
                                @if ($trip->formatted_price)
                                From ${{ $trip->formatted_price }}
                                @else
                                <span class="text-sm font-medium text-[#1a3a2a]/40">Price on request</span>
                                @endif
                            </p>

                            <div class="mt-4 flex w-full gap-2">
                                <a href="{{ route('trips.show', $trip->slug) }}"
                                    class="tn-btn-secondary flex-1 justify-center">
                                    View Trip
                                </a>
                                <button
                                    type="button"
                                    x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }} })"
                                    class="tn-btn-primary flex-1 justify-center">
                                    Book Now
                                </button>
                            </div>
                        </div>

                    </article>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $trips->links() }}
                </div>
                @endif

            </main>
        </div>
    </div>
</div>