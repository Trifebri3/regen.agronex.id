<?php

namespace Database\Factories;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use App\Models\SupportingDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SupportingDocument>
 */
class SupportingDocumentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Dokumen '.fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($title).'-'.fake()->numberBetween(100, 999),
            'title' => ucwords($title),
            'category' => DocumentCategory::Haki,
            'document_type' => 'Sertifikat Hak Cipta',
            'description' => fake()->paragraph(),
            'document_number' => 'EC002026'.fake()->numerify('#####'),
            'issuing_institution' => 'DJKI Kementerian Hukum RI',
            'issue_date' => now()->subMonths(2)->toDateString(),
            'expiration_date' => null,
            'registration_status' => DocumentStatus::Registered,
            'disk' => 'local',
            'file_path' => null,
            'visibility' => 'public',
            'allow_download' => true,
        ];
    }
}
