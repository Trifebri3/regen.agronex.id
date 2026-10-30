<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organization_id',
    'farm_id',
    'crop_cycle_id',
    'harvest_date',
    'total_quantity_kg',
    'product_grade',
    'quality_notes',
    'saleable_quantity_kg',
    'sold_quantity_kg',
    'unsold_quantity_kg',
    'post_harvest_loss_kg',
    'recorded_by',
])]
class HarvestRecord extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harvest_date' => 'date',
            'total_quantity_kg' => 'decimal:2',
            'saleable_quantity_kg' => 'decimal:2',
            'sold_quantity_kg' => 'decimal:2',
            'unsold_quantity_kg' => 'decimal:2',
            'post_harvest_loss_kg' => 'decimal:2',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function cropCycle(): BelongsTo
    {
        return $this->belongsTo(CropCycle::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(SalesRecord::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
