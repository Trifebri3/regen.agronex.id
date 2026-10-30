<?php

namespace Tests\Feature;

use App\Models\Advisory;
use App\Models\Device;
use App\Models\Farm;
use App\Models\Land;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3And4FarmAndDeviceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_farm_manager_can_record_lands_plots_crops_activities_inputs_harvests_sales_and_finances(): void
    {
        $manager = User::query()->where('email', 'manager@agronex-nusantara.id')->firstOrFail();
        $farm = Farm::forOrganization($manager->organization_id)->firstOrFail();

        $this->actingAs($manager)->get(route('app.dashboard'))->assertOk()->assertSee($farm->name);

        // Create Land & Plot
        $this->actingAs($manager)->post(route('app.lands.store'), [
            'farm_id' => $farm->id,
            'name' => 'Blok Uji Agronomi Timur',
            'location' => 'Sektor Timur',
            'area_ha' => 3.50,
            'ownership_status' => 'owned',
            'soil_type' => 'Andosol',
            'soil_ph' => 6.45,
        ])->assertRedirect();

        $land = Land::query()->where('name', 'Blok Uji Agronomi Timur')->firstOrFail();

        $this->actingAs($manager)->post(route('app.lands.plots.store', $land), [
            'plot_code' => 'PLT-E1',
            'name' => 'Petak Timur 1',
            'area_ha' => 1.75,
            'irrigation_type' => 'Drip',
        ])->assertRedirect();

        $this->assertDatabaseHas('land_plots', ['land_id' => $land->id, 'plot_code' => 'PLT-E1']);

        // Create Crop Cycle
        $this->actingAs($manager)->post(route('app.crops.store'), [
            'land_id' => $land->id,
            'crop_name' => 'Bawang Merah',
            'variety' => 'Bima Brebes',
            'planting_date' => '2026-10-01',
            'estimated_harvest_date' => '2026-12-05',
            'planting_area_ha' => 1.75,
            'seed_requirement' => 1400,
            'seed_unit' => 'kg',
            'cultivation_status' => 'planted',
        ])->assertRedirect();

        $crop = $land->cropCycles()->firstOrFail();

        // Log Activity, Input, Harvest, Sale, Finance
        $this->actingAs($manager)->post(route('app.activities.store'), [
            'farm_id' => $farm->id,
            'crop_cycle_id' => $crop->id,
            'activity_type' => 'irrigation',
            'title' => 'Penyiraman Pagi Petak Timur',
            'activity_date' => '2026-10-29',
            'labor_count' => 3,
            'water_volume_liters' => 4500,
            'cost_incurred' => 250000,
        ])->assertRedirect();

        $this->actingAs($manager)->post(route('app.inputs.store'), [
            'farm_id' => $farm->id,
            'crop_cycle_id' => $crop->id,
            'input_category' => 'fertilizers',
            'name' => 'Pupuk NPK 15-15-15',
            'unit' => 'kg',
            'quantity_purchased' => 200,
            'quantity_used' => 100,
            'unit_price' => 16000,
        ])->assertRedirect();

        $this->actingAs($manager)->post(route('app.harvests.store'), [
            'crop_cycle_id' => $crop->id,
            'harvest_date' => '2026-12-05',
            'total_quantity_kg' => 15000,
            'product_grade' => 'Grade A',
            'saleable_quantity_kg' => 14400,
            'sold_quantity_kg' => 14000,
        ])->assertRedirect();

        $this->actingAs($manager)->post(route('app.sales.store'), [
            'farm_id' => $farm->id,
            'crop_cycle_id' => $crop->id,
            'buyer_name' => 'Koperasi Distribusi Pangan',
            'buyer_type' => 'cooperative',
            'product_name' => 'Bawang Merah Grade A',
            'quantity_sold_kg' => 14000,
            'price_per_kg' => 22000,
            'distribution_cost' => 2500000,
            'transaction_date' => '2026-12-06',
            'payment_status' => 'paid',
        ])->assertRedirect();

        $this->actingAs($manager)->post(route('app.finance.store'), [
            'farm_id' => $farm->id,
            'crop_cycle_id' => $crop->id,
            'transaction_type' => 'expense',
            'category' => 'labor_cost',
            'cost_nature' => 'variable',
            'title' => 'Upah Panen Bawang Merah',
            'amount' => 8500000,
            'transaction_date' => '2026-12-05',
        ])->assertRedirect();

        // Business Intelligence Calculator & CSV Export + Learning Center
        $this->actingAs($manager)->post(route('app.intelligence.calculate'), [
            'land_lease_fixed' => 8000000,
            'input_cost' => 32000000,
            'harvested_quantity' => 10000,
            'saleable_quantity' => 8000,
            'quantity_sold' => 8000,
            'selling_price_per_unit' => 10000,
            'hpp_basis' => 'saleable',
        ])->assertOk()->assertSee('Rp 5.000 / kg');

        $this->actingAs($manager)->get(route('app.intelligence.export'))->assertOk();
        $this->actingAs($manager)->get(route('app.learning.index'))->assertOk()->assertSee('Persiapan Lahan');
    }

    public function test_device_registration_telemetry_ingestion_and_rule_based_advisory_generation(): void
    {
        $manager = User::query()->where('email', 'manager@agronex-nusantara.id')->firstOrFail();
        $farm = Farm::forOrganization($manager->organization_id)->firstOrFail();

        $this->actingAs($manager)->post(route('app.devices.store'), [
            'farm_id' => $farm->id,
            'device_uid' => 'RGN-SS-TEST-99',
            'name' => 'SoilSense Probe Test Unit',
            'product_line' => 'soilsense',
            'connection_mode' => 'demo_simulation',
        ])->assertRedirect();

        $device = Device::query()->where('device_uid', 'RGN-SS-TEST-99')->firstOrFail();
        $this->assertTrue($device->is_demo);

        // Ingest acidic soil pH (4.9) + low root moisture (26.5%) -> triggers 2 rule-based advisories
        $this->actingAs($manager)->post(route('app.devices.readings.store', $device), [
            'soil_ph' => 4.90,
            'soil_moisture_pct' => 26.5,
            'soil_ec_ds_m' => 1.35,
        ])->assertRedirect();

        $this->assertSame(
            2,
            Advisory::query()->where('device_id', $device->id)->count()
        );

        $this->actingAs($manager)
            ->get(route('app.devices.index'))
            ->assertOk()
            ->assertSee('Demo Simulation Data')
            ->assertSee('pH Tanah Terlalu Asam');
    }
}
