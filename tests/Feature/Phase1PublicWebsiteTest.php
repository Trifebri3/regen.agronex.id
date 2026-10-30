<?php

namespace Tests\Feature;

use Database\Seeders\LearningCenterSeeder;
use Database\Seeders\RegenCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase1PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RegenCatalogSeeder::class,
            LearningCenterSeeder::class,
        ]);
    }

    public function test_homepage_renders_hero_ctas_ecosystem_and_agronex_attribution(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Grow Smarter. Manage Better. Regenerate the Future.')
            ->assertSee('An Integrated Smart Agriculture')
            ->assertSee('Explore Solutions')
            ->assertSee('Explore Platform')
            ->assertSee('Request Enterprise Demo')
            ->assertSee('https://agronex.id', false)
            ->assertSee('REGEN SoilSense')
            ->assertSee('REGEN Enterprise')
            ->assertSee('Demo Data / Simulasi');
    }

    public function test_all_phase_1_public_solution_and_marketing_routes_work(): void
    {
        $routes = [
            '/solutions' => 'REGEN Product Solutions',
            '/solutions/soilsense' => 'REGEN SoilSense',
            '/solutions/watersense' => 'REGEN WaterSense',
            '/solutions/envirosense' => 'REGEN EnviroSense',
            '/solutions/terra' => 'REGEN Terra',
            '/platform' => 'REGEN Farm Management',
            '/enterprise' => 'REGEN Enterprise Agriculture Platform',
            '/about' => 'Produk Unggulan Mandiri dari Ekosistem AGRONEX',
            '/contact' => 'Formulir Permintaan Demo',
            '/login' => 'Masuk ke Ekosistem REGEN',
        ];

        foreach ($routes as $uri => $expectedText) {
            $this->get($uri)
                ->assertOk()
                ->assertSee($expectedText);
        }
    }

    public function test_contact_request_validates_and_persists_demo_inquiry(): void
    {
        $this->post('/contact', [
            'request_type' => 'invalid_type',
            'full_name' => '',
            'email' => 'not-an-email',
            'message' => 'short',
        ])->assertSessionHasErrors(['request_type', 'full_name', 'email', 'message']);

        $response = $this->post('/contact', [
            'request_type' => 'enterprise_demo',
            'full_name' => 'Dr. Hendra Kusuma',
            'email' => 'hendra@agromitra.id',
            'phone' => '081234567890',
            'organization_name' => 'PT Agro Mitra Jaya',
            'role_title' => 'VP Agronomy',
            'interested_modules' => ['soilsense', 'enterprise'],
            'land_scale' => '> 250 Ha',
            'message' => 'Kami tertarik mengimplementasikan REGEN Enterprise pada 3 kebun kami di Jawa Barat.',
        ]);

        $response->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_requests', [
            'request_type' => 'enterprise_demo',
            'email' => 'hendra@agromitra.id',
            'organization_name' => 'PT Agro Mitra Jaya',
            'status' => 'new',
        ]);
    }
}
