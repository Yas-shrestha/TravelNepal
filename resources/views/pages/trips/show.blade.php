@extends('layouts.app')

@section('content')


<div x-data="{ pastHero: false }" x-init="window.addEventListener('scroll', () => { pastHero = window.scrollY > 300 }, { passive: true })">
    <section class="relative min-h-[320px] h-[420px] overflow-hidden sm:min-h-0">
        <img src="{{ $trip->image_url }}" alt="{{ $trip->title }}" class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 to-black/75"></div>
        <div class="relative z-10 mx-auto flex h-full w-full max-w-7xl flex-col justify-end px-4 pb-10 md:px-6">
            <div class="mb-4 flex flex-wrap items-center gap-2 break-words text-sm text-white/80">
                <a href="{{ route('home') }}">Home</a><span>›</span><a href="{{ route('trips.index') }}">Trips</a><span>›</span><span>{{ $trip->category?->name }}</span><span>›</span><span class="text-adventure-ochre">{{ $trip->title }}</span>
            </div>
            <h1 class="break-words font-display text-2xl font-bold leading-tight text-white sm:text-4xl md:text-6xl">{{ $trip->title }}</h1>
            <div class="mt-5 flex flex-wrap gap-3">
                <span class="rounded-full border border-white/40 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">{{ $trip->duration_days }} Days</span>
                <span class="rounded-full border border-white/40 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">{{ ucfirst($trip->difficulty) }}</span>
                <span class="rounded-full border border-white/40 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">{{ $trip->max_altitude_m ? 'Max '.$trip->max_altitude_m.'m' : ($trip->route_distance_km ? $trip->route_distance_km.' km' : 'All Terrain') }}</span>
                <span class="rounded-full border border-white/40 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">Group: {{ $trip->min_group_size }}-{{ $trip->max_group_size }}</span>
                <span class="rounded-full border border-white/40 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">{{ $trip->best_season }}</span>
            </div>
        </div>
    </section>

    <section class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-10 px-4 py-12 md:px-6 lg:grid-cols-3">
        <aside class="order-1 lg:order-2 lg:col-span-1">
            <div class="lg:sticky lg:top-24">
                <div class="rounded-3xl border-t-4 border-[#1a3a2a] bg-white p-6 shadow-lg">
                    <p class="font-sans text-xs uppercase tracking-widest text-adventure-green/45">Starting from</p>
                    <p class="mt-1 font-display text-4xl font-bold text-[#c8860a]">${{ number_format((float) $trip->starting_price, 2) }}</p>
                    <div class="mt-6 space-y-4 font-sans text-sm">
                        <p><strong>Duration:</strong> {{ $trip->duration_days }} Days</p>
                        <p><strong>Difficulty:</strong> <span class="rounded-full px-2 py-1 text-xs {{ $trip->difficulty_badge }}">{{ ucfirst($trip->difficulty) }}</span></p>
                        @if ($trip->max_altitude_m)
                        <p><strong>Max Altitude:</strong> {{ $trip->max_altitude_m }}m</p>
                        @endif
                        @if ($trip->route_distance_km)
                        <p><strong>Route Distance:</strong> {{ $trip->route_distance_km }} km</p>
                        @endif
                        <p><strong>Group Size:</strong> {{ $trip->min_group_size }} - {{ $trip->max_group_size }}</p>
                        <p><strong>Best Season:</strong> {{ $trip->best_season }}</p>
                    </div>
                    <button type="button" x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }} })" class="tn-btn-primary mt-6 w-full px-6 py-3 transition hover:brightness-110">Book Now</button>
                </div>
            </div>
        </aside>

        <div class="order-2 space-y-12 lg:order-1 lg:col-span-2">
            <section class="bg-white lg:bg-transparent">
                <div class="tn-section-head">
                    <h2 class="tn-section-title font-display">Trip Overview</h2>
                    <p class="tn-section-subtitle">Highlights, pacing, and what makes this route special.</p>
                </div>
                <p class="mt-6 font-sans leading-relaxed text-adventure-green/85 lg:mt-8">{{ $trip->overview }}</p>
                <div class="mt-6 grid gap-3 md:grid-cols-2">
                    @foreach (($trip->highlights ?? []) as $highlight)
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-adventure-green font-sans text-xs text-white">✓</span>
                        <span class="font-sans text-sm text-adventure-green/90">{{ $highlight }}</span>
                    </div>
                    @endforeach
                </div>
            </section>

            <section>
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">Day-by-Day Itinerary</h2>
                    <p class="tn-section-subtitle">Tap a day for accommodations, meals, and terrain notes.</p>
                </div>
                <div class="overflow-hidden rounded-2xl border border-adventure-green/10 bg-white font-sans" x-data="{ openIdx: 0 }">
                    @foreach ($trip->itineraryDays as $index => $day)
                    <div class="border-b border-adventure-green/10 transition-colors last:border-b-0" :class="openIdx === {{ $index }} ? 'border-l-4 border-l-adventure-ochre bg-adventure-ochre/5' : 'border-l-4 border-l-transparent'">
                        <button type="button" class="flex min-h-12 w-full items-center justify-between gap-4 px-5 py-4 text-left md:min-h-0 md:p-5" @click="openIdx = openIdx === {{ $index }} ? -1 : {{ $index }}">
                            <span class="font-display text-lg font-semibold text-adventure-green md:text-xl">Day {{ $day->day_number }} — {{ $day->title }}</span>
                            <svg class="h-5 w-5 shrink-0 text-adventure-ochre transition-transform duration-300" :class="openIdx === {{ $index }} ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            x-show="openIdx === {{ $index }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="border-t border-adventure-green/5"
                            style="display: none;">
                            <div class="space-y-4 px-5 pb-5 pt-2">
                                <p class="text-sm leading-relaxed text-adventure-green/80">{{ $day->description }}</p>
                                @if ($day->accommodation)
                                <p class="flex items-start gap-2 text-sm text-adventure-green/85">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-adventure-ochre" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Zm0 3.75h.008v.008H17.25v-.008Zm0 3.75h.008v.008H17.25v-.008Z" />
                                    </svg>
                                    <span><strong class="text-adventure-green">Stay:</strong> {{ $day->accommodation }}</span>
                                </p>
                                @endif
                                @if ($day->meals_included)
                                <p class="flex items-start gap-2 text-sm text-adventure-green/85">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-adventure-ochre" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513m-3 4.875v-1.5a3.375 3.375 0 0 0-3.375-3.375h-1.5a3.375 3.375 0 0 0-3.375 3.375v1.5m12-6.75v1.5m0 0v1.5m0-1.5h-12m12 0h-1.5m-12 0H9m12 0v-1.5m0 1.5H9m-3.75 0H9" />
                                    </svg>
                                    <span><strong class="text-adventure-green">Meals:</strong> {{ $day->meals_included }}</span>
                                </p>
                                @endif
                                @if ($day->distance_km || $day->elevation_gain_m)
                                <div class="flex flex-wrap gap-4 text-xs text-adventure-green/70">
                                    @if ($day->distance_km)
                                    <span><strong class="text-adventure-green">Distance:</strong> {{ $day->distance_km }} km</span>
                                    @endif
                                    @if ($day->elevation_gain_m)
                                    <span><strong class="text-adventure-green">Elevation gain:</strong> {{ $day->elevation_gain_m }} m</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            <section>
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">Major Attractions</h2>
                    <p class="tn-section-subtitle">Landmarks and viewpoints along your route.</p>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($trip->attractions as $attraction)
                    @php $img = Illuminate\Support\Str::startsWith($attraction->image_path, ['http://', 'https://']) ? $attraction->image_path : Storage::url($attraction->image_path); @endphp
                    <article class="overflow-hidden rounded-2xl border border-adventure-green/10 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-md">
                        <img src="{{ $img }}" alt="{{ $attraction->name }}" class="h-48 w-full object-cover" />
                        <div class="p-4">
                            <h3 class="font-display text-xl font-semibold">{{ $attraction->name }}</h3>
                            <p class="mt-2 font-sans text-sm text-adventure-green/75">{{ $attraction->description }}</p>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>

            <section>
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">Choose Your Package</h2>
                    <p class="tn-section-subtitle">Compare inclusions — swipe horizontally on small screens.</p>
                </div>
                <p class="mb-3 flex items-center justify-center gap-2 text-center font-sans text-xs font-semibold uppercase tracking-wider text-adventure-ochre md:hidden" aria-hidden="false">
                    <span aria-hidden="true">←</span>
                    <span>Swipe to compare packages</span>
                    <span aria-hidden="true">→</span>
                </p>
                <div class="overflow-x-auto pb-3 [-webkit-overflow-scrolling:touch]">
                    <div class="grid min-w-[800px] grid-cols-3 gap-4">
                        @foreach ($trip->packages as $package)
                        @php $isPremium = $package->tier === 'premium'; @endphp
                        <article class="relative rounded-2xl p-6 transition duration-300 hover:-translate-y-1 {{ $isPremium ? 'border-2 border-[#c8860a] bg-white shadow-lg' : 'border-2 border-[#1a3a2a]/10 bg-white' }}">
                            @if ($isPremium)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-[#c8860a] px-3 py-1 font-sans text-xs font-semibold uppercase tracking-wider text-white">Most Popular</span>
                            @endif
                            <h3 class="font-display text-2xl font-bold capitalize">{{ $package->tier }}</h3>
                            <p class="mt-2 font-display text-2xl font-bold text-[#c8860a]">${{ number_format((float) $package->price_usd, 2) }}</p>
                            <div class="mt-4 space-y-2 font-sans text-sm">
                                @foreach ($package->inclusions as $inclusion)
                                <p class="flex gap-2 text-adventure-green/80">
                                    @if ($inclusion->type === 'exclusion')
                                    <span class="font-bold text-red-600" aria-hidden="true">✗</span>
                                    @else
                                    <span class="font-bold text-green-600" aria-hidden="true">✓</span>
                                    @endif
                                    <span>{{ $inclusion->description }}</span>
                                </p>
                                @endforeach
                            </div>
                            <button type="button" x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }}, packageId: {{ $package->id }} })" class="tn-btn-primary mt-6 w-full px-6 py-3 transition hover:brightness-110">Book Now</button>
                        </article>
                        @endforeach
                    </div>
                </div>
            </section>

            @if (count($galleryUrls) > 0)
            <section
                x-data="{
                            open: false,
                            i: 0,
                            urls: @json($galleryUrls),
                            captions: @json($galleryCaptions),
                            openAt(idx) { this.i = idx; this.open = true; document.body.classList.add('overflow-hidden'); },
                            close() { this.open = false; document.body.classList.remove('overflow-hidden'); },
                            next() { this.i = (this.i + 1) % this.urls.length },
                            prev() { this.i = (this.i - 1 + this.urls.length) % this.urls.length }
                        }"
                @keydown.escape.window="close()"
                @keydown.arrow-right.window="open && next()"
                @keydown.arrow-left.window="open && prev()">
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">Trip Gallery</h2>
                    <p class="tn-section-subtitle">Open any photo for a full-screen look.</p>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryUrls as $idx => $img)
                    <button type="button" class="group relative block overflow-hidden rounded-xl focus:outline-none focus:ring-2 focus:ring-adventure-ochre" @click="openAt({{ $idx }})">
                        <img src="{{ $img }}" alt="{{ $galleryCaptions[$idx] ?? $trip->title }}" class="h-44 w-full object-cover transition duration-500 group-hover:scale-105 md:h-48" />
                    </button>
                    @endforeach
                </div>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[90] flex items-center justify-center bg-black/90 p-4" style="display: none;" @click.self="close()">
                    <button type="button" class="absolute right-4 top-4 text-2xl text-white/80 hover:text-white" @click="close()" aria-label="Close">✕</button>
                    <button type="button" class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white hover:bg-white/20 md:left-6" @click.stop="prev()" aria-label="Previous">‹</button>
                    <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white hover:bg-white/20 md:right-6" @click.stop="next()" aria-label="Next">›</button>
                    <div class="max-h-[85vh] max-w-5xl px-8" @click.stop>
                        <img :src="urls[i]" :alt="(captions[i] || '')" class="max-h-[80vh] w-full object-contain" />
                    </div>
                </div>
            </section>
            @endif

            <section>
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">What Travellers Say</h2>
                    <p class="tn-section-subtitle">Reviews from guests who travelled this itinerary.</p>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($trip->testimonials as $testimonial)
                    <article class="rounded-2xl border border-adventure-green/10 bg-white p-5 transition duration-300 hover:-translate-y-1">
                        <p class="text-adventure-ochre">{{ str_repeat('★', (int) $testimonial->rating) }}</p>
                        <p class="mt-2 font-sans text-sm text-adventure-green/80">"{{ $testimonial->quote }}"</p>
                        <p class="mt-4 font-sans text-sm font-semibold">{{ $testimonial->name }} <span class="text-adventure-green/60">· {{ $testimonial->location }}</span></p>
                    </article>
                    @endforeach
                </div>
            </section>

            <livewire:submit-trip-review :trip="$trip" />

            @if ($trip->faqs->isNotEmpty())
            <section x-data="{ openFaq: null }">
                <div class="tn-section-head mb-6 md:mb-8">
                    <h2 class="tn-section-title font-display">Frequently Asked Questions</h2>
                    <p class="tn-section-subtitle">Trip-specific answers before you book.</p>
                </div>
                <div class="space-y-3 font-sans">
                    @foreach ($trip->faqs as $fi => $faq)
                    <div class="overflow-hidden rounded-xl border border-adventure-green/10 bg-white transition-shadow hover:shadow-sm">
                        <button type="button" class="flex min-h-12 w-full items-center justify-between gap-3 px-4 py-4 text-left md:min-h-0 md:p-4" @click="openFaq = openFaq === {{ $fi }} ? null : {{ $fi }}">
                            <span class="font-semibold text-adventure-green">{{ $faq->question }}</span>
                            <svg class="h-5 w-5 shrink-0 text-adventure-ochre transition-transform duration-300" :class="openFaq === {{ $fi }} ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            x-show="openFaq === {{ $fi }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="border-t border-adventure-green/5"
                            style="display: none;">
                            <p class="px-4 pb-4 pt-2 text-sm leading-relaxed text-adventure-green/80">{{ $faq->answer }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </section>

    <button
        type="button"
        x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }} })"
        class="lg:hidden z-40 truncate rounded-full bg-[#c8860a] px-5 py-3 text-center font-sans text-xs font-semibold uppercase tracking-wide text-white shadow-lg transition hover:brightness-110 sm:text-sm"
        style="position: fixed !important; bottom: 24px !important; left: 20px !important; top: auto !important;">
        Book Now — from ${{ number_format((float) $trip->starting_price, 2) }}
    </button>
</div>
@endsection