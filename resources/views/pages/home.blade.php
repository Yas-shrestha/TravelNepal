@extends('layouts.app')

@section('content')

<section class="relative flex min-h-[100svh] w-full items-center justify-center overflow-hidden pb-24 pt-0">
    <img src="{{ $heroImageUrl }}" alt="Nepal adventure landscape" class="absolute inset-0 h-full w-full object-cover" />
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-[rgba(0,0,0,0.75)]"></div>
    <div class="relative z-10 mx-auto max-w-5xl px-4 pt-[calc(72px+2rem)] text-center md:px-6 md:pt-[calc(72px+3rem)]">
        <h1 class="font-display text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl md:text-6xl lg:text-7xl">
            {{ $settings->get('site_tagline', "Nepal's home for adventure — on two wheels or two feet.") }}
        </h1>
        <p class="mx-auto mt-6 max-w-2xl break-words font-sans text-base text-white/90 sm:text-lg">Motorbike tours, treks, cycles and cultural adventures across Nepal</p>
        <div class="mx-auto mt-10 flex w-full max-w-xl flex-col justify-center gap-4 sm:max-w-none sm:flex-row sm:gap-6">
            <a href="{{ route('trips.index') }}" class="tn-btn-secondary text-white border border-white hover:bg-white hover:text-[#1a3a2a] w-full justify-center sm:w-auto">Explore Trips</a>
            <button type="button" x-on:click="Livewire.dispatch('open-booking-popup')" class="tn-btn-primary justify-center px-8 py-3 transition hover:brightness-110">Plan My Trip</button>
        </div>
    </div>
    <button type="button" onclick="document.getElementById('categories')?.scrollIntoView({ behavior: 'smooth' })" class="absolute bottom-8 left-1/2 z-10 -translate-x-1/2 text-white/80 transition hover:text-adventure-ochre" aria-label="Scroll down">
        <svg class="h-10 w-10 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
</section>

{{-- Categories --}}
<section id="categories" class="bg-white py-12 md:py-16">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="tn-section-head">
            <h2 class="tn-section-title font-display">Explore by Category</h2>
            <p class="tn-section-subtitle">Pick a style of adventure — from high trails to open highways.</p>
        </div>
        <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2 [-ms-overflow-style:none] [scrollbar-width:none] md:grid md:snap-none md:grid-cols-2 md:gap-6 md:overflow-visible md:pb-0 lg:grid-cols-3 xl:grid-cols-5 [&::-webkit-scrollbar]:hidden">
            @foreach ($categories as $category)
            @php
            $iconComponent = $category->icon && str_starts_with((string) $category->icon, 'heroicon-')
            ? $category->icon
            : 'heroicon-o-map';
            @endphp
            <a href="{{ route('trips.index', ['category' => $category->slug]) }}" class="flex w-[min(20rem,calc(100vw-3rem))] shrink-0 snap-center flex-col rounded-2xl border border-[#1a3a2a]/10 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md md:w-auto md:shrink md:snap-none">
                <div class="flex items-start justify-between gap-3">
                    <x-dynamic-component :component="$iconComponent" class="h-8 w-8 shrink-0 text-[#c8860a]" />
                    <span class="shrink-0 rounded-full bg-[#1a3a2a]/10 px-2 py-0.5 text-xs font-semibold text-[#1a3a2a]">{{ $category->trips_count }} trips</span>
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold text-[#1a3a2a]">{{ $category->name }}</h3>
                @if ($category->description)
                <p class="mt-2 font-sans text-sm leading-relaxed text-[#1a3a2a]/70">{{ \Illuminate\Support\Str::limit($category->description, 120) }}</p>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured trips --}}
<section class="bg-[#f5f0e8] py-12 md:py-16">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="tn-section-head">
            <h2 class="tn-section-title font-display">Popular Trips</h2>
            <p class="tn-section-subtitle">Hand-picked departures travellers love — limited group sizes.</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredTrips as $trip)
            <article class="overflow-hidden rounded-3xl border border-[#1a3a2a]/10 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                {{-- Image --}}
                <div class="relative overflow-hidden">
                    <img
                        src="{{ $trip->image_url }}"
                        alt="{{ $trip->title }}"
                        class="h-48 w-full object-cover transition duration-500 hover:scale-105" />

                    @if ($trip->category)
                    <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 font-sans text-xs font-semibold uppercase tracking-wider text-[#1a3a2a] shadow">
                        {{ $trip->category->name }}
                    </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <h3 class="font-display text-2xl font-bold text-[#1a3a2a]">
                        {{ $trip->title }}
                    </h3>

                    <div class="mt-3 flex flex-wrap items-center gap-3 font-sans text-sm text-[#1a3a2a]/70">
                        <span>
                            {{ $trip->duration_days }} days
                        </span>

                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $trip->difficulty_badge }}">
                            {{ $trip->difficulty_label }}
                        </span>
                    </div>

                    {{-- Price --}}
                    <p class="mt-4 font-sans text-lg font-bold text-[#c8860a]">
                        @if ($trip->formatted_price)
                        From ${{ $trip->formatted_price }}
                        @else
                        Contact Us
                        @endif
                    </p>

                    {{-- Buttons --}}
                    <div class="mt-5 flex w-full flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('trips.show', $trip->slug) }}"
                            class="tn-btn-secondary w-full justify-center sm:w-auto">
                            View Trip
                        </a>

                        <button
                            type="button"
                            x-on:click="Livewire.dispatch('open-booking-popup', { tripId: {{ $trip->id }} })"
                            class="tn-btn-primary w-full justify-center px-6 py-3 transition hover:brightness-110 sm:w-auto">
                            Book Now
                        </button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Why choose us --}}
<section class="bg-[#1a3a2a] py-12 text-white md:py-16">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="tn-section-head mb-8 md:mb-10 [&_.tn-section-title]:text-white [&_.tn-section-subtitle]:text-white/70">
            <h2 class="tn-section-title font-display text-white md:text-4xl">Why Choose Us</h2>
            <p class="tn-section-subtitle">Safety-first crews, small groups, and support when it counts.</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($whyChooseUsItems as $item)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 transition duration-300 hover:-translate-y-1">
                @if ($item['icon'] === 'shield')
                <svg class="h-10 w-10 text-[#c8860a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                @elseif ($item['icon'] === 'users')
                <svg class="h-10 w-10 text-[#c8860a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.813-2.513M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                @elseif ($item['icon'] === 'wrench')
                <svg class="h-10 w-10 text-[#c8860a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655-5.653a2.548 2.548 0 0 0-3.586 0L2.5 8.5" />
                </svg>
                @else
                <svg class="h-10 w-10 text-[#c8860a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                @endif
                <h3 class="mt-4 font-display text-xl font-semibold">{{ $item['title'] }}</h3>
                <p class="mt-3 font-sans text-sm text-white/80">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@if ($testimonialCount > 0)
<section
    class="bg-white py-12 md:py-20"
    x-data="{
        current: 0,
        total: {{ $testimonialCount }},
        perPage: 1,
        autoplay: null,
        gap: 24,
        cardWidth: 320,
        init() {
            this.updatePerPage();
            this.$nextTick(() => {
                this.measure();
                this.startAutoplay();
            });
            window.addEventListener('resize', () => {
                this.updatePerPage();
                this.current = Math.min(this.current, this.maxIdx());
                this.$nextTick(() => {
                    this.measure();
                    this.startAutoplay();
                });
            });
        },
        updatePerPage() {
            const w = window.innerWidth;
            this.perPage = w >= 1024 ? 2 : (w >= 768 ? 2 : 1);
        },
        maxIdx() {
            return Math.max(0, this.total - this.perPage);
        },
        measure() {
            const vp = this.$refs.viewport;
            if (!vp) return;
            const usable = vp.clientWidth;
            this.cardWidth = this.perPage > 0
                ? (usable - this.gap * (this.perPage - 1)) / this.perPage
                : usable;
        },
        slidePx() {
            return this.cardWidth + this.gap;
        },
        tx() {
            return -(this.current * this.slidePx());
        },
        dotCount() {
            return this.maxIdx() + 1;
        },
        dotIndexes() {
            return Array.from({ length: this.maxIdx() + 1 }, (_, i) => i);
        },
        startAutoplay() {
            clearInterval(this.autoplay);
            if (this.maxIdx() === 0) return;
            this.autoplay = setInterval(() => this.next(), 5000);
        },
        pauseAutoplay() {
            clearInterval(this.autoplay);
        },
        next() {
            const m = this.maxIdx();
            if (m === 0) return;
            this.current = this.current >= m ? 0 : this.current + 1;
        },
        prev() {
            const m = this.maxIdx();
            if (m === 0) return;
            this.current = this.current <= 0 ? m : this.current - 1;
        },
        go(i) {
            this.current = i;
        },
    }"
    @mouseenter="pauseAutoplay()"
    @mouseleave="startAutoplay()">

    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="tn-section-head">
            <h2 class="tn-section-title font-display">What Our Travellers Say</h2>
            <p class="tn-section-subtitle">Independent voices from recent journeys across Nepal.</p>
        </div>

        <div class="relative mx-auto flex max-w-6xl items-center gap-2 md:gap-4">
            {{-- Prev --}}
            <button
                type="button"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-transparent text-2xl text-[#c8860a] transition hover:bg-[#c8860a]/10 disabled:pointer-events-none disabled:opacity-30 md:h-12 md:w-12"
                aria-label="Previous testimonial"
                @click="prev()"
                :disabled="maxIdx() === 0">
                &#8249;
            </button>

            {{-- Track --}}
            <div class="min-w-0 flex-1 overflow-hidden" x-ref="viewport">
                <div
                    class="flex gap-6"
                    :style="{ transform: 'translateX(' + tx() + 'px)', transition: 'transform 0.4s ease' }"
                    x-ref="track">
                    @foreach ($testimonials as $testimonial)
                    @php
                    $avatarUrl = $testimonial->avatar
                    ? (Illuminate\Support\Str::startsWith($testimonial->avatar, ['http://', 'https://'])
                    ? $testimonial->avatar
                    : Storage::url($testimonial->avatar))
                    : 'https://ui-avatars.com/api/?name='.urlencode($testimonial->name).'&background=1a3a2a&color=c8860a&size=80&bold=true';
                    @endphp
                    <article
                        class="flex shrink-0 flex-col rounded-xl bg-white p-8 shadow-md"
                        :style="{ width: cardWidth + 'px', minWidth: cardWidth + 'px' }">
                        <div class="flex flex-row items-start gap-4">
                            <img
                                src="{{ $avatarUrl }}"
                                alt="{{ $testimonial->name }}"
                                class="h-16 w-16 shrink-0 rounded-full object-cover"
                                width="64" height="64" />
                            <div class="min-w-0 flex-1 text-left">
                                <h3 class="break-words font-display text-lg font-semibold text-adventure-green">
                                    {{ $testimonial->name }}
                                </h3>
                                <p class="break-words font-sans text-sm text-gray-500">
                                    {{ $testimonial->location }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-4 flex gap-0.5" aria-label="Rating {{ $testimonial->rating }} out of 5">
                            @for ($s = 1; $s <= 5; $s++)
                                <span class="{{ $s <= $testimonial->rating ? 'text-[#c8860a]' : 'text-gray-300' }}">★</span>
                                @endfor
                        </p>
                        <p class="mt-4 flex-1 break-words font-sans text-base italic leading-relaxed text-gray-600">
                            "{{ $testimonial->quote }}"
                        </p>
                        <div class="mt-auto pt-6">
                            @if ($testimonial->trip)

                            <a href="{{ route('trips.show', $testimonial->trip->slug) }}"
                                class="break-words font-sans text-sm font-medium text-adventure-ochre transition hover:brightness-110">
                                {{ $testimonial->trip->title }}
                            </a>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>

            {{-- Next --}}
            <button
                type="button"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-transparent text-2xl text-[#c8860a] transition hover:bg-[#c8860a]/10 disabled:pointer-events-none disabled:opacity-30 md:h-12 md:w-12"
                aria-label="Next testimonial"
                @click="next()"
                :disabled="maxIdx() === 0">
                &#8250;
            </button>
        </div>

        {{-- Dots --}}
        <div class="mt-8 flex flex-wrap justify-center gap-2" x-show="dotCount() > 1" x-cloak>
            <template x-for="dotIndex in dotIndexes()" :key="dotIndex">
                <button
                    type="button"
                    class="h-2.5 w-2.5 rounded-full transition"
                    :class="current === dotIndex ? 'bg-[#c8860a]' : 'bg-[#c8860a]/25'"
                    @click="go(dotIndex)"
                    :aria-current="current === dotIndex"
                    :aria-label="'Go to testimonial slide ' + (dotIndex + 1)">
                </button>
            </template>
        </div>
    </div>
</section>
@endif

{{-- CTA banner --}}
<section class="relative min-h-[280px] overflow-hidden py-16 md:py-24">
    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1600" alt="" class="absolute inset-0 h-full min-h-[280px] w-full object-cover" />
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/80"></div>
    <div class="relative z-10 mx-auto flex max-w-3xl flex-col items-center px-4 text-center md:px-6">
        <h2 class="break-words font-display text-3xl font-bold text-white sm:text-4xl md:text-5xl">Ready to Start Your Adventure?</h2>
        <p class="mt-4 max-w-xl break-words font-sans text-base text-white/90 sm:text-lg">From Himalayan treks to Royal Enfield expeditions</p>
        <a href="{{ route('trips.index') }}" class="tn-btn-primary mt-8 w-full max-w-md justify-center px-8 py-3 transition hover:brightness-110 sm:inline-flex sm:w-auto">Browse All Trips</a>
    </div>
</section>
@endsection