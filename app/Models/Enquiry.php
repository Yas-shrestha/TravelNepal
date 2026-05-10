<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Enquiry extends Model
{
    protected $fillable = [
        'trip_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'unread');
    }

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
