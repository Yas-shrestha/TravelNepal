<?php

use App\Models\BikeRental;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Trip;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

new class extends Component
{
    use WithFileUploads;

    public bool $open = false;

    public ?int $trip_id = null;

    public ?int $package_id = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $preferred_date = '';

    public int $group_size = 1;

    public string $message = '';

    public ?bool $has_license = null;

    public string $license_number = '';

    public string $license_country = '';

    public string $license_type = 'international';

    public $license_image;

    public ?bool $has_own_bike = null;

    public string $own_bike_model = '';

    public ?int $rental_bike_id = null;

    protected $listeners = [
        'open-booking-popup' => 'openPopup',
    ];

    public function openPopup(?int $tripId = null, ?int $packageId = null): void
    {
        $this->open = true;
        $this->trip_id = $tripId;
        $this->package_id = $packageId;

        if ($tripId && ! $packageId) {
            $this->package_id = Package::query()
                ->where('trip_id', $tripId)
                ->where('is_active', true)
                ->orderByRaw("CASE tier WHEN 'standard' THEN 1 WHEN 'premium' THEN 2 WHEN 'luxury' THEN 3 ELSE 4 END")
                ->value('id');
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
        $trip = $this->trip_id ? Trip::query()->find($this->trip_id) : null;
        $hasLicense = $this->toNullableBool($this->has_license);
        $hasOwnBike = $this->toNullableBool($this->has_own_bike);

        $rules = [
            'trip_id' => ['required', 'exists:trips,id'],
            'package_id' => ['required', 'exists:packages,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'group_size' => ['required', 'integer', 'min:1', 'max:30'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];

        if ($trip?->requires_bike) {
            $rules = array_merge($rules, [
                'has_license' => ['required', 'boolean'],
                'has_own_bike' => ['required', 'boolean'],
            ]);

            if ($hasLicense === true) {
                $rules = array_merge($rules, [
                    'license_number' => ['required', 'string', 'max:100'],
                    'license_country' => ['required', 'string', 'max:100'],
                    'license_type' => ['required', 'in:local,international'],
                    'license_image' => ['required', 'image', 'max:4096'],
                ]);
            }

            if ($hasOwnBike === true) {
                $rules['own_bike_model'] = ['required', 'string', 'max:255'];
            }

            if ($hasOwnBike === false && $trip->bike_rental_available) {
                $rules['rental_bike_id'] = ['required', 'exists:bike_rentals,id'];
            }
        }

        $validated = $this->validate($rules);

        if ($trip?->requires_bike && $hasLicense === false) {
            $this->addError('has_license', 'A valid bike license is required for motorbike trips.');

            return;
        }

        $licenseImagePath = null;
        if ($this->license_image) {
            $licenseImagePath = $this->license_image->store('licenses', 'public');
        }

        $rentalCost = null;
        if ($this->rental_bike_id) {
            $bike = BikeRental::query()->find($this->rental_bike_id);
            if ($bike && $trip) {
                $rentalCost = round((float) $bike->price_per_day_usd * (int) $trip->duration_days, 2);
            }
        }

        Booking::query()->create([
            'trip_id' => $validated['trip_id'],
            'package_id' => $validated['package_id'],
            'rental_bike_id' => $this->rental_bike_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?: null,
            'preferred_date' => $validated['preferred_date'],
            'group_size' => $validated['group_size'],
            'message' => $validated['message'] ?: null,
            'has_own_bike' => $hasOwnBike,
            'own_bike_model' => $this->own_bike_model !== '' ? $this->own_bike_model : null,
            'rental_cost_usd' => $rentalCost,
            'has_license' => $hasLicense,
            'license_number' => $this->license_number !== '' ? $this->license_number : null,
            'license_country' => $this->license_country !== '' ? $this->license_country : null,
            'license_type' => $this->license_type !== '' ? $this->license_type : null,
            'license_image' => $licenseImagePath,
            'status' => 'pending',
        ]);

        try {
            Mail::raw('Your booking request has been received. We will contact you within 24 hours.', function ($message): void {
                $message->to($this->email)
                    ->subject('TravelNepal Booking Confirmation');
            });
        } catch (\Throwable $exception) {
        }

        $this->reset([
            'name', 'email', 'phone', 'preferred_date', 'group_size', 'message',
            'has_license', 'license_number', 'license_country', 'license_type', 'license_image',
            'has_own_bike', 'own_bike_model', 'rental_bike_id',
        ]);
        $this->group_size = 1;
        $this->open = true;
        session()->flash('booking_success', "Your booking request has been sent! We'll contact you within 24 hours.");
    }

    public function getSelectedTripProperty(): ?Trip
    {
        return $this->trip_id ? Trip::query()->find($this->trip_id) : null;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getAvailableTripsProperty(): Collection
    {
        return Trip::query()->active()->orderBy('title')->get(['id', 'title']);
    }

    /**
     * @return Collection<int, Package>
     */
    public function getAvailablePackagesProperty(): Collection
    {
        if (! $this->trip_id) {
            return collect();
        }

        return Package::query()
            ->where('trip_id', $this->trip_id)
            ->where('is_active', true)
            ->orderByRaw("CASE tier WHEN 'standard' THEN 1 WHEN 'premium' THEN 2 WHEN 'luxury' THEN 3 ELSE 4 END")
            ->get();
    }

    /**
     * @return Collection<int, BikeRental>
     */
    public function getAvailableBikeRentalsProperty(): Collection
    {
        if (! $this->trip_id) {
            return collect();
        }

        return BikeRental::query()
            ->where('trip_id', $this->trip_id)
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getSelectedRentalBikeProperty(): ?BikeRental
    {
        if (! $this->rental_bike_id) {
            return null;
        }

        return BikeRental::query()->find($this->rental_bike_id);
    }

    public function getRentalTotalUsdProperty(): ?float
    {
        $trip = $this->selectedTrip;
        $bike = $this->selectedRentalBike;

        if (! $trip || ! $bike) {
            return null;
        }

        return round((float) $bike->price_per_day_usd * (int) $trip->duration_days, 2);
    }

    private function toNullableBool(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}; ?>

@php
    $licenseBlocked = (bool) ($this->selectedTrip?->requires_bike && (string) $has_license === '0');
@endphp

<div
    x-data="{ open: @entangle('open') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[100] flex items-stretch justify-center p-0 md:items-center md:p-4"
    style="display: none;"
>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/70"
        @click="$wire.closePopup()"
    ></div>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative z-10 flex max-h-full w-full max-w-none flex-col overflow-y-auto bg-adventure-beige p-6 shadow-2xl md:max-h-[92vh] md:max-w-lg md:rounded-3xl md:p-8"
        @click.stop
    >
        <button type="button" class="sticky top-0 z-20 ml-auto flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-adventure-beige/95 text-2xl text-[#1a3a2a]/70 shadow-sm transition hover:text-[#1a3a2a] md:absolute md:right-4 md:top-4 md:ml-0 md:bg-transparent md:shadow-none" @click="$wire.closePopup()" aria-label="Close">✕</button>

        @if (session('booking_success'))
            <div class="rounded-xl bg-green-100 px-4 py-4 font-sans text-sm text-green-900">
                {{ session('booking_success') }}
            </div>
            <button type="button" class="tn-btn-primary mt-6 w-full px-6 py-3 transition hover:brightness-110" wire:click="closePopup">Close</button>
        @else
            @if ($this->selectedTrip)
                <p class="font-sans text-sm font-semibold uppercase tracking-wider text-[#c8860a]">{{ $this->selectedTrip->title }}</p>
            @endif
            <h2 class="font-display text-3xl font-bold text-[#1a3a2a]">Plan My Trip</h2>
            <form wire:submit="submit" class="mt-6 space-y-5 font-sans" @if ($licenseBlocked) aria-disabled="true" @endif>
                <fieldset class="min-w-0 space-y-5 border-0 p-0" @disabled($licenseBlocked)>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Trip</label>
                            <select wire:model.live="trip_id" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                <option value="">Select a trip</option>
                                @foreach ($this->availableTrips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->title }}</option>
                                @endforeach
                            </select>
                            @error('trip_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Package</label>
                            <select wire:model.live="package_id" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                <option value="">Select package</option>
                                @foreach ($this->availablePackages as $package)
                                    <option value="{{ $package->id }}">{{ ucfirst($package->tier) }} — ${{ number_format((float) $package->price_usd, 2) }}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Name</label>
                            <input type="text" wire:model.live="name" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Email</label>
                            <input type="email" wire:model.live="email" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Phone</label>
                            <input type="text" wire:model.live="phone" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Preferred Date</label>
                            <input type="date" wire:model.live="preferred_date" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                            @error('preferred_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Group Size</label>
                            <input type="number" min="1" wire:model.live="group_size" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Message</label>
                            <textarea wire:model.live="message" rows="2" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm"></textarea>
                        </div>
                    </div>

                    @if ($this->selectedTrip?->requires_bike)
                        <div class="rounded-2xl border border-[#1a3a2a]/15 bg-white p-4">
                            <h3 class="font-display text-xl font-semibold text-[#1a3a2a]">Motorbike Requirements</h3>

                            <div class="mt-3">
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Do you have a valid bike license?</label>
                                <select wire:model.live="has_license" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @if ((string) $has_license === '0')
                                    <p class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-800">A valid motorbike license is required for this trip. We cannot confirm a booking without one.</p>
                                @endif
                                @error('has_license')
                                    <p class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ((string) $has_license === '1')
                                <div class="mt-3 grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Number</label>
                                        <input type="text" wire:model.live="license_number" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                                        @error('license_number')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Country</label>
                                        <input type="text" wire:model.live="license_country" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                                        @error('license_country')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Type</label>
                                        <select wire:model.live="license_type" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                            <option value="local">Local</option>
                                            <option value="international">International</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">License Image</label>
                                        <input type="file" wire:model="license_image" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                                        @error('license_image')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3">
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Do you have your own bike?</label>
                                <select wire:model.live="has_own_bike" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('has_own_bike')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ((string) $has_own_bike === '1')
                                <div class="mt-3">
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Bike Model</label>
                                    <input type="text" wire:model.live="own_bike_model" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm" />
                                    @error('own_bike_model')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @if ((string) $has_own_bike === '0')
                                @if ($this->selectedTrip?->bike_rental_available)
                                    <div class="mt-3">
                                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Rental Bike</label>
                                        <select wire:model.live="rental_bike_id" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-white px-4 py-3 text-sm">
                                            <option value="">Select rental</option>
                                            @foreach ($this->availableBikeRentals as $rental)
                                                <option value="{{ $rental->id }}">{{ $rental->model }} ({{ $rental->engine_cc }}cc) — ${{ number_format((float) $rental->price_per_day_usd, 2) }}/day</option>
                                            @endforeach
                                        </select>
                                        @error('rental_bike_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        @if ($this->selectedRentalBike && $this->rentalTotalUsd !== null)
                                            <div class="mt-3 rounded-xl border border-[#1a3a2a]/10 bg-adventure-beige/80 px-4 py-3 text-sm text-[#1a3a2a]">
                                                <p><strong>{{ $this->selectedRentalBike->model }}</strong> · {{ $this->selectedRentalBike->engine_cc }}cc</p>
                                                <p class="mt-1 text-[#1a3a2a]/80">${{ number_format((float) $this->selectedRentalBike->price_per_day_usd, 2) }} per day × {{ $this->selectedTrip->duration_days }} days</p>
                                                <p class="mt-2 font-semibold text-[#c8860a]">Rental total: ${{ number_format((float) $this->rentalTotalUsd, 2) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="mt-3 rounded-lg bg-yellow-100 px-3 py-2 text-sm text-yellow-900">Please contact us to discuss bike arrangements.</p>
                                @endif
                            @endif
                        </div>
                    @endif

                    <button type="submit" wire:loading.attr="disabled" @disabled($licenseBlocked) class="tn-btn-primary w-full px-6 py-3 transition hover:brightness-110">
                        <span wire:loading.remove>Send Booking Request</span>
                        <span wire:loading>Sending...</span>
                    </button>
                </fieldset>
            </form>
        @endif
    </div>
</div>
