<?php

namespace Database\Factories;

use App\Models\ContactRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactRequest>
 */
class ContactRequestFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_type' => 'enterprise_demo',
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'organization_name' => fake()->company(),
            'role_title' => 'Head of Agronomy',
            'interested_modules' => ['soilsense', 'farm-management', 'enterprise'],
            'land_scale' => '50 - 250 Ha',
            'message' => fake()->paragraph(),
            'status' => 'new',
        ];
    }
}
