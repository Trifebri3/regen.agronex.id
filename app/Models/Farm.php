<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Database\Factories\FarmFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_id',
    'manager_id',
    'name',
    'code',
    'location',
    'province',
    'latitude',
    'longitude',
    'total_area_ha',
    'elevation_m',
    'status',
    'notes',
])]
class Farm extends Model
{
    /** @use HasFactory<FarmFactory> */
    use BelongsToOrganization, HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'total_area_ha' => 'decimal:2',
            'elevation_m' => 'integer',
        ];
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

    public function cropCycles(): HasMany
    {
        return $this->hasMany(CropCycle::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(FarmActivity::class);
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(FarmInput::class);
    }

    public function harvests(): HasMany
    {
        return $this->hasMany(HarvestRecord::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(SalesRecord::class);
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function advisories(): HasMany
    {
        return $this->hasMany(Advisory::class);
    }
}
