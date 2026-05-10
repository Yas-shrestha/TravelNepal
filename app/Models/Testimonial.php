<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Testimonial extends Model
{
    protected $fillable = [
        'trip_id',
        'name',
        'location',
        'avatar',
        'quote',
        'rating',
        'is_approved',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_approved' => 'boolean',
    ];

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
