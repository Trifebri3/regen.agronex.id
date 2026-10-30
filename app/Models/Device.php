<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'organization_id',
    'farm_id',
    'land_id',
    'land_plot_id',
    'device_uid',
    'name',
    'product_line',
    'firmware_version',
    'connection_mode',
    'is_demo',
    'status',
    'battery_level',
    'signal_rssi',
    'last_seen_at',
    'sensor_config',
])]
class Device extends Model
{
    /** @use HasFactory<DeviceFactory> */
    use BelongsToOrganization, HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_demo' => 'boolean',
            'battery_level' => 'integer',
            'signal_rssi' => 'integer',
            'last_seen_at' => 'datetime',
            'sensor_config' => 'array',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(LandPlot::class, 'land_plot_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class)->orderByDesc('recorded_at')->orderByDesc('id');
    }

    public function latestReading(): HasOne
    {
        return $this->hasOne(SensorReading::class)->latestOfMany('recorded_at');
    }

    public function advisories(): HasMany
    {
        return $this->hasMany(Advisory::class);
    }
}
