<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'organization_id',
    'uploaded_by',
    'title',
    'disk',
    'file_path',
    'original_filename',
    'mime_type',
    'file_size',
    'collection',
    'alt_text',
])]
class MediaItem extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function publicUrl(): ?string
    {
        if ($this->disk !== 'public') {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }
}
