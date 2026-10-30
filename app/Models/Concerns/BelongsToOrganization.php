<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    #[Scope]
    protected function forOrganization(Builder $query, ?int $organizationId): Builder
    {
        if ($organizationId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($this->qualifyColumn('organization_id'), $organizationId);
    }
}
