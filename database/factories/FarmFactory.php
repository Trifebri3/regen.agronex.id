<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Farm>
 */
class FarmFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => 'Kebun '.ucfirst(fake()->word()).' Highland',
            'code' => 'FRM-'.strtoupper(Str::random(5)),
            'location' => 'Pangalengan, Kabupaten Bandung',
            'province' => 'Jawa Barat',
            'latitude' => -7.1778000,
            'longitude' => 107.5724000,
            'total_area_ha' => fake()->randomFloat(2, 5, 120),
            'elevation_m' => 1350,
            'status' => 'active',
            'notes' => 'Lahan produksi hortikultura presisi.',
        ];
    }
}
