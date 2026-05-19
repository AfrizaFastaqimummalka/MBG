<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sparepart extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'vehicle_id',
        'name',
        'price',
        'installed_date',
        'lifespan',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'installed_date' => 'date',
            'price'          => 'integer',
            'lifespan'       => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // ─── Computed helpers ────────────────────────────────────────────────────

    public function getStatusAttribute(): string
    {
        return \App\Helpers\FormatHelper::getPartStatus($this);
    }

    public function getPctAttribute(): float
    {
        return \App\Helpers\FormatHelper::getPartPct($this);
    }
}
