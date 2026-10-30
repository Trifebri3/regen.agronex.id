<?php

namespace App\Models;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use Database\Factories\SupportingDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'slug',
    'title',
    'category',
    'document_type',
    'description',
    'document_number',
    'issuing_institution',
    'issue_date',
    'expiration_date',
    'registration_status',
    'disk',
    'file_path',
    'original_filename',
    'mime_type',
    'file_size',
    'visibility',
    'allow_download',
    'created_by',
])]
class SupportingDocument extends Model
{
    /** @use HasFactory<SupportingDocumentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => DocumentCategory::class,
            'registration_status' => DocumentStatus::class,
            'issue_date' => 'date',
            'expiration_date' => 'date',
            'allow_download' => 'boolean',
            'file_size' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    #[Scope]
    protected function publiclyVisible(Builder $query): Builder
    {
        return $query->where('visibility', 'public')
            ->where('registration_status', '!=', DocumentStatus::Draft->value);
    }

    public function isPubliclyDownloadable(): bool
    {
        return $this->visibility === 'public'
            && $this->registration_status !== DocumentStatus::Draft
            && $this->allow_download
            && ! empty($this->file_path);
    }
}
