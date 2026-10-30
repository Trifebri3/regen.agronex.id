<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company().' Agribusiness';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'code' => 'ORG-'.strtoupper(Str::random(5)),
            'industry_type' => 'horticulture_agribusiness',
            'subscription_tier' => 'enterprise',
            'contact_email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'is_active' => true,
            'settings' => ['timezone' => 'Asia/Jakarta', 'currency' => 'IDR'],
        ];
    }
}
