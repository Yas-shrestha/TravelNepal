<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TripAttraction extends Model
{
    protected $fillable = [
        'trip_id',
        'name',
        'description',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=80';
        }

        return Str::startsWith($this->image_path, ['http://', 'https://'])
            ? $this->image_path
            : Storage::url($this->image_path);
    }
}
