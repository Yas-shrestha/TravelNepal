<?php

use App\Models\BikeRental;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Trip;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;
use App\Jobs\ProcessBooking;

new class extends Component
{
    use WithFileUploads;

    public bool $open = false;

    // Basic Info
    public ?int $trip_id = null;
    public ?int $package_id = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $preferred_date = '';
    public int $group_size = 1;
    public string $message = '';

    // License Info
    public $has_license = null;
    public string $license_number = '';
    public string $license_country = '';
    public string $license_type = 'international';
    public $license_image;

    // Bike Info
    public $has_own_bike = null;
    public string $own_bike_model = '';
    public ?int $rental_bike_id = null;

    protected $listeners = ['open-booking-popup' => 'openPopup'];

    public function openPopup(?int $tripId = null, ?int $packageId = null): void
    {
        $this->open = true;
        $this->trip_id = $tripId;
        $this->package_id = $packageId;
        if ($tripId && !$packageId) {
            $this->updatedTripId();
        }
    }

    public function closePopup(): void
    {
        $this->open = false;
        session()->forget('booking_success');
    }

    public function updatedTripId(): void
    {
        $this->package_id = Package::query()
            ->where('trip_id', $this->trip_id)
            ->where('is_active', true)
            ->orderByRaw("CASE tier WHEN 'standard' THEN 1 WHEN 'premium' THEN 2 WHEN 'luxury' THEN 3 ELSE 4 END")
            ->value('id');
    }

    public function submit(): void
    {
        $trip = $this->trip_id ? Trip::find($this->trip_id) : null;
        $hasLicense = $this->toNullableBool($this->has_license);
        $hasOwnBike = $this->toNullableBool($this->has_own_bike);

        $rules = [
            'trip_id'        => ['required', 'exists:trips,id'],
            'package_id'     => ['required', 'exists:packages,id'],
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:50'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today' . now()->addWeek()->toDateString()],
            'group_size'     => ['required', 'integer', 'min:1', 'max:50'],
            'message'        => ['nullable', 'string', 'max:2000'],
        ];

        if ($trip?->requires_bike) {
            $rules['has_license'] = ['required'];
            if ($hasLicense === true) {
                $rules['license_number']  = ['required', 'string'];
                $rules['license_country'] = ['required', 'string'];
                $rules['license_image']   = ['required', 'image', 'max:4096'];
            }
            $rules['has_own_bike'] = ['required'];
            if ($hasOwnBike === true) {
                $rules['own_bike_model'] = ['required', 'string'];
            } elseif ($hasOwnBike === false) {
                $rules['rental_bike_id'] = ['required', 'exists:bike_rentals,id'];
            }
        }

        $this->validate($rules);

        if ($trip?->requires_bike && $hasLicense === false) {
            $this->addError('has_license', 'A valid bike license is required to book this trip.');
            return;
        }

        $path = $this->license_image ? $this->license_image->store('licenses', 'public') : null;

        $rentalCost = null;
        if ($hasOwnBike === false && $this->rental_bike_id && $trip) {
            $bike = BikeRental::find($this->rental_bike_id);
            if ($bike) $rentalCost = round((float)$bike->price_per_day_usd * (int)$trip->duration_days, 2);
        }

        ProcessBooking::dispatch([
            'trip_id'          => $this->trip_id,
            'package_id'       => $this->package_id,
            'rental_bike_id'   => ($hasOwnBike === false) ? $this->rental_bike_id : null,
            'name'             => $this->name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'preferred_date'   => $this->preferred_date,
            'group_size'       => $this->group_size,
            'message'          => $this->message,
            'has_own_bike'     => $hasOwnBike,
            'own_bike_model'   => $this->own_bike_model,
            'rental_cost_usd'  => $rentalCost,
            'has_license'      => $hasLicense,
            'license_number'   => $this->license_number,
            'license_country'  => $this->license_country,
            'license_type'     => $this->license_type,
            'license_image'    => $path,
            'status'           => 'pending',
        ]);

        $this->reset([
            'name',
            'email',
            'phone',
            'preferred_date',
            'group_size',
            'message',
            'license_image',
            'has_license',
            'has_own_bike',
            'own_bike_model',
            'rental_bike_id'
        ]);
        session()->flash('booking_success', "Request received! We'll contact you shortly.");
    }

    public function getSelectedTripProperty()
    {
        return $this->trip_id ? Trip::find($this->trip_id) : null;
    }
    public function getAvailableTripsProperty()
    {
        return Trip::active()->orderBy('title')->get(['id', 'title']);
    }
    public function getAvailablePackagesProperty()
    {
        return $this->trip_id
            ? Package::where('trip_id', $this->trip_id)->active()->get()
            : collect();
    }
    public function getAvailableBikeRentalsProperty()
    {
        return $this->trip_id
            ? BikeRental::where('trip_id', $this->trip_id)->where('is_available', true)->get()
            : collect();
    }

    private function toNullableBool($value)
    {
        if ($value === null || $value === '') return null;
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}; ?>

@php
$isNoLicense = ($has_license === "0" || $has_license === 0);
$licenseBlocked = (bool) ($this->selectedTrip?->requires_bike && $isNoLicense);

// Shared input classes
$inputClass = 'w-full rounded-xl border bg-white px-4 py-3 text-sm text-[#1a3a2a] transition focus:outline-none focus:ring-2 focus:ring-[#1a3a2a]/30';
$inputBase = $inputClass . ' border-[#1a3a2a]/20';
$inputError = $inputClass . ' border-red-400 bg-red-50 focus:ring-red-300';
@endphp

<div
    x-data="{ open: @entangle('open') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[100] flex items-stretch justify-center p-0 md:items-center md:p-4"
    style="display: none;">
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/70 backdrop-blur-sm"
        @click="$wire.closePopup()"></div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative z-10 flex max-h-full w-full max-w-none flex-col overflow-y-auto bg-adventure-beige shadow-2xl md:max-h-[92vh] md:max-w-2xl md:rounded-3xl"
        @click.stop>
        {{-- Header --}}
        <div class="sticky top-0 z-20 flex items-center justify-between border-b border-[#1a3a2a]/10 bg-adventure-beige/95 px-6 py-4 backdrop-blur-md md:px-8">
            <div>
                <h2 class="font-display text-2xl font-bold text-[#1a3a2a]">Plan My Trip</h2>
                <p class="mt-0.5 text-xs text-[#1a3a2a]/60">Fill in the details and we'll be in touch shortly.</p>
            </div>
            <button
                type="button"
                @click="$wire.closePopup()"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1a3a2a]/10 text-[#1a3a2a] transition hover:bg-[#1a3a2a]/20"
                aria-label="Close">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-6 md:px-8">

            {{-- ░░ SUCCESS STATE ░░ --}}
            @if (session('booking_success'))
            <div class="flex flex-col items-center py-12 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mt-6 font-display text-2xl font-bold text-[#1a3a2a]">Booking Received!</h3>
                <p class="mt-3 text-[#1a3a2a]/70">{{ session('booking_success') }}</p>
                <button type="button" wire:click="closePopup"
                    class="tn-btn-primary mt-8 px-10 py-3">
                    Done
                </button>
            </div>

            {{-- ░░ FORM ░░ --}}
            @else
            {{-- Global validation summary --}}
            @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="mb-2 flex items-center gap-2 text-sm font-semibold text-red-700">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                    Please fix the following errors:
                </p>
                <ul class="space-y-1 pl-6 text-xs text-red-600 list-disc">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- License block warning --}}
            @if ($licenseBlocked)
            <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-center">
                <p class="text-sm font-bold text-red-700">⚠️ A valid bike license is required for this trip. You cannot proceed without one.</p>
            </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-6 font-sans">
                <fieldset class="space-y-6 border-0 p-0" @if($licenseBlocked) disabled @endif>

                    {{-- ── SECTION: Trip & Package ── --}}
                    <div>
                        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#1a3a2a]/50">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1a3a2a] text-white text-[10px]">1</span>
                            Trip & Package
                        </h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Trip *</label>
                                <select wire:model.live="trip_id"
                                    class="{{ $errors->has('trip_id') ? $inputError : $inputBase }}">
                                    <option value="">Select a trip</option>
                                    @foreach ($this->availableTrips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->title }}</option>
                                    @endforeach
                                </select>
                                @error('trip_id')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Package *</label>
                                <select wire:model="package_id"
                                    class="{{ $errors->has('package_id') ? $inputError : $inputBase }}">
                                    <option value="">Select package</option>
                                    @foreach ($this->availablePackages as $package)
                                    <option value="{{ $package->id }}">{{ ucfirst($package->tier) }} — ${{ number_format($package->price_usd, 0) }}</option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION: Contact Info ── --}}
                    <div>
                        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#1a3a2a]/50">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1a3a2a] text-white text-[10px]">2</span>
                            Contact Information
                        </h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Full Name *</label>
                                <input type="text" wire:model="name" placeholder="John Doe"
                                    class="{{ $errors->has('name') ? $inputError : $inputBase }}" />
                                @error('name')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Email *</label>
                                <input type="email" wire:model="email" placeholder="you@example.com"
                                    class="{{ $errors->has('email') ? $inputError : $inputBase }}" />
                                @error('email')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Phone *</label>
                                <input type="text" wire:model="phone" placeholder="+977 98XXXXXXXX"
                                    class="{{ $errors->has('phone') ? $inputError : $inputBase }}" />
                                @error('phone')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION: Dates & Group ── --}}
                    <div>
                        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#1a3a2a]/50">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1a3a2a] text-white text-[10px]">3</span>
                            Schedule
                        </h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Preferred Date *</label>
                                <input type="date" wire:model="preferred_date"
                                    class="{{ $errors->has('preferred_date') ? $inputError : $inputBase }}" />
                                @error('preferred_date')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Group Size *</label>
                                <input type="number" wire:model="group_size" min="1" max="50"
                                    class="{{ $errors->has('group_size') ? $inputError : $inputBase }}" />
                                @error('group_size')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION: Bike & License (conditional) ── --}}
                    @if ($this->selectedTrip?->requires_bike)
                    <div>
                        <h3 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[#1a3a2a]/50">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1a3a2a] text-white text-[10px]">4</span>
                            License & Bike Details
                        </h3>
                        <div class="space-y-4 rounded-2xl border border-[#1a3a2a]/15 bg-white p-5">

                            {{-- License check --}}
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Valid Bike License? *</label>
                                <select wire:model.live="has_license"
                                    class="{{ $errors->has('has_license') ? $inputError : $inputBase }}">
                                    <option value="">Select</option>
                                    <option value="1">Yes, I have a valid license</option>
                                    <option value="0">No, I don't have one</option>
                                </select>
                                @error('has_license')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                                @if ($isNoLicense)
                                <div class="mt-2 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 p-3">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                    </svg>
                                    <p class="text-xs font-semibold text-red-700">A valid bike license is mandatory for this trip. You cannot proceed without one.</p>
                                </div>
                                @endif
                            </div>

                            @if ($has_license == "1")
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Country of Issue *</label>
                                    <input type="text" wire:model="license_country" placeholder="e.g. Nepal"
                                        class="{{ $errors->has('license_country') ? $inputError : $inputBase }}" />
                                    @error('license_country')
                                    <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Type *</label>
                                    <select wire:model="license_type"
                                        class="{{ $errors->has('license_type') ? $inputError : $inputBase }}">
                                        <option value="international">International Permit</option>
                                        <option value="local">Local License</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Number *</label>
                                    <input type="text" wire:model="license_number" placeholder="e.g. NP-123456"
                                        class="{{ $errors->has('license_number') ? $inputError : $inputBase }}" />
                                    @error('license_number')
                                    <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Photo *</label>
                                    <div class="rounded-xl border-2 border-dashed border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-5 text-center">
                                        <input type="file" wire:model="license_image" accept="image/*"
                                            class="w-full text-xs text-[#1a3a2a]/70 file:mr-3 file:rounded-lg file:border-0 file:bg-[#1a3a2a] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white file:transition file:hover:brightness-110" />
                                        <p class="mt-2 text-xs text-[#1a3a2a]/50">JPG, PNG up to 4MB</p>
                                    </div>
                                    @error('license_image')
                                    <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            <hr class="border-[#1a3a2a]/10" />

                            {{-- Own bike --}}
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Bringing your own bike? *</label>
                                <select wire:model.live="has_own_bike"
                                    class="{{ $errors->has('has_own_bike') ? $inputError : $inputBase }}">
                                    <option value="">Select option</option>
                                    <option value="1">Yes, bringing my own</option>
                                    <option value="0">No, I'll rent one</option>
                                </select>
                                @error('has_own_bike')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            @if ($has_own_bike == "1")
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Bike Model & Year *</label>
                                <input type="text" wire:model="own_bike_model" placeholder="e.g. Honda CRF300L 2023"
                                    class="{{ $errors->has('own_bike_model') ? $inputError : $inputBase }}" />
                                @error('own_bike_model')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @elseif ($has_own_bike == "0")
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Select Rental Bike *</label>
                                <select wire:model="rental_bike_id"
                                    class="{{ $errors->has('rental_bike_id') ? $inputError : $inputBase }}">
                                    <option value="">Choose a bike</option>
                                    @foreach ($this->availableBikeRentals as $bike)
                                    <option value="{{ $bike->id }}">{{ $bike->model }} — {{ $bike->engine_cc }}cc — ${{ $bike->price_per_day_usd }}/day</option>
                                    @endforeach
                                </select>
                                @error('rental_bike_id')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- ── SECTION: Message ── --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Additional Notes <span class="font-normal normal-case tracking-normal text-[#1a3a2a]/50">(optional)</span></label>
                        <textarea wire:model="message" rows="3" placeholder="Anything we should know? Special requirements, dietary needs, etc."
                            class="{{ $errors->has('message') ? $inputError : $inputBase }} resize-none"></textarea>
                        @error('message')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="tn-btn-primary w-full py-4 text-base transition hover:brightness-110 disabled:opacity-50"
                        @if($licenseBlocked) disabled @endif>
                        <span wire:loading.remove wire:target="submit">Confirm Booking Request</span>
                        <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Processing...
                        </span>
                    </button>

                </fieldset>
            </form>
            @endif
        </div>
    </div>
</div>