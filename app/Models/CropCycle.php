<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Database\Factories\CropCycleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_id',
    'farm_id',
    'land_id',
    'land_plot_id',
    'crop_name',
    'variety',
    'cycle_code',
    'planting_date',
    'estimated_harvest_date',
    'actual_harvest_date',
    'planting_area_ha',
    'seed_requirement',
    'seed_unit',
    'cultivation_status',
    'target_yield_kg',
    'notes',
])]
class CropCycle extends Model
{
    /** @use HasFactory<CropCycleFactory> */
    use BelongsToOrganization, HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'planting_date' => 'date',
            'estimated_harvest_date' => 'date',
            'actual_harvest_date' => 'date',
            'planting_area_ha' => 'decimal:2',
            'seed_requirement' => 'decimal:2',
            'target_yield_kg' => 'decimal:2',
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

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->whereNotIn('cultivation_status', ['completed', 'failed']);
    }
}
