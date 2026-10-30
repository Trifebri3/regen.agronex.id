<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\User;

class TenantContext
{
    public function resolveOrganization(User $user, ?int $requestedOrganizationId = null): ?Organization
    {
        if ($user->role === UserRole::SuperAdmin) {
            if ($requestedOrganizationId !== null) {
                return Organization::findOrFail($requestedOrganizationId);
            }

            if ($user->organization_id !== null) {
                return $user->organization;
            }

            return Organization::query()->where('is_active', true)->orderBy('id')->first();
        }

        if ($requestedOrganizationId !== null && $requestedOrganizationId !== $user->organization_id) {
            abort(403, 'Akses ke data organisasi lain tidak diizinkan.');
        }

        if ($user->organization_id !== null) {
            return $user->organization;
        }

        if ($user->role === UserRole::TechnicalAdmin) {
            return Organization::query()->where('is_active', true)->orderBy('id')->first();
        }

        return null;
    }

    public function assertSameOrganization(int $resourceOrganizationId, User $user): void
    {
        if ($user->role === UserRole::SuperAdmin) {
            return;
        }

        if ($user->organization_id === null || $user->organization_id !== $resourceOrganizationId) {
            abort(403, 'Isolasi Multi-Tenant: Anda tidak memiliki izin mengakses data organisasi lain.');
        }
    }
}
