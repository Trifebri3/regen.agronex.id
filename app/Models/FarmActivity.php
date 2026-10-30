<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'organization_id',
    'farm_id',
    'land_id',
    'crop_cycle_id',
    'recorded_by',
    'activity_type',
    'title',
    'activity_date',
    'labor_count',
    'labor_hours',
    'water_volume_liters',
    'cost_incurred',
    'status',
    'notes',
])]
class FarmActivity extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'labor_count' => 'integer',
            'labor_hours' => 'decimal:2',
            'water_volume_liters' => 'decimal:2',
            'cost_incurred' => 'decimal:2',
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

    public function cropCycle(): BelongsTo
    {
        return $this->belongsTo(CropCycle::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
