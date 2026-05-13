<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    // testimonail avatar URL accessor
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Str::startsWith($this->avatar, ['http://', 'https://'])
                ? $this->avatar
                : Storage::url($this->avatar);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=1a3a2a&color=c8860a&size=80&bold=true";
    }
}
