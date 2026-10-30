<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'REGEN '.ucfirst(fake()->unique()->word()).'Sense';

        return [
            'slug' => Str::slug($name),
            'code' => 'RGN-'.strtoupper(Str::random(4)),
            'name' => $name,
            'tagline' => fake()->sentence(6),
            'category' => 'hardware_iot',
            'deployment_modes' => ['modular', 'integrated'],
            'readiness_status' => 'pilot_demo',
            'short_description' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'features' => ['Precision sensor telemetry', 'Solar-ready field enclosure', 'Modular deployment'],
            'specifications' => ['Power' => 'Solar + LiFePO4', 'Connectivity' => 'LoRaWAN / 4G LTE'],
            'metrics' => ['Interval' => '15 min', 'Protection' => 'IP67'],
            'hero_image' => 'images/regen-soilsense-probe.jpg',
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 1,
        ];
    }
}
