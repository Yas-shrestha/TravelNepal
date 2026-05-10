<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryDay extends Model
{
    protected $fillable = [
        'trip_id',
        'day_number',
        'title',
        'description',
        'accommodation',
        'meals_included',
        'distance_km',
        'elevation_gain_m',
    ];

    protected $casts = [
        'distance_km'      => 'decimal:2',
        'elevation_gain_m' => 'integer',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
