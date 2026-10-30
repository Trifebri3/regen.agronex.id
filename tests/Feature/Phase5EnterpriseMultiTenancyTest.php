<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Farm;
use App\Models\Land;
use App\Models\Organization;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5EnterpriseMultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_organization_admin_views_only_own_tenant_on_enterprise_dashboard_csv_export_and_json_api(): void
    {
        $orgAdmin1 = User::query()->where('email', 'orgadmin@agronex-nusantara.id')->firstOrFail();

        $this->actingAs($orgAdmin1)
            ->get(route('enterprise.dashboard'))
            ->assertOk()
            ->assertSee('PT Agronex Nusantara Agro')
            ->assertSee('Kebun Pangalengan Highland')
            ->assertSee('Kebun Lembang Precision Valley')
            ->assertDontSee('Kebun Koperasi Cisurupan Garut')
            ->assertDontSee('Koperasi Tani Mandiri Priangan');

        // Create a new Farm unit under Tenant 1
        $this->actingAs($orgAdmin1)
            ->post(route('enterprise.farms.store'), [
                'name' => 'Kebun Ciwidey Berry Estate',
                'location' => 'Rancabali, Ciwidey',
                'province' => 'Jawa Barat',
                'total_area_ha' => 7.40,
                'elevation_m' => 1550,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('farms', [
            'organization_id' => $orgAdmin1->organization_id,
            'name' => 'Kebun Ciwidey Berry Estate',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'organization_id' => $orgAdmin1->organization_id,
            'user_id' => $orgAdmin1->id,
            'action' => 'enterprise.farm_created',
        ]);

        // Verify CSV export streams strictly Tenant 1 farms
        $csvResponse = $this->actingAs($orgAdmin1)->get(route('enterprise.export'));
        $csvResponse->assertOk();
        $csvContent = $csvResponse->streamedContent();
        $this->assertStringContainsString('Kebun Pangalengan Highland', $csvContent);
        $this->assertStringContainsString('Kebun Ciwidey Berry Estate', $csvContent);
        $this->assertStringNotContainsString('Kebun Koperasi Cisurupan Garut', $csvContent);

        // Verify Enterprise REST JSON API v1
        $this->actingAs($orgAdmin1)
            ->getJson(route('api.v1.enterprise.summary'))
            ->assertOk()
            ->assertJsonPath('data.organization.code', 'ORG-AGX-01')
            ->assertJsonPath('data.metrics.farms_count', 3)
            ->assertJsonPath('data.metrics.devices.demo_simulation', 3);
    }

    public function test_cross_tenant_parameter_tampering_and_cross_tenant_mutations_are_strictly_blocked(): void
    {
        $orgPrimary = Organization::query()->where('slug', 'agronex-nusantara-agro')->firstOrFail();
        $orgAdmin2 = User::query()->where('email', 'orgadmin@priangan-tani.id')->firstOrFail();

        $tenant1Farm = Farm::forOrganization($orgPrimary->id)->firstOrFail();
        $tenant1Land = Land::forOrganization($orgPrimary->id)->firstOrFail();
        $tenant1Device = Device::forOrganization($orgPrimary->id)->firstOrFail();

        // Tenant 2 see only Tenant 2 farm by default
        $this->actingAs($orgAdmin2)
            ->get(route('enterprise.dashboard'))
            ->assertOk()
            ->assertSee('Kebun Koperasi Cisurupan Garut')
            ->assertDontSee('Kebun Pangalengan Highland');

        // Tampering with ?organization_id=1 must be rejected with 403
        $this->actingAs($orgAdmin2)
            ->get(route('enterprise.dashboard', ['organization_id' => $orgPrimary->id]))
            ->assertForbidden();

        $this->actingAs($orgAdmin2)
            ->getJson(route('api.v1.enterprise.summary', ['organization_id' => $orgPrimary->id]))
            ->assertForbidden();

        // Cross-tenant write mutations against Tenant 1 entities must abort with 403
        $this->actingAs($orgAdmin2)
            ->post(route('app.lands.store'), [
                'farm_id' => $tenant1Farm->id,
                'name' => 'Injeksi Lahan Lintas Organisasi',
                'location' => 'Sektor Ilegal',
                'area_ha' => 2.00,
                'ownership_status' => 'owned',
            ])
            ->assertForbidden();

        $this->actingAs($orgAdmin2)
            ->post(route('app.activities.store'), [
                'farm_id' => $tenant1Farm->id,
                'activity_type' => 'daily_log',
                'title' => 'Injeksi Aktivitas Lintas Tenant',
                'activity_date' => '2026-10-30',
            ])
            ->assertForbidden();

        $this->actingAs($orgAdmin2)
            ->post(route('app.devices.readings.store', $tenant1Device), [
                'soil_ph' => 6.10,
                'soil_moisture_pct' => 55.0,
            ])
            ->assertForbidden();

        $this->actingAs($orgAdmin2)
            ->delete(route('app.lands.destroy', $tenant1Land))
            ->assertForbidden();

        $this->assertDatabaseMissing('lands', ['name' => 'Injeksi Lahan Lintas Organisasi']);
    }

    public function test_unauthenticated_and_role_boundaries_are_enforced_across_enterprise_and_farm_portals(): void
    {
        // Unauthenticated checks
        $this->get(route('api.v1.health'))->assertOk()->assertJsonPath('status', 'ok');
        $this->get(route('api.v1.enterprise.summary'))->assertUnauthorized();
        $this->get(route('enterprise.dashboard'))->assertRedirect(route('login'));

        // Farmer role boundaries
        $farmer = User::query()->where('email', 'farmer@agronex-nusantara.id')->firstOrFail();
        $tenant1Farm = Farm::forOrganization($farmer->organization_id)->firstOrFail();

        $this->actingAs($farmer)->get(route('app.dashboard'))->assertOk();
        $this->actingAs($farmer)->get(route('enterprise.dashboard'))->assertForbidden();
        $this->actingAs($farmer)->getJson(route('api.v1.enterprise.summary'))->assertForbidden();
        $this->actingAs($farmer)->get(route('admin.overview'))->assertForbidden();
        $this->actingAs($farmer)->post(route('app.lands.store'), [
            'farm_id' => $tenant1Farm->id,
            'name' => 'Percobaan Buat Lahan oleh Farmer',
            'location' => 'Blok B',
            'area_ha' => 1.0,
            'ownership_status' => 'owned',
        ])->assertForbidden();

        // SuperAdmin can inspect any tenant scope
        $superAdmin = User::query()->where('email', 'superadmin@regen.agronex.id')->firstOrFail();
        $orgSecondary = Organization::query()->where('slug', 'koperasi-tani-priangan')->firstOrFail();

        $this->actingAs($superAdmin)
            ->get(route('enterprise.dashboard', ['organization_id' => $orgSecondary->id]))
            ->assertOk()
            ->assertSee('Koperasi Tani Mandiri Priangan')
            ->assertSee('Kebun Koperasi Cisurupan Garut');
    }
}
