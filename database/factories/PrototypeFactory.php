<?php

namespace Database\Factories;

use App\Enums\PrototypeCategory;
use App\Models\Prototype;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Prototype>
 */
class PrototypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Field Trial '.fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($title).'-'.fake()->numberBetween(100, 999),
            'title' => ucwords($title),
            'category' => PrototypeCategory::SoilSense,
            'description' => fake()->paragraphs(2, true),
            'technical_notes' => 'RS485 Modbus RTU multi-parameter probe calibrated in Andosol soil.',
            'testing_date' => now()->subDays(14)->toDateString(),
            'location' => 'Lembang, Jawa Barat',
            'thumbnail_path' => 'images/regen-soilsense-probe.jpg',
            'video_url' => null,
            'is_featured' => false,
            'publication_status' => 'published',
        ];
    }
}
