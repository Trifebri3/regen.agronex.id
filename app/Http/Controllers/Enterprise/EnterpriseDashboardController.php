<?php

namespace App\Http\Controllers\Enterprise;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\EnterpriseSummaryResource;
use App\Models\AuditLog;
use App\Models\Device;
use App\Models\Farm;
use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnterpriseDashboardController extends Controller
{
    public function dashboard(Request $request, TenantContext $tenantContext): View
    {
        $organization = $this->resolveTargetOrganization($request, $tenantContext);
        $farms = $this->loadAggregatedFarms($organization->id);

        $totalMappedHa = (float) $farms->sum(fn (Farm $farm): float => (float) ($farm->lands_sum_area_ha ?? 0));
        $totalExpenses = (float) $farms->sum(fn (Farm $farm): float => (float) ($farm->total_expenses ?? 0));
        $totalRevenue = (float) $farms->sum(fn (Farm $farm): float => max(
            (float) ($farm->ledger_revenue ?? 0),
            (float) ($farm->gross_sales_revenue ?? 0),
        ));
        $totalHarvestKg = (float) $farms->sum(fn (Farm $farm): float => (float) ($farm->saleable_harvest_kg ?? 0));
        $liveDevicesCount = (int) $farms->sum(fn (Farm $farm): int => (int) ($farm->live_devices_count ?? 0));
        $demoDevicesCount = (int) $farms->sum(fn (Farm $farm): int => (int) ($farm->demo_devices_count ?? 0));

        $members = User::query()
            ->where('organization_id', $organization->id)
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $devices = Device::forOrganization($organization->id)
            ->with(['farm:id,name,code', 'land:id,name', 'latestReading'])
            ->orderBy('product_line')
            ->orderBy('name')
            ->get();

        $auditLogs = AuditLog::query()
            ->where('organization_id', $organization->id)
            ->with('user:id,name,email,role')
            ->latest('id')
            ->limit(12)
            ->get();

        $availableOrganizations = $request->user()?->role === UserRole::SuperAdmin
            ? Organization::query()->orderBy('name')->get(['id', 'name', 'code', 'subscription_tier'])
            : collect();

        return view('enterprise.dashboard', [
            'organization' => $organization,
            'availableOrganizations' => $availableOrganizations,
            'farms' => $farms,
            'members' => $members,
            'devices' => $devices,
            'auditLogs' => $auditLogs,
            'totalMappedHa' => $totalMappedHa,
            'totalExpenses' => $totalExpenses,
            'totalRevenue' => $totalRevenue,
            'estimatedProfitLoss' => $totalRevenue - $totalExpenses,
            'totalHarvestKg' => $totalHarvestKg,
            'liveDevicesCount' => $liveDevicesCount,
            'demoDevicesCount' => $demoDevicesCount,
        ]);
    }

    public function storeFarm(
        Request $request,
        TenantContext $tenantContext,
        AuditLogger $auditLogger,
    ): RedirectResponse {
        $organization = $this->resolveTargetOrganization($request, $tenantContext);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'location' => ['required', 'string', 'max:200'],
            'province' => ['required', 'string', 'max:120'],
            'total_area_ha' => ['required', 'numeric', 'min:0.1', 'max:100000'],
            'elevation_m' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'manager_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $managerId = null;
        if (! empty($validated['manager_id'])) {
            $manager = User::findOrFail((int) $validated['manager_id']);
            $tenantContext->assertSameOrganization((int) ($manager->organization_id ?? 0), $request->user());
            if ($manager->organization_id !== $organization->id) {
                abort(403, 'Manajer kebun harus berasal dari organisasi yang sama.');
            }
            $managerId = $manager->id;
        }

        $farm = Farm::create([
            'organization_id' => $organization->id,
            'manager_id' => $managerId,
            'name' => $validated['name'],
            'code' => 'FRM-'.strtoupper(Str::random(5)),
            'location' => $validated['location'],
            'province' => $validated['province'],
            'total_area_ha' => $validated['total_area_ha'],
            'elevation_m' => $validated['elevation_m'] ?? null,
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        $auditLogger->record('enterprise.farm_created', $farm, [
            'organization_code' => $organization->code,
            'total_area_ha' => (float) $farm->total_area_ha,
        ]);

        return back()->with('status', "Unit kebun '{$farm->name}' ({$farm->code}) berhasil ditambahkan ke organisasi {$organization->name}.");
    }

    public function exportCsv(Request $request, TenantContext $tenantContext): StreamedResponse
    {
        $organization = $this->resolveTargetOrganization($request, $tenantContext);
        $farms = $this->loadAggregatedFarms($organization->id);

        $filename = 'regen-enterprise-'.$organization->slug.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($organization, $farms): void {
            $out = fopen('php://output', 'wb');
            if ($out === false) {
                return;
            }

            fputcsv($out, ['REGEN Enterprise Multi-Farm Organization Report']);
            fputcsv($out, ['Organization', $organization->name, $organization->code]);
            fputcsv($out, ['Subscription Tier', ucfirst($organization->subscription_tier)]);
            fputcsv($out, ['Generated At', now()->toIso8601String()]);
            fputcsv($out, []);
            fputcsv($out, [
                'Farm Code',
                'Farm Name',
                'Location',
                'Province',
                'Lands Count',
                'Mapped Area (ha)',
                'Active Crop Cycles',
                'Live Devices',
                'Demo Devices',
                'Saleable Harvest (kg)',
                'Recorded Expenses (IDR)',
                'Effective Revenue (IDR)',
                'Estimated Net Profit/Loss (IDR)',
            ]);

            foreach ($farms as $farm) {
                $expenses = round((float) ($farm->total_expenses ?? 0), 2);
                $revenue = round(max((float) ($farm->ledger_revenue ?? 0), (float) ($farm->gross_sales_revenue ?? 0)), 2);

                fputcsv($out, [
                    $farm->code,
                    $farm->name,
                    $farm->location,
                    $farm->province,
                    (int) ($farm->lands_count ?? 0),
                    round((float) ($farm->lands_sum_area_ha ?? 0), 2),
                    (int) ($farm->active_cycles_count ?? 0),
                    (int) ($farm->live_devices_count ?? 0),
                    (int) ($farm->demo_devices_count ?? 0),
                    round((float) ($farm->saleable_harvest_kg ?? 0), 2),
                    $expenses,
                    $revenue,
                    round($revenue - $expenses, 2),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function apiSummary(Request $request, TenantContext $tenantContext): EnterpriseSummaryResource
    {
        $organization = $this->resolveTargetOrganization($request, $tenantContext);
        $farms = $this->loadAggregatedFarms($organization->id);

        return new EnterpriseSummaryResource($organization, $farms);
    }

    private function resolveTargetOrganization(Request $request, TenantContext $tenantContext): Organization
    {
        $requestedOrgId = $request->filled('organization_id')
            ? $request->integer('organization_id')
            : null;

        $organization = $tenantContext->resolveOrganization($request->user(), $requestedOrgId);
        abort_if($organization === null, 403, 'Organisasi enterprise tidak ditemukan untuk akun ini.');

        return $organization;
    }

    /**
     * @return Collection<int, Farm>
     */
    private function loadAggregatedFarms(int $organizationId): Collection
    {
        return Farm::forOrganization($organizationId)
            ->with('manager:id,name,email,role')
            ->withCount([
                'lands',
                'devices',
                'devices as live_devices_count' => fn ($q) => $q->where('is_demo', false),
                'devices as demo_devices_count' => fn ($q) => $q->where('is_demo', true),
                'cropCycles as active_cycles_count' => fn ($q) => $q->active(),
            ])
            ->withSum('lands', 'area_ha')
            ->withSum(['financialRecords as total_expenses' => fn ($q) => $q->where('transaction_type', 'expense')], 'amount')
            ->withSum(['financialRecords as ledger_revenue' => fn ($q) => $q->where('transaction_type', 'revenue')], 'amount')
            ->withSum('sales as gross_sales_revenue', 'gross_revenue')
            ->withSum('harvests as saleable_harvest_kg', 'saleable_quantity_kg')
            ->orderBy('name')
            ->get();
    }
}
