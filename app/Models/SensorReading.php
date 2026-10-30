<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'organization_id',
    'device_id',
    'is_demo',
    'recorded_at',
    'soil_ph',
    'soil_moisture_pct',
    'soil_ec_ds_m',
    'nitrogen_mg_kg',
    'phosphorus_mg_kg',
    'potassium_mg_kg',
    'water_flow_lpm',
    'water_level_cm',
    'ambient_temp_c',
    'humidity_pct',
    'rainfall_mm',
    'raw_payload',
])]
class SensorReading extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_demo' => 'boolean',
            'recorded_at' => 'datetime',
            'soil_ph' => 'decimal:2',
            'soil_moisture_pct' => 'decimal:2',
            'soil_ec_ds_m' => 'decimal:3',
            'nitrogen_mg_kg' => 'decimal:2',
            'phosphorus_mg_kg' => 'decimal:2',
            'potassium_mg_kg' => 'decimal:2',
            'water_flow_lpm' => 'decimal:2',
            'water_level_cm' => 'decimal:2',
            'ambient_temp_c' => 'decimal:2',
            'humidity_pct' => 'decimal:2',
            'rainfall_mm' => 'decimal:2',
            'raw_payload' => 'array',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
