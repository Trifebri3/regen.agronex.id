<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function users(): View
    {
        return view('admin.users', [
            'users' => User::query()->with('organization')->orderBy('role')->orderBy('name')->paginate(20),
            'organizations' => Organization::query()->orderBy('name')->get(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function storeUser(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180', Rule::unique('users', 'email')],
            'role' => ['required', Rule::enum(UserRole::class)],
            'organization_id' => ['nullable', 'integer', Rule::exists('organizations', 'id')],
            'job_title' => ['nullable', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'max:120'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'organization_id' => $validated['organization_id'] ?? null,
            'job_title' => $validated['job_title'] ?? null,
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => $validated['password'],
        ]);

        $auditLogger->record('admin.user_created', $user, [
            'assigned_role' => $user->role->value,
        ]);

        return back()->with('status', "Pengguna {$user->name} ({$user->role->label()}) berhasil dibuat.");
    }

    public function updateUser(Request $request, User $user, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
            'organization_id' => ['nullable', 'integer', Rule::exists('organizations', 'id')],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'role' => $validated['role'],
            'organization_id' => $validated['organization_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $auditLogger->record('admin.user_updated', $user, [
            'role' => $user->role->value,
            'is_active' => $user->is_active,
        ]);

        return back()->with('status', "Hak akses {$user->name} berhasil diperbarui.");
    }

    public function organizations(): View
    {
        $organizations = Organization::query()
            ->withCount(['users', 'farms', 'lands', 'devices'])
            ->orderBy('name')
            ->get();

        return view('admin.enterprise', [
            'organizations' => $organizations,
        ]);
    }

    public function storeOrganization(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'code' => ['required', 'string', 'max:30', Rule::unique('organizations', 'code')],
            'industry_type' => ['required', 'string', 'max:120'],
            'subscription_tier' => ['required', 'string', Rule::in(['modular', 'integrated', 'enterprise'])],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $slug = Str::slug($validated['name']).'-'.strtolower(Str::random(4));

        $org = Organization::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'code' => strtoupper($validated['code']),
            'industry_type' => $validated['industry_type'],
            'subscription_tier' => $validated['subscription_tier'],
            'contact_email' => $validated['contact_email'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
            'settings' => ['timezone' => 'Asia/Jakarta', 'currency' => 'IDR'],
        ]);

        $auditLogger->record('admin.organization_created', $org);

        return back()->with('status', "Organisasi Enterprise '{$org->name}' berhasil didaftarkan.");
    }
}
