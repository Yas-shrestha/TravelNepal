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

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $data
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Save the booking to the database
        $booking = Booking::create($this->data);

        // 2. Eager Load relationships to avoid any extra queries in the email view
        // This is where you prevent that "Lazy Loading" query
        $booking->load(['trip', 'package']);

        try {
            // 3. Send Customer Email
            Mail::raw(
                "Hi {$booking->name},\n\n" .
                    "Thank you for booking the '{$booking->trip->title}' adventure with TravelNepal!\n\n" .
                    "We have received your request for {$booking->preferred_date} and our team is currently reviewing the details. " .
                    "We will contact you via phone or email within the next 24 hours to finalize your itinerary.\n\n" .
                    "Safe travels,\n" .
                    "The TravelNepal Team",
                function ($message) use ($booking) {
                    $message->to($booking->email)
                        ->subject('Booking Received - ' . $booking->trip->title);
                }
            );

            // 4. Send Admin Email (The Mailable)
            $adminEmail = config('mail.from.address') ?? env('MAIL_USERNAME');
            Mail::to($adminEmail)->send(new NewBookingNotification($booking));
        } catch (Exception $e) {
            Log::error("Booking Job Email Failed: " . $e->getMessage());
        }
    }
}
