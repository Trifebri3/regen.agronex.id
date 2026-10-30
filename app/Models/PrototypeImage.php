<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'prototype_id',
    'image_path',
    'caption',
    'sort_order',
])]
class PrototypeImage extends Model
{
    public function prototype(): BelongsTo
    {
        return $this->belongsTo(Prototype::class);
    }

    public function resolvedUrl(): string
    {
        if (str_starts_with($this->image_path, 'images/') || str_starts_with($this->image_path, '/images/')) {
            return asset(ltrim($this->image_path, '/'));
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
