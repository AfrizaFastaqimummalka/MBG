<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'wa_service_reminder',
        'wa_sparepart_alert',
        'wa_phone',
    ];

    protected function casts(): array
    {
        return [
            'wa_service_reminder' => 'boolean',
            'wa_sparepart_alert'  => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
