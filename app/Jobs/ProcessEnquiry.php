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

    public function __construct(public array $data) {}

    public function handle(): void
    {
        // 1. Create the Enquiry record
        $enquiry = Enquiry::create($this->data);

        // Load trip relationship for the email
        $enquiry->load('trip');

        try {
            // 2. Email to the Customer
            Mail::raw("Hi {$enquiry->name},\n\nThank you for reaching out to TravelNepal! We have received your message regarding '{$enquiry->subject}'.\n\n"
                . "Our team will review your enquiry and get back to you shortly.\n\nBest regards,\nTravelNepal Team", function ($message) use ($enquiry) {
                $message->to($enquiry->email)
                    ->subject('Enquiry Received - TravelNepal');
            });

            // 3. Email to the Admin (Optional: You can use a Mailable here too)
            $adminEmail = config('mail.from.address');
            Mail::raw("New Enquiry Received:\n\nName: {$enquiry->name}\nEmail: {$enquiry->email}\nSubject: {$enquiry->subject}\nTrip: " . ($enquiry->trip->title ?? 'None') . "\nMessage: {$enquiry->message}", function ($message) use ($enquiry, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('New Website Enquiry: ' . $enquiry->subject);
            });
        } catch (\Exception $e) {
            Log::error("Enquiry Job Email Failed: " . $e->getMessage());
        }
    }
}
