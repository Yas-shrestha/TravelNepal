<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What fitness level is required for a motorbike tour?',
                'answer' => 'You should be comfortable riding a mid-weight motorcycle for several hours a day on mountain roads, including gravel and occasional traffic. A valid motorcycle licence is required. If you are new to Himalayan riding, we recommend our moderate routes first and honest disclosure of your experience so we can match you to the right group.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Do I need a motorcycle license to join a motorbike trip?',
                'answer' => 'Yes. You must hold a valid motorcycle licence from your home country and carry it in Nepal. For many nationalities an International Driving Permit (IDP) with motorcycle endorsement is strongly recommended. We cannot allow riders without appropriate documentation to operate bikes on public roads.',
                'sort_order' => 2,
            ],
            [
                'question' => 'What travel insurance do you recommend?',
                'answer' => 'Choose a policy that covers trekking or motorcycling at your maximum altitude or activity type, includes medical evacuation (helicopter where possible), trip cancellation, and gear. Read exclusions carefully—many standard policies exclude riding above 3,500 m or off-road motorcycling.',
                'sort_order' => 3,
            ],
            [
                'question' => 'What is your cancellation policy?',
                'answer' => 'Deposits are generally non-refundable inside 45 days of departure because lodges and permits are committed on your behalf. Between 45 and 60 days we may offer a partial credit toward a future trip, subject to supplier charges we cannot recover. Full policy is sent with your invoice and varies slightly by trip type.',
                'sort_order' => 4,
            ],
            [
                'question' => 'What currency should I bring to Nepal?',
                'answer' => 'We quote and invoice major trips in USD. In Nepal you will use Nepalese Rupees (NPR) for day-to-day spending—ATMs are available in Kathmandu and Pokhara. Carry small denominations for rural areas. Major hotels and some shops accept cards; trekking trails are largely cash-only.',
                'sort_order' => 5,
            ],
            [
                'question' => 'Are your guides government licensed?',
                'answer' => 'Yes. Our trekking leaders hold TAAN-recognised guide certifications and required first-aid training where applicable. Cultural guides are licensed by the Nepal Tourism Board. Motorbike tour leaders are chosen for documented high-country riding experience and local route knowledge.',
                'sort_order' => 6,
            ],
            [
                'question' => 'What is the best time of year to visit Nepal?',
                'answer' => 'Pre-monsoon (March–May) and post-monsoon (late September–November) offer the clearest mountain views and stable weather for trekking and riding. June–August is monsoon—greener but wetter. December–February is cold and clear at altitude; short hikes and cultural tours remain pleasant in the valleys.',
                'sort_order' => 7,
            ],
            [
                'question' => 'Can I customise an itinerary?',
                'answer' => 'Absolutely. Private departures, extra rest days, hotel upgrades, helicopter transfers, and family-friendly pacing are all possible. Send your dates, group size, and ideas through our contact form—we typically reply within one business day with a tailored outline and indicative pricing.',
                'sort_order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                ]
            );
        }
    }
}
