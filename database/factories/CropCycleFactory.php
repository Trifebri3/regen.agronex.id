<?php

namespace Database\Factories;

use App\Models\CropCycle;
use App\Models\Land;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CropCycle>
 */
class CropCycleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'land_id' => Land::factory(),
            'farm_id' => fn (array $attributes) => Land::findOrFail($attributes['land_id'])->farm_id,
            'organization_id' => fn (array $attributes) => Land::findOrFail($attributes['land_id'])->organization_id,
            'crop_name' => 'Cabai Merah Besar',
            'variety' => 'Pilar F1',
            'cycle_code' => 'CYC-'.strtoupper(Str::random(5)),
            'planting_date' => now()->subDays(45)->toDateString(),
            'estimated_harvest_date' => now()->addDays(45)->toDateString(),
            'planting_area_ha' => 2.50,
            'seed_requirement' => 0.75,
            'seed_unit' => 'kg',
            'cultivation_status' => 'vegetative',
            'target_yield_kg' => 30000,
        ];
    }
}
