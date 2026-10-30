<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\Advisory;
use App\Models\Device;
use App\Models\Farm;
use App\Models\Land;
use App\Models\SensorReading;
use App\Services\AuditLogger;
use App\Services\RuleBasedAdvisoryService;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmDeviceController extends Controller
{
    public function index(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        $devices = Device::forOrganization($orgId)
            ->with(['farm', 'land', 'readings' => fn ($q) => $q->limit(5)])
            ->orderBy('product_line')
            ->orderBy('name')
            ->get();

        $advisories = Advisory::forOrganization($orgId)
            ->with(['farm', 'device'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('farm.devices', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'lands' => Land::forOrganization($orgId)->orderBy('name')->get(),
            'devices' => $devices,
            'advisories' => $advisories,
        ]);
    }

    public function store(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'land_id' => ['nullable', 'integer'],
            'device_uid' => ['required', 'string', 'max:60', Rule::unique('devices', 'device_uid')],
            'name' => ['required', 'string', 'max:160'],
            'product_line' => ['required', 'string', Rule::in(['soilsense', 'watersense', 'envirosense', 'terra'])],
            'connection_mode' => ['required', 'string', Rule::in(['demo_simulation', 'live_mqtt', 'live_http'])],
            'firmware_version' => ['nullable', 'string', 'max:30'],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $landId = null;
        if (! empty($validated['land_id'])) {
            $land = Land::findOrFail((int) $validated['land_id']);
            $tenantContext->assertSameOrganization((int) $land->organization_id, $request->user());
            $landId = $land->id;
        }

        $isDemo = $validated['connection_mode'] === 'demo_simulation';

        $device = Device::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'land_id' => $landId,
            'device_uid' => strtoupper($validated['device_uid']),
            'name' => $validated['name'],
            'product_line' => $validated['product_line'],
            'firmware_version' => ($validated['firmware_version'] ?? null) ?: 'v1.4.2',
            'connection_mode' => $validated['connection_mode'],
            'is_demo' => $isDemo,
            'status' => 'online',
            'battery_level' => 100,
            'signal_rssi' => -70,
            'last_seen_at' => now(),
        ]);

        $auditLogger->record('device.registered', $device, [
            'is_demo' => $isDemo,
            'product_line' => $device->product_line,
        ]);

        return back()->with('status', "Perangkat {$device->device_uid} berhasil didaftarkan (".($isDemo ? 'Mode Demo Simulasi' : 'Mode Perangkat Live').').');
    }

    public function storeReading(
        Request $request,
        Device $device,
        TenantContext $tenantContext,
        RuleBasedAdvisoryService $advisoryService,
        AuditLogger $auditLogger
    ): RedirectResponse {
        $tenantContext->assertSameOrganization((int) $device->organization_id, $request->user());

        $validated = $request->validate([
            'soil_ph' => ['nullable', 'numeric', 'min:2.0', 'max:11.0'],
            'soil_moisture_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'soil_ec_ds_m' => ['nullable', 'numeric', 'min:0', 'max:30'],
            'nitrogen_mg_kg' => ['nullable', 'numeric', 'min:0'],
            'phosphorus_mg_kg' => ['nullable', 'numeric', 'min:0'],
            'potassium_mg_kg' => ['nullable', 'numeric', 'min:0'],
            'water_flow_lpm' => ['nullable', 'numeric', 'min:0'],
            'water_level_cm' => ['nullable', 'numeric', 'min:0'],
            'ambient_temp_c' => ['nullable', 'numeric', 'min:-20', 'max:70'],
            'humidity_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rainfall_mm' => ['nullable', 'numeric', 'min:0'],
        ]);

        $reading = SensorReading::create(array_merge($validated, [
            'organization_id' => $device->organization_id,
            'device_id' => $device->id,
            'is_demo' => $device->is_demo,
            'recorded_at' => now(),
        ]));

        $device->update(['last_seen_at' => now(), 'status' => 'online']);

        $advisories = $advisoryService->evaluateReading($device, $reading);

        $auditLogger->record('device.reading_ingested', $reading, [
            'device_uid' => $device->device_uid,
            'is_demo' => $reading->is_demo,
            'advisories_triggered' => count($advisories),
        ]);

        return back()->with(
            'status',
            'Data pembacaan sensor berhasil direkam ('.($reading->is_demo ? 'Demo Simulasi' : 'Live Telemetry').')'.(count($advisories) > 0 ? ' dan memicu '.count($advisories).' rekomendasi agronomi.' : '.')
        );
    }
}
