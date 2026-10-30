<?php

namespace Tests\Feature;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use App\Enums\PrototypeCategory;
use App\Enums\UserRole;
use App\Models\Prototype;
use App\Models\SupportingDocument;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase2CmsAndRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_prototype_gallery_and_detail_hide_drafts_from_public(): void
    {
        $published = Prototype::factory()->create([
            'title' => 'SoilSense Published Field Test',
            'publication_status' => 'published',
        ]);
        $draft = Prototype::factory()->create([
            'title' => 'Secret Draft Prototype',
            'publication_status' => 'draft',
        ]);

        $this->get('/prototype')
            ->assertOk()
            ->assertSee('SoilSense Published Field Test')
            ->assertDontSee('Secret Draft Prototype');

        $this->get(route('prototype.show', $published))->assertOk();
        $this->get(route('prototype.show', $draft))->assertNotFound();
    }

    public function test_supporting_documents_isolate_private_files_and_enforce_download_permission(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents/public-haki.pdf', '%PDF-1.4 public');
        Storage::disk('local')->put('documents/private-nda.pdf', '%PDF-1.4 secret');

        $publicDoc = SupportingDocument::factory()->create([
            'title' => 'Sertifikat HAKI Publik REGEN',
            'visibility' => 'public',
            'allow_download' => true,
            'disk' => 'local',
            'file_path' => 'documents/public-haki.pdf',
            'original_filename' => 'HAKI-Publik.pdf',
        ]);

        $restrictedPublicDoc = SupportingDocument::factory()->create([
            'title' => 'Metadata Publik Tanpa Unduh',
            'visibility' => 'public',
            'allow_download' => false,
            'disk' => 'local',
            'file_path' => 'documents/public-haki.pdf',
        ]);

        $privateDoc = SupportingDocument::factory()->create([
            'title' => 'Kontrak Internal Rahasia',
            'visibility' => 'private',
            'allow_download' => false,
            'disk' => 'local',
            'file_path' => 'documents/private-nda.pdf',
        ]);

        $this->get('/pendukung')
            ->assertOk()
            ->assertSee('Sertifikat HAKI Publik REGEN')
            ->assertSee('Metadata Publik Tanpa Unduh')
            ->assertDontSee('Kontrak Internal Rahasia');

        $this->get(route('pendukung.download', $publicDoc))->assertOk();
        $this->get(route('pendukung.download', $restrictedPublicDoc))->assertForbidden();
        $this->get(route('pendukung.download', $privateDoc))->assertForbidden();
    }

    public function test_content_admin_can_upload_prototype_and_private_document_but_cannot_access_user_management(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $contentAdmin = User::factory()->role(UserRole::ContentAdmin)->create();

        $this->actingAs($contentAdmin)
            ->post(route('admin.prototypes.store'), [
                'title' => 'WaterSense Drip Valve v2 Trial',
                'category' => PrototypeCategory::WaterSense->value,
                'description' => 'Uji aktuator solenoid latching hemat energi di lahan cabai.',
                'testing_date' => '2026-10-20',
                'location' => 'Garut',
                'publication_status' => 'published',
                'thumbnail' => UploadedFile::fake()->image('thumb.jpg', 800, 600),
                'gallery_images' => [
                    UploadedFile::fake()->image('shot1.png', 800, 600),
                    UploadedFile::fake()->image('shot2.png', 800, 600),
                ],
                'video_url' => 'https://www.youtube.com/embed/abc123xyz',
            ])
            ->assertRedirect();

        $createdProto = Prototype::query()->where('title', 'WaterSense Drip Valve v2 Trial')->firstOrFail();
        $this->assertCount(2, $createdProto->images);

        $this->actingAs($contentAdmin)
            ->post(route('admin.documents.store'), [
                'title' => 'Paten Sensor Tanah Modular',
                'category' => DocumentCategory::Patent->value,
                'document_type' => 'Paten Sederhana',
                'registration_status' => DocumentStatus::InProcess->value,
                'visibility' => 'private',
                'document_file' => UploadedFile::fake()->create('patent-draft.pdf', 256, 'application/pdf'),
            ])
            ->assertRedirect();

        $createdDoc = SupportingDocument::query()->where('title', 'Paten Sensor Tanah Modular')->firstOrFail();
        $this->assertSame('local', $createdDoc->disk);
        Storage::disk('local')->assertExists($createdDoc->file_path);

        // ContentAdmin must be denied User Management & Enterprise Tenant Management
        $this->actingAs($contentAdmin)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($contentAdmin)->get(route('admin.enterprise.index'))->assertForbidden();
    }

    public function test_farmer_is_blocked_from_admin_cms_while_super_admin_accesses_all_13_menus(): void
    {
        $this->seed(DatabaseSeeder::class);

        $farmer = User::query()->where('email', 'farmer@agronex-nusantara.id')->firstOrFail();
        $this->actingAs($farmer)->get(route('admin.overview'))->assertForbidden();

        $superAdmin = User::query()->where('email', 'superadmin@regen.agronex.id')->firstOrFail();

        $adminRoutes = [
            'admin.overview',
            'admin.website-content',
            'admin.products.index',
            'admin.prototypes.index',
            'admin.documents.index',
            'admin.media.index',
            'admin.users.index',
            'admin.farms.index',
            'admin.devices.index',
            'admin.enterprise.index',
            'admin.contacts.index',
            'admin.reports.index',
            'admin.settings.index',
        ];

        foreach ($adminRoutes as $routeName) {
            $this->actingAs($superAdmin)->get(route($routeName))->assertOk();
        }
    }
}
