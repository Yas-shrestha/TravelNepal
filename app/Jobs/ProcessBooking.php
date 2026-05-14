<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Mail\NewBookingNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Booking $booking  // ✅ model instead of array
    ) {}

    public function handle(): void
    {
        // ✅ No Booking::create() here anymore — already saved in submit()

        // Eager load relationships for the email
        $this->booking->load(['trip', 'package']);

        try {
            // Send Customer Email
            Mail::raw(
                "Hi {$this->booking->name},\n\n" .
                    "Thank you for booking the '{$this->booking->trip->title}' adventure with TravelNepal!\n\n" .
                    "We have received your request for {$this->booking->preferred_date} and our team is currently reviewing the details. " .
                    "We will contact you via phone or email within the next 24 hours to finalize your itinerary.\n\n" .
                    "Safe travels,\n" .
                    "The TravelNepal Team",
                function ($message) {
                    $message->to($this->booking->email)
                        ->subject('Booking Received - ' . $this->booking->trip->title);
                }
            );

            // Send Admin Email
            $adminEmail = config('mail.from.address') ?? env('MAIL_USERNAME');
            Mail::to($adminEmail)->queue(new NewBookingNotification($this->booking));
        } catch (Exception $e) {
            Log::error("Booking Job Email Failed: " . $e->getMessage());
        }
    }
}
