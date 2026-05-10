<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Trip extends Model
{

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'difficulty',
        'duration_days',
        'max_altitude_m',
        'route_distance_km',
        'min_group_size',
        'max_group_size',
        'best_season',
        'overview',
        'highlights',
        'cover_image',
        'is_featured',
        'is_active',
        'requires_bike',
        'bike_rental_available',
    ];

    protected $casts = [
        'highlights'           => 'array',
        'is_featured'          => 'boolean',
        'is_active'            => 'boolean',
        'requires_bike'        => 'boolean',
        'bike_rental_available' => 'boolean',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itineraryDays(): HasMany
    {
        return $this->hasMany(ItineraryDay::class)->orderBy('day_number');
    }

    public function attractions(): HasMany
    {
        return $this->hasMany(TripAttraction::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TripImage::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(TripFaq::class)->orderBy('sort_order');
    }

    public function bikeRentals(): HasMany
    {
        return $this->hasMany(BikeRental::class)->orderBy('sort_order');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Accessor
    public function getStartingPriceAttribute(): ?float
    {
        return $this->packages()
            ->where('is_active', true)
            ->min('price_usd');
    }
}
