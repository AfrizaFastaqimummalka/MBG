<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'type',
        'year',
        'plate',
        'odometer',
        'owner',
        'photo_url',
        'next_service_date',
        'next_service_km',
    ];

    protected function casts(): array
    {
        return [
            'next_service_date' => 'date',
            'year'              => 'integer',
            'odometer'          => 'integer',
            'next_service_km'   => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function spareparts(): HasMany
    {
        return $this->hasMany(Sparepart::class);
    }

    // ─── Computed helpers ────────────────────────────────────────────────────

    public function getStatusAttribute(): string
    {
        return \App\Helpers\FormatHelper::getStatus($this);
    }
}
