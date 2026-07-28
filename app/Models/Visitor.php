<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'device_id',
        'avatar',
        'ip_address',
        'city',
        'region',
        'country',
        'latitude',
        'longitude',
        'is_blocked',
        'last_seen_at',
        'total_scans',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'last_seen_at' => 'datetime',
        'total_scans' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function scanLogs(): HasMany
    {
        return $this->hasMany(ScanLog::class);
    }

    public function getFormattedLocationAttribute(): string
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return $parts ? implode(', ', $parts) : '—';
    }

    public function getDevicesAttribute(): array
    {
        return $this->scanLogs()
            ->whereNotNull('device_info')
            ->pluck('device_info')
            ->unique()
            ->values()
            ->toArray();
    }
}
