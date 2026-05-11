<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class EnquirySeeder extends Seeder
{
    public function run(): void
    {
        $trips = Trip::all();

        $enquiries = [
            [
                'name'    => 'James Wilson',
                'email'   => 'james.wilson@gmail.com',
                'phone'   => '+44 7911 123456',
                'subject' => 'Custom Itinerary',
                'message' => 'Hi, I am interested in planning a custom motorbike tour from Kathmandu to Mustang. Can you help me plan a 15-day route?',
                'trip_id' => $trips->where('slug', 'kathmandu-to-mustang-motorbike-expedition')->first()?->id,
                'status'  => 'unread',
            ],
            [
                'name'    => 'Sarah Chen',
                'email'   => 'sarah.chen@outlook.com',
                'phone'   => '+1 415 555 0192',
                'subject' => 'Trip Question',
                'message' => 'What is the best time to do the Everest Base Camp Trek? I am planning for next year.',
                'trip_id' => $trips->where('slug', 'everest-base-camp-trek')->first()?->id,
                'status'  => 'read',
            ],
            [
                'name'    => 'Marco Rossi',
                'email'   => 'marco.rossi@gmail.com',
                'phone'   => '+39 02 1234567',
                'subject' => 'General Enquiry',
                'message' => 'Do you offer group discounts for parties of 8 or more people?',
                'trip_id' => null,
                'status'  => 'replied',
            ],
            [
                'name'    => 'Priya Sharma',
                'email'   => 'priya.sharma@yahoo.com',
                'phone'   => '+91 98765 43210',
                'subject' => 'Trip Question',
                'message' => 'Is the Annapurna Circuit suitable for someone who has never trekked before?',
                'trip_id' => $trips->where('slug', 'annapurna-circuit-trek')->first()?->id,
                'status'  => 'unread',
            ],
            [
                'name'    => 'David Park',
                'email'   => 'david.park@gmail.com',
                'phone'   => '+82 10 1234 5678',
                'subject' => 'Custom Itinerary',
                'message' => 'I would like to combine a motorbike tour with a cultural tour of Kathmandu Valley. Is this possible?',
                'trip_id' => null,
                'status'  => 'unread',
            ],
        ];

        foreach ($enquiries as $enquiry) {
            Enquiry::create($enquiry);
        }
    }
}
