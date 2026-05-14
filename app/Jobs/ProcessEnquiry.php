<?php

namespace App\Jobs;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessEnquiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Enquiry $enquiry) {}  // pass model, not array

    public function handle(): void
    {
        $this->enquiry->load('trip');

        try {
            Mail::raw(
                "Hi {$this->enquiry->name},\n\nThank you for reaching out to TravelNepal! We have received your message regarding '{$this->enquiry->subject}'.\n\nOur team will review your enquiry and get back to you shortly.\n\nBest regards,\nTravelNepal Team",
                fn($message) => $message->to($this->enquiry->email)->subject('Enquiry Received - TravelNepal')
            );

            $adminEmail = config('mail.from.address');
            Mail::raw(
                "New Enquiry Received:\n\nName: {$this->enquiry->name}\nEmail: {$this->enquiry->email}\nSubject: {$this->enquiry->subject}\nTrip: " . ($this->enquiry->trip->title ?? 'None') . "\nMessage: {$this->enquiry->message}",
                fn($message) => $message->to($adminEmail)->subject('New Website Enquiry: ' . $this->enquiry->subject)
            );
        } catch (\Exception $e) {
            Log::error("Enquiry Job Email Failed: " . $e->getMessage());
        }
    }
}
