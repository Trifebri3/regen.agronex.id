<?php

namespace App\Models;

use Database\Factories\ContactRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'request_type',
    'full_name',
    'email',
    'phone',
    'organization_name',
    'role_title',
    'interested_modules',
    'land_scale',
    'message',
    'status',
    'admin_notes',
    'handled_by',
])]
class ContactRequest extends Model
{
    /** @use HasFactory<ContactRequestFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'interested_modules' => 'array',
        ];
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
