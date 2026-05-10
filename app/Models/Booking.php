<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    protected $fillable = [
        'trip_id',
        'package_id',
        'rental_bike_id',
        'name',
        'email',
        'phone',
        'preferred_date',
        'group_size',
        'message',
        'has_own_bike',
        'own_bike_model',
        'rental_cost_usd',
        'has_license',
        'license_number',
        'license_country',
        'license_type',
        'license_image',
        'status',
    ];

    protected $casts = [
        'preferred_date'  => 'date',
        'group_size'      => 'integer',
        'has_own_bike'    => 'boolean',
        'rental_cost_usd' => 'decimal:2',
        'has_license'     => 'boolean',
    ];

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function rentalBike(): BelongsTo
    {
        return $this->belongsTo(BikeRental::class, 'rental_bike_id');
    }
}
