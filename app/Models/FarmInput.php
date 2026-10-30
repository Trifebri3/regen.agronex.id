<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'organization_id',
    'farm_id',
    'crop_cycle_id',
    'input_category',
    'name',
    'brand_or_source',
    'unit',
    'quantity_purchased',
    'quantity_used',
    'unit_price',
    'total_cost',
    'purchase_date',
    'notes',
])]
class FarmInput extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity_purchased' => 'decimal:2',
            'quantity_used' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'purchase_date' => 'date',
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
}
