@extends('layouts.app')

@section('content')
<section class="relative min-h-[280px] h-[360px] overflow-hidden">
    <img src="https://images.unsplash.com/photo-1578922746465-3a80a228f223?q=80&w=1974&auto=format&fit=crop" alt="TravelNepal Team" class="absolute inset-0 h-full w-full object-cover" />
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative z-10 mx-auto flex h-full w-full max-w-7xl items-end px-4 pb-10 md:px-6">
        <div>
            <h1 class="break-words font-display text-3xl font-bold text-white sm:text-5xl">About TravelNepal</h1>
            <p class="mt-3 font-sans text-xl text-white/85">Nepal's Home for Adventure</p>
        </div>
    </div>
</section>

<div class="mx-auto w-full max-w-7xl space-y-12 px-4 py-12 md:space-y-16 md:px-6 md:py-16">
    <section class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
        <div class="text-center lg:text-left">
            <div class="tn-section-head lg:mx-0 lg:text-left lg:[&_.tn-section-title]:text-left lg:[&_.tn-section-subtitle]:text-left">
                <h2 class="tn-section-title font-display">Our Story</h2>
                <p class="tn-section-subtitle">How TravelNepal came to life — and what guides every departure.</p>
            </div>
            <div class="mt-6 space-y-4 font-sans leading-relaxed text-adventure-green/85 lg:mt-4">
                <p>TravelNepal started with one vision: helping travellers experience Nepal with authenticity, safety, and local expertise. From remote mountain passes to ancient valley cultures, we curate journeys that balance challenge with comfort.</p>
                <p>Our team combines experienced mountain guides, route planners, and rider support staff. Every itinerary is built around Nepal's terrain, weather windows, and local rhythm so you can focus on the experience, not logistics.</p>
                <p>Today, we lead trekking, motorbike, cycling, and cultural programs across the country with deep respect for local communities and the Himalayan environment.</p>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-adventure-green/10 shadow-lg">
            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=1200&auto=format&fit=crop" alt="Himalayan landscape" class="h-72 w-full object-cover transition duration-500 hover:scale-105 md:h-full md:min-h-[280px]" />
        </div>
    </section>
</div>

<section class="bg-adventure-green py-12 text-white md:py-16">
    <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
        <div class="tn-section-head mb-8 md:mb-10 [&_.tn-section-title]:text-white [&_.tn-section-subtitle]:text-white/70">
            <h2 class="tn-section-title font-display text-white md:text-4xl">Our Values</h2>
            <p class="tn-section-subtitle">Principles we refuse to compromise on — every season.</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            @foreach ([
            ['title' => 'Sustainability', 'text' => 'Responsible routes and local partnerships that protect mountain ecosystems.'],
            ['title' => 'Safety First', 'text' => 'Proven operating standards, qualified guides, and emergency readiness.'],
            ['title' => 'Authentic Experiences', 'text' => 'Real local encounters beyond generic tourist itineraries.'],
            ['title' => 'Community', 'text' => 'We work with local hosts, drivers, porters, and small businesses.'],
            ] as $value)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 transition duration-300 hover:-translate-y-1">
                <svg class="h-10 w-10 text-adventure-ochre" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mt-4 font-display text-xl font-semibold">{{ $value['title'] }}</h3>
                <p class="mt-3 font-sans text-sm text-white/80">{{ $value['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<div class="mx-auto w-full max-w-7xl space-y-12 bg-adventure-beige px-4 py-12 md:space-y-16 md:px-6 md:py-16">
    <section>
        <div class="tn-section-head">
            <h2 class="tn-section-title font-display">Meet the Team</h2>
            <p class="tn-section-subtitle">Operators and storytellers behind your Himalayan itineraries.</p>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($persons as $person)
            <article class="rounded-2xl border border-adventure-green/10 bg-white p-5 transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-adventure-green/10 font-display text-2xl font-bold text-adventure-green">
                    {{ collect(explode(' ', $person['name']))->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold">{{ $person['name'] }}</h3>
                <p class="mt-1 font-sans text-sm font-medium text-adventure-ochre">{{ $person['role'] }}</p>
                <p class="mt-3 font-sans text-sm text-adventure-green/75">{{ $person['bio'] }}</p>
            </article>
            @endforeach
        </div>
    </section>
    <!-- 
        <section>
            <div class="tn-section-head">
                <h2 class="tn-section-title font-display">Trust &amp; Accreditation</h2>
                <p class="tn-section-subtitle">Licenses and memberships that back accountable adventures.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4 md:justify-start">
                @foreach (['Government Licensed', 'TAAN Member', 'Sustainable Travel'] as $badge)
                    <div class="min-w-[10rem] flex-1 rounded-xl border border-adventure-green/10 bg-white px-4 py-6 text-center font-sans text-sm font-semibold text-adventure-green transition hover:-translate-y-1 sm:min-w-[12rem] sm:flex-none md:flex-1 lg:flex-none">{{ $badge }}</div>
                @endforeach
            </div>
        </section> -->
</div>

<section class="relative min-h-[260px] overflow-hidden py-16 md:py-20">
    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1600" alt="" class="absolute inset-0 h-full min-h-[260px] w-full object-cover" />
    <div class="absolute inset-0 bg-adventure-green/80"></div>
    <div class="relative z-10 mx-auto max-w-3xl px-4 text-center md:px-6">
        <h2 class="font-display text-4xl font-bold text-white md:text-5xl">Ready to Explore Nepal?</h2>
        <a href="{{ route('trips.index') }}" class="tn-btn-primary mt-8 w-full max-w-xs justify-center px-8 py-3 transition hover:brightness-110 sm:inline-flex sm:w-auto">Browse All Trips</a>
    </div>
</section>
@endsection