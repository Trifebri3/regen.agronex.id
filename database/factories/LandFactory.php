<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Land;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Land>
 */
class LandFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => fn (array $attributes) => Farm::find($attributes['farm_id'])?->organization_id ?? Farm::factory()->create()->organization_id,
            'farm_id' => Farm::factory(),
            'name' => 'Blok '.strtoupper(Str::random(2)),
            'code' => 'LND-'.strtoupper(Str::random(5)),
            'location' => 'Sektor Utara',
            'area_ha' => fake()->randomFloat(2, 1, 20),
            'ownership_status' => 'owned',
            'soil_type' => 'Andosol Vulkanik',
            'soil_ph' => 6.40,
            'soil_profile_notes' => 'Gembur, bahan organik tinggi, drainase baik.',
            'status' => 'active',
        ];
    }
}
