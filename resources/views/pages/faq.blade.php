@extends('layouts.app')

@section('content')
    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto w-full max-w-4xl px-4 md:px-6">
            <div class="tn-section-head">
                <h1 class="tn-section-title font-display">Frequently Asked Questions</h1>
                <p class="tn-section-subtitle">Everything you need to know before your Nepal adventure.</p>
            </div>
            <div class="mt-10 space-y-3 font-sans" x-data="{ openIdx: 0 }">
                @foreach ($faqs as $idx => $faq)
                    <div class="overflow-hidden rounded-xl border border-adventure-green/10 bg-adventure-beige transition-shadow hover:shadow-sm">
                        <button type="button" class="flex min-h-12 w-full items-center justify-between gap-3 px-5 py-4 text-left md:min-h-0 md:p-5" @click="openIdx = openIdx === {{ $idx }} ? -1 : {{ $idx }}">
                            <span class="break-words pr-2 font-semibold text-adventure-green">{{ $faq->question }}</span>
                            <svg class="h-5 w-5 shrink-0 text-adventure-ochre transition-transform duration-300" :class="openIdx === {{ $idx }} ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            x-show="openIdx === {{ $idx }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="border-t border-adventure-green/10"
                            style="display: none;"
                        >
                            <p class="px-5 pb-5 pt-2 text-sm leading-relaxed text-adventure-green/80">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="bg-adventure-green py-12 text-center md:py-14">
        <h2 class="break-words font-display text-3xl font-bold text-white md:text-4xl">Still have questions?</h2>
        <p class="mt-4 px-2 font-sans text-white/85">We are happy to help plan your adventure.</p>
        <a href="{{ route('contact') }}" class="tn-btn-primary mt-8 w-full max-w-xs justify-center px-8 py-3 transition hover:brightness-110 sm:inline-flex sm:w-auto">Contact Us</a>
    </section>
@endsection
