<?php

use App\Models\Trip;
use App\Jobs\ProcessEnquiry;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = 'General Enquiry';
    public ?int $trip_id = null;
    public string $message = '';

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:120'],
            'trip_id' => ['nullable', 'exists:trips,id'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        // Dispatch background job
        ProcessEnquiry::dispatch(array_merge($validated, [
            'status' => 'unread',
        ]));

        $this->reset(['name', 'email', 'phone', 'trip_id', 'message']);
        $this->subject = 'General Enquiry';

        session()->flash('contact_success', 'Your enquiry has been sent. We will get back to you shortly.');
    }

    public function with(): array
    {
        return [
            'trips' => Trip::query()->active()->orderBy('title')->get(['id', 'title']),
        ];
    }
}; ?>

<div class="font-sans">
    <h2 class="font-display text-3xl font-bold text-[#1a3a2a]">Send Enquiry</h2>

    @if (session('contact_success'))
    <div class="mt-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-900">
        {{ session('contact_success') }}
    </div>
    @endif

    <form wire:submit="submit" class="mt-6 space-y-4">
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Full Name</label>
            <!-- Removed .live so the button doesn't flicker "Sending" while typing -->
            <input type="text" wire:model="name" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm" />
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Email</label>
            <input type="email" wire:model="email" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm" />
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Phone</label>
            <input type="text" wire:model="phone" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm" />
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Subject</label>
            <select wire:model="subject" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm">
                <option>General Enquiry</option>
                <option>Trip Question</option>
                <option>Custom Itinerary</option>
                <option>Other</option>
            </select>
            @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Trip of Interest</label>
            <select wire:model="trip_id" class="min-h-11 w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm">
                <option value="">Select a trip (optional)</option>
                @foreach ($trips as $trip)
                <option value="{{ $trip->id }}">{{ $trip->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#1a3a2a]">Message</label>
            <textarea wire:model="message" rows="4" class="w-full rounded-xl border border-[#1a3a2a]/20 bg-adventure-beige/50 px-4 py-3 text-sm"></textarea>
            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="tn-btn-primary w-full px-6 py-3 transition hover:brightness-110"
            wire:loading.attr="disabled">
            <!-- wire:target ensures the loading state only triggers on submission -->
            <span wire:loading.remove wire:target="submit">Send Message</span>
            <span wire:loading wire:target="submit">Sending...</span>
        </button>
    </form>
</div>  