<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogger
{
    public function __construct(private ?Request $request = null) {}

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function record(
        string $action,
        ?Model $auditable = null,
        array $metadata = [],
        ?User $actor = null,
        ?int $organizationId = null
    ): AuditLog {
        $resolvedActor = $actor ?? auth()->user();
        $resolvedOrgId = $organizationId
            ?? ($auditable !== null && isset($auditable->organization_id) ? (int) $auditable->organization_id : null)
            ?? $resolvedActor?->organization_id;

        return AuditLog::create([
            'organization_id' => $resolvedOrgId,
            'user_id' => $resolvedActor?->id,
            'action' => $action,
            'auditable_type' => $auditable !== null ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'ip_address' => $this->request?->ip(),
            'user_agent' => $this->request?->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
