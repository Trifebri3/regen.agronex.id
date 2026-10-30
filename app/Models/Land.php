<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Database\Factories\LandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'organization_id',
    'farm_id',
    'name',
    'code',
    'location',
    'area_ha',
    'ownership_status',
    'soil_type',
    'soil_ph',
    'soil_profile_notes',
    'photo_path',
    'coordinates_geojson',
    'status',
])]
class Land extends Model
{
    /** @use HasFactory<LandFactory> */
    use BelongsToOrganization, HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'area_ha' => 'decimal:2',
            'soil_ph' => 'decimal:2',
            'coordinates_geojson' => 'array',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(LandPlot::class);
    }

    public function cropCycles(): HasMany
    {
        return $this->hasMany(CropCycle::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function resolvedPhotoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        if (str_starts_with($this->photo_path, 'images/') || str_starts_with($this->photo_path, '/images/')) {
            return asset(ltrim($this->photo_path, '/'));
        }

        return Storage::disk('public')->url($this->photo_path);
    }
}
