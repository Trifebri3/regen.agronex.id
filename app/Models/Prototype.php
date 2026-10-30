<?php

namespace App\Models;

use App\Enums\PrototypeCategory;
use Database\Factories\PrototypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'slug',
    'title',
    'category',
    'description',
    'technical_notes',
    'testing_date',
    'location',
    'thumbnail_path',
    'video_path',
    'video_url',
    'pdf_path',
    'is_featured',
    'publication_status',
    'created_by',
])]
class Prototype extends Model
{
    /** @use HasFactory<PrototypeFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => PrototypeCategory::class,
            'testing_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(PrototypeImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('publication_status', 'published');
    }

    public function resolvedThumbnailUrl(): string
    {
        if (! $this->thumbnail_path) {
            return asset('images/regen-soilsense-probe.jpg');
        }

        if (str_starts_with($this->thumbnail_path, 'images/') || str_starts_with($this->thumbnail_path, '/images/')) {
            return asset(ltrim($this->thumbnail_path, '/'));
        }

        return Storage::disk('public')->url($this->thumbnail_path);
    }
}
