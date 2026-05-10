<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Package extends Model
{
    protected $fillable = [
        'trip_id',
        'tier',
        'title',
        'price_usd',
        'is_popular',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price_usd'  => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(PackageInclusion::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
