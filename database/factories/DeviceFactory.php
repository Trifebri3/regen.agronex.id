<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farm_id' => Farm::factory(),
            'organization_id' => fn (array $attributes) => Farm::findOrFail($attributes['farm_id'])->organization_id,
            'device_uid' => 'RGN-SS-'.strtoupper(Str::random(6)),
            'name' => 'SoilSense Probe Node',
            'product_line' => 'soilsense',
            'firmware_version' => 'v1.4.2',
            'connection_mode' => 'demo_simulation',
            'is_demo' => true,
            'status' => 'online',
            'battery_level' => 92,
            'signal_rssi' => -74,
            'last_seen_at' => now(),
        ];
    }
}
