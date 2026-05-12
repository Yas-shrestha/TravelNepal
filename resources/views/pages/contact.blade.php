@extends('layouts.app')

@php
    $siteSettings = App\Models\Setting::query()->pluck('value', 'key');
@endphp

@section('content')
    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
            <div class="tn-section-head">
                <h1 class="tn-section-title font-display">Get In Touch</h1>
                <p class="tn-section-subtitle">Have questions? We'd love to hear from you.</p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="rounded-3xl border border-adventure-green/10 bg-adventure-beige p-8">
                    <h2 class="font-display text-3xl font-bold text-adventure-green">Contact Info</h2>
                    <ul class="mt-6 space-y-5 font-sans text-sm text-adventure-green/85">
                        <li class="flex gap-3">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-adventure-green/10 text-adventure-ochre" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-adventure-green/55">Address</p>
                                <p class="mt-1">{{ $siteSettings->get('address') }}</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-adventure-green/10 text-adventure-ochre" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-adventure-green/55">Phone</p>
                                <p class="mt-1">
                                    @php $phoneRaw = (string) ($siteSettings->get('phone') ?? ''); @endphp
                                    @if ($phoneRaw !== '')
                                        <a href="tel:{{ preg_replace('/\s+/', '', $phoneRaw) }}" class="hover:text-adventure-ochre">{{ $phoneRaw }}</a>
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-adventure-green/10 text-adventure-ochre" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-adventure-green/55">Email</p>
                                <p class="mt-1"><a href="mailto:{{ $siteSettings->get('email') }}" class="hover:text-adventure-ochre">{{ $siteSettings->get('email') }}</a></p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-adventure-green/10 text-adventure-ochre" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-adventure-green/55">Office hours</p>
                                <p class="mt-1">{{ $siteSettings->get('office_hours') }}</p>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-8 overflow-hidden rounded-xl border border-adventure-green/10">
                        <iframe
                            src="https://maps.google.com/maps?q=Thamel%20Kathmandu&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            class="aspect-video min-h-56 w-full max-w-full border-0 sm:min-h-64"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="rounded-3xl border border-adventure-green/10 bg-white p-8">
                    <livewire:contact-form />
                </div>
            </div>
        </div>
    </section>
@endsection
