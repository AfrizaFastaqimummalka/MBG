<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRecord extends Model
{
    use HasFactory, HasUuids;

    /**
     * Nama tabel di database adalah "services".
     */
    protected $table = 'services';

    protected $fillable = [
        'vehicle_id',
        'date',
        'cost',
        'odometer',
        'type',
        'workshop',
        'notes',
        'next_date',
        'next_km',
    ];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'next_date' => 'date',
            'cost'      => 'integer',
            'odometer'  => 'integer',
            'next_km'   => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
