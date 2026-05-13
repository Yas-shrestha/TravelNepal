<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            background-color: #f4f4f2;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1a3a2a;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #c8860a;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .detail-row {
            margin-bottom: 10px;
            display: flex;
        }

        .label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }

        .value {
            flex: 1;
        }

        .footer {
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>New Trip Booking</h1>
            </div>

            <div class="content">
                <div class="section-title">Customer Information</div>
                <div class="detail-row"><span class="label">Name:</span> <span class="value">{{ $booking->name }}</span></div>
                <div class="detail-row"><span class="label">Email:</span> <span class="value">{{ $booking->email }}</span></div>
                <div class="detail-row"><span class="label">Phone:</span> <span class="value">{{ $booking->phone ?? 'N/A' }}</span></div>

                <div class="section-title">Trip Details</div>
                <div class="detail-row"><span class="label">Trip:</span> <span class="value"><strong>{{ $booking->trip->title ?? 'N/A' }}</strong></span></div>
                <div class="detail-row"><span class="label">Package:</span> <span class="value">{{ ucfirst($booking->package->tier ?? 'N/A') }}</span></div>
                <div class="detail-row"><span class="label">Travel Date:</span> <span class="value">{{ $booking->preferred_date }}</span></div>
                <div class="detail-row"><span class="label">Group Size:</span> <span class="value">{{ $booking->group_size }} Person(s)</span></div>

                <div class="section-title">Motorbike & Requirements</div>
                <div class="detail-row"><span class="label">License:</span> <span class="value">{{ $booking->has_license ? 'Yes ('.$booking->license_type.')' : 'No' }}</span></div>

                @if($booking->rental_bike_id)
                <div class="detail-row"><span class="label">Rental Bike:</span> <span class="value">{{ $booking->rentalBike->model ?? 'Rental Selected' }}</span></div>
                <div class="detail-row"><span class="label">Rental Est:</span> <span class="value">${{ number_format($booking->rental_cost_usd, 2) }}</span></div>
                @elseif($booking->has_own_bike)
                <div class="detail-row"><span class="label">Own Bike:</span> <span class="value">{{ $booking->own_bike_model }}</span></div>
                @endif

                @if($booking->message)
                <div class="section-title" style="margin-top: 20px;">Customer Message</div>
                <div style="background: #fdfaf3; padding: 15px; border-radius: 5px; font-style: italic;">
                    "{{ $booking->message }}"
                </div>
                @endif
            </div>

            <div class="footer">
                This request was submitted via the TravelNepal Booking Form.
            </div>
        </div>
    </div>
</body>

</html>