<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BikeRental extends Model
{
    protected $fillable = [
        'trip_id',
        'model',
        'engine_cc',
        'price_per_day_usd',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'price_per_day_usd' => 'decimal:2',
        'is_available'      => 'boolean',
        'sort_order'        => 'integer',
        'engine_cc'         => 'integer',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'rental_bike_id');
    }
}
