<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Trip extends Model
{
    use HasFactory;
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
        'highlights' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'requires_bike' => 'boolean',
        'bike_rental_available' => 'boolean',
    ];


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
        return $this->hasMany(Package::class)
            ->orderByRaw("CASE tier WHEN 'standard' THEN 1 WHEN 'premium' THEN 2 WHEN 'luxury' THEN 3 ELSE 4 END");
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)->latest();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest();
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Eager-load relations needed for the public trip detail page (ordered / constrained).
     */
    public function scopeWithEagerLoading(Builder $query): Builder
    {
        return $query->with([
            'category',
            'itineraryDays' => fn($q) => $q->orderBy('day_number'),
            'attractions' => fn($q) => $q->orderBy('sort_order'),
            'images' => fn($q) => $q->orderBy('sort_order'),
            'faqs' => fn($q) => $q->orderBy('sort_order'),
            'packages' => fn($q) => $q->where('is_active', true)->with('inclusions'),
            'testimonials' => fn($q) => $q->where('is_approved', true)->latest()->take(3),
            'bikeRentals' => fn($q) => $q->where('is_available', true)->orderBy('sort_order'),
        ]);
    }

    /**
     * Homepage featured trips: category plus active packages for starting price.
     */
    public function scopeFeaturedWithRelations(Builder $query): Builder
    {
        return $query->featured()
            ->active()
            ->with([
                'category',
                'packages' => fn($q) => $q->where('is_active', true),
            ]);
    }

    /**
     * Trip listing grid: category and active packages (supports starting_price without N+1).
     */
    public function scopeForListing(Builder $query): Builder
    {
        return $query->active()
            ->with([
                'category',
                'packages' => fn($q) => $q->where('is_active', true),
            ]);
    }

    public static function findBySlug(string $slug): self
    {
        return static::query()
            ->active()
            ->where('slug', $slug)
            ->withEagerLoading()
            ->firstOrFail();
    }

    // Accessor
    public function getStartingPriceAttribute(): ?float
    {
        if ($this->relationLoaded('packages')) {
            $minimum = $this->packages
                ->where('is_active', true)
                ->min('price_usd');

            return $minimum !== null ? (float) $minimum : null;
        }

        $minimum = $this->packages()
            ->where('is_active', true)
            ->min('price_usd');

        return $minimum !== null ? (float) $minimum : null;
    }
    // UI Accessors
    public function getImageUrlAttribute(): string
    {
        if (!$this->cover_image) {
            return 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800&q=80';
        }

        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }

        $url = Storage::url($this->cover_image);

        // Debug: Log or dump the URL
        Log::info('Image URL: ' . $url);

        return $url;
    }


    public function getDifficultyBadgeAttribute(): string
    {
        return match ($this->difficulty) {
            'easy' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'moderate' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'challenging' => 'bg-orange-100 text-orange-700 ring-orange-200',
            'extreme' => 'bg-red-100 text-red-700 ring-red-200',
            default => 'bg-zinc-100 text-zinc-700 ring-zinc-200',
        };
    }

    public function getFormattedPriceAttribute(): ?string
    {
        return $this->starting_price
            ? number_format($this->starting_price, 0)
            : null;
    }

    public function getDifficultyLabelAttribute(): string
    {
        return ucfirst($this->difficulty);
    }
}
