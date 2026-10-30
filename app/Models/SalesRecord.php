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
    'harvest_record_id',
    'invoice_number',
    'buyer_name',
    'buyer_contact',
    'buyer_type',
    'product_name',
    'quantity_sold_kg',
    'price_per_kg',
    'gross_revenue',
    'distribution_cost',
    'net_revenue',
    'transaction_date',
    'payment_status',
    'notes',
])]
class SalesRecord extends Model
{
    use BelongsToOrganization;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity_sold_kg' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'gross_revenue' => 'decimal:2',
            'distribution_cost' => 'decimal:2',
            'net_revenue' => 'decimal:2',
            'transaction_date' => 'date',
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

    public function harvestRecord(): BelongsTo
    {
        return $this->belongsTo(HarvestRecord::class);
    }
}
