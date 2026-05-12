<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Str;

        $defaultOgImage = 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1200&q=80';
        $defaultMeta = match (true) {
            request()->routeIs('about') => ['title' => 'About Us | TravelNepal', 'description' => "Learn about TravelNepal, Nepal's home for adventure experiences."],
            request()->routeIs('contact') => ['title' => 'Contact | TravelNepal', 'description' => 'Contact TravelNepal for enquiries, custom plans, and trip support.'],
            request()->routeIs('faq') => ['title' => 'Frequently Asked Questions | TravelNepal', 'description' => 'Answers to common questions about TravelNepal trips, bookings, permits, and logistics.'],
            request()->routeIs('trips.index') => ['title' => 'All Trips | TravelNepal', 'description' => 'Browse active Nepal adventure trips by category, difficulty, and duration.'],
            default => ['title' => 'TravelNepal', 'description' => 'TravelNepal adventures across Nepal.'],
        };
        $metaTitle = $metaTitle ?? $defaultMeta['title'];
        $metaDescription = $metaDescription ?? $defaultMeta['description'];
        $metaImagePath = $metaImage ?? null;
        $resolvedImage = $metaImagePath
            ? (Str::startsWith($metaImagePath, ['http://', 'https://']) ? $metaImagePath : Storage::url($metaImagePath))
            : null;
        $metaImageUrl = $resolvedImage ? url($resolvedImage) : $defaultOgImage;
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:image" content="{{ $metaImageUrl }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-adventure-beige font-sans text-adventure-green">
    @php
        $siteSettings = App\Models\Setting::query()->pluck('value', 'key');
        $menuCategories = App\Models\Category::query()->whereHas('trips', fn ($q) => $q->where('is_active', true))->orderBy('name')->get();
        $socials = [
            'Facebook' => ['url' => $siteSettings->get('facebook_url'), 'icon' => 'F'],
            'Instagram' => ['url' => $siteSettings->get('instagram_url'), 'icon' => 'I'],
            'TikTok' => ['url' => $siteSettings->get('tiktok_url'), 'icon' => 'T'],
            'YouTube' => ['url' => $siteSettings->get('youtube_url'), 'icon' => 'Y'],
        ];
        $navLinks = [
            ['name' => 'Home', 'route' => 'home'],
            ['name' => 'About Us', 'route' => 'about'],
            ['name' => 'All Trips', 'route' => 'trips.index'],
            ['name' => 'FAQ', 'route' => 'faq'],
            ['name' => 'Contact', 'route' => 'contact'],
        ];
    @endphp

    <header
        x-data="{ open: false, categoriesOpen: false, scrolled: false, isHomepage: {{ request()->routeIs('home') ? 'true' : 'false' }} }"
        @scroll.window="scrolled = window.scrollY > 80"
        class="fixed left-0 right-0 top-0 z-50 border-b border-transparent transition-all duration-[400ms] ease-in-out"
        :class="isHomepage && ! scrolled ? 'bg-white/[0.08] backdrop-blur-[16px] [-webkit-backdrop-filter:blur(16px)]' : 'bg-[#1a3a2a]'"
    >
        <nav class="mx-auto flex h-[72px] w-full max-w-7xl items-center justify-between px-4 md:px-6">
            <a href="{{ route('home') }}" class="font-display text-2xl font-bold tracking-tight text-white">TravelNepal</a>

            <div class="hidden items-center gap-7 lg:flex">
                @foreach ($navLinks as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="text-sm font-medium tracking-wide transition {{ request()->routeIs($link['route']) ? 'text-[#c8860a]' : 'text-white hover:text-[#c8860a]' }}"
                    >{{ $link['name'] }}</a>
                @endforeach
                <div class="relative z-50" @keydown.escape.window="categoriesOpen = false">
                    <button type="button" @click="categoriesOpen = ! categoriesOpen" class="flex items-center gap-1 text-sm font-medium tracking-wide text-white transition hover:text-[#c8860a]">
                        Categories
                        <svg class="h-4 w-4 transition-transform duration-300" :class="categoriesOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div
                        x-show="categoriesOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        @click.outside="categoriesOpen = false"
                        class="absolute left-0 z-50 mt-3 w-56 rounded-xl bg-white p-2 shadow-xl ring-1 ring-black/5"
                        style="display: none;"
                    >
                        @foreach ($menuCategories as $category)
                            <a href="{{ route('trips.index', ['category' => $category->slug]) }}" @click="categoriesOpen = false" class="block rounded-lg px-3 py-2 font-sans text-sm text-[#1a3a2a] transition hover:bg-adventure-beige">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="min-h-11 rounded-sm bg-[#c8860a] px-5 py-2 text-sm font-semibold uppercase tracking-wider text-white transition hover:brightness-110" x-on:click="Livewire.dispatch('open-booking-popup')">Plan My Trip</button>
            </div>

            <button class="flex h-11 w-11 items-center justify-center text-white lg:hidden" @click="open = true" type="button" aria-label="Open menu">
                <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </nav>

        <div x-show="open" x-transition class="fixed inset-0 z-[60] bg-black/50 lg:hidden" @click="open = false" style="display: none;"></div>
        <aside
            x-show="open"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 z-[70] flex h-full w-[min(100vw-3rem,20rem)] flex-col bg-[#1a3a2a] px-6 py-8 shadow-2xl lg:hidden"
            style="display: none;"
        >
            <div class="mb-8 flex items-center justify-between">
                <span class="font-display text-xl font-bold text-white">TravelNepal</span>
                <button class="flex h-10 w-10 items-center justify-center rounded-full text-white hover:bg-white/10" type="button" @click="open = false" aria-label="Close menu">✕</button>
            </div>
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto pb-6">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}" @click="open = false" class="rounded-lg px-3 py-3 text-base font-medium {{ request()->routeIs($link['route']) ? 'text-[#c8860a]' : 'text-white hover:bg-white/10' }}">{{ $link['name'] }}</a>
                @endforeach
                <div class="mt-4 rounded-xl border border-white/15 p-4">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-white/60">Categories</p>
                    <div class="flex flex-col gap-2">
                        @foreach ($menuCategories as $category)
                            <a href="{{ route('trips.index', ['category' => $category->slug]) }}" @click="open = false" class="text-sm text-white/90 hover:text-[#c8860a]">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="tn-btn-primary mt-auto w-full px-5 py-3 transition hover:brightness-110" x-on:click="Livewire.dispatch('open-booking-popup'); open = false">Plan My Trip</button>
            </nav>
        </aside>
    </header>

    <main @class([
        'pt-0' => request()->routeIs('home'),
        'pt-[72px]' => ! request()->routeIs('home'),
    ])>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="bg-adventure-green pb-10 pt-16 text-white">
        <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-10 px-4 text-center md:grid-cols-2 md:px-6 md:text-left lg:grid-cols-4">
            <div class="flex flex-col items-center md:items-start">
                <h3 class="font-display text-2xl font-bold">{{ $siteSettings->get('site_name', 'TravelNepal') }}</h3>
                <p class="mt-4 text-sm text-white/75">{{ $siteSettings->get('site_tagline') }}</p>
                <div class="mt-5 flex justify-center gap-3 md:justify-start">
                    @foreach ($socials as $social => $item)
                        @if ($item['url'])
                            <a href="{{ $item['url'] }}" target="_blank" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-sm hover:bg-[#c8860a]">{{ $item['icon'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-display text-lg font-semibold">Quick Links</h4>
                <div class="mt-4 space-y-2 text-sm text-white/80">
                    <a class="block hover:text-[#c8860a]" href="{{ route('home') }}">Home</a>
                    <a class="block hover:text-[#c8860a]" href="{{ route('trips.index') }}">All Trips</a>
                    <a class="block hover:text-[#c8860a]" href="{{ route('about') }}">About Us</a>
                    <a class="block hover:text-[#c8860a]" href="{{ route('faq') }}">FAQ</a>
                    <a class="block hover:text-[#c8860a]" href="{{ route('contact') }}">Contact</a>
                </div>
            </div>
            <div>
                <h4 class="font-display text-lg font-semibold">Categories</h4>
                <div class="mt-4 space-y-2 text-sm text-white/80">
                    @foreach ($menuCategories->take(5) as $category)
                        <a class="block hover:text-[#c8860a]" href="{{ route('trips.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-display text-lg font-semibold">Contact</h4>
                <div class="mt-4 space-y-2 text-sm text-white/80">
                    <p>{{ $siteSettings->get('address') }}</p>
                    <p>{{ $siteSettings->get('phone') }}</p>
                    <p>{{ $siteSettings->get('email') }}</p>
                    <p>{{ $siteSettings->get('office_hours') }}</p>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-10 flex w-full max-w-7xl flex-col gap-3 border-t border-white/10 px-4 pt-6 text-xs text-white/65 sm:flex-row sm:items-center sm:justify-between md:px-6">
            <p class="text-center sm:text-left">© {{ now()->year }} {{ $siteSettings->get('site_name', 'TravelNepal') }}. All rights reserved.</p>
        </div>
    </footer>

    <button
        x-data="{ show: false }"
        x-init="window.addEventListener('scroll', () => show = window.scrollY > 300)"
        x-show="show"
        x-transition
        type="button"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-28 right-4 z-30 flex h-12 w-12 items-center justify-center rounded-full bg-[#c8860a] text-white shadow-lg transition hover:brightness-110 md:bottom-6 md:right-6 md:z-40"
        aria-label="Back to top"
        style="display: none;"
    >
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
    </button>

    <livewire:booking-popup />
    @livewireScripts
</body>

</html>
