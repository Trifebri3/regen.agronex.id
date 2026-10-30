<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_id',
    'land_id',
    'name',
    'plot_code',
    'area_ha',
    'soil_condition',
    'irrigation_type',
    'notes',
])]
class LandPlot extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'area_ha' => 'decimal:2',
        ];
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    public function cropCycles(): HasMany
    {
        return $this->hasMany(CropCycle::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
