<?php

namespace Tests\Unit;

use App\Services\FinancialCalculatorService;
use PHPUnit\Framework\TestCase;

class FinancialCalculatorServiceTest extends TestCase
{
    public function test_calculates_production_cost_revenue_profit_hpp_and_bep_accurately(): void
    {
        $service = new FinancialCalculatorService;

        $result = $service->calculate([
            'land_lease_fixed' => 5000000,
            'equipment_fixed' => 5000000,
            'other_fixed' => 0,
            'land_preparation_cost' => 10000000,
            'input_cost' => 15000000,
            'labor_cost' => 10000000,
            'operational_cost' => 3000000,
            'transportation_cost' => 2000000,
            'harvested_quantity' => 12500,
            'saleable_quantity' => 10000,
            'quantity_sold' => 10000,
            'selling_price_per_unit' => 9000,
            'hpp_basis' => 'saleable',
        ]);

        // Fixed = 10,000,000 | Variable = 40,000,000 | Total = 50,000,000
        $this->assertSame(10000000.0, $result['fixed_costs']);
        $this->assertSame(40000000.0, $result['variable_costs']);
        $this->assertSame(50000000.0, $result['total_production_cost']);
        $this->assertSame(90000000.0, $result['revenue']);
        $this->assertSame(40000000.0, $result['profit_loss']);
        $this->assertTrue($result['is_profitable']);

        // HPP on saleable (50,000,000 / 10,000) = 5,000
        $this->assertSame(5000.0, $result['hpp_per_unit']);

        // Variable/unit = 40,000,000 / 10,000 = 4,000 | Margin = 9,000 - 4,000 = 5,000
        // BEP Qty = 10,000,000 / 5,000 = 2,000 kg
        $this->assertSame(2000.0, $result['break_even_quantity']);
        $this->assertSame(18000000.0, $result['break_even_revenue']);
        $this->assertStringContainsString('bukan jaminan keuntungan pasti', $result['disclaimer']);

        // Switch basis to harvested (50,000,000 / 12,500) = 4,000
        $harvestedBasis = $service->calculate([
            'land_lease_fixed' => 10000000,
            'input_cost' => 40000000,
            'harvested_quantity' => 12500,
            'saleable_quantity' => 10000,
            'quantity_sold' => 10000,
            'selling_price_per_unit' => 9000,
            'hpp_basis' => 'harvested',
        ]);
        $this->assertSame(4000.0, $harvestedBasis['hpp_per_unit']);
    }

    public function test_handles_zero_missing_negative_and_invalid_inputs_safely_without_division_by_zero(): void
    {
        $service = new FinancialCalculatorService;

        $result = $service->calculate([
            'land_lease_fixed' => -50000,
            'input_cost' => 'invalid_text',
            'harvested_quantity' => 0,
            'saleable_quantity' => 0,
            'quantity_sold' => null,
            'selling_price_per_unit' => 0,
        ]);

        $this->assertSame(0.0, $result['total_production_cost']);
        $this->assertSame(0.0, $result['revenue']);
        $this->assertNull($result['hpp_per_unit']);
        $this->assertNull($result['break_even_quantity']);
        $this->assertNotEmpty($result['warnings']);
    }
}
