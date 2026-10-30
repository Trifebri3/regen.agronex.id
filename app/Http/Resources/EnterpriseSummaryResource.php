<?php

namespace App\Http\Resources;

use App\Models\Farm;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property-read Organization $resource
 */
class EnterpriseSummaryResource extends JsonResource
{
    /**
     * @param  Collection<int, Farm>  $farms
     */
    public function __construct(
        Organization $resource,
        private readonly Collection $farms,
    ) {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalMappedHa = round((float) $this->farms->sum(fn (Farm $farm): float => (float) ($farm->lands_sum_area_ha ?? 0)), 2);
        $totalExpenses = round((float) $this->farms->sum(fn (Farm $farm): float => (float) ($farm->total_expenses ?? 0)), 2);
        $totalRevenue = round((float) $this->farms->sum(fn (Farm $farm): float => max(
            (float) ($farm->ledger_revenue ?? 0),
            (float) ($farm->gross_sales_revenue ?? 0),
        )), 2);
        $liveDevices = (int) $this->farms->sum(fn (Farm $farm): int => (int) ($farm->live_devices_count ?? 0));
        $demoDevices = (int) $this->farms->sum(fn (Farm $farm): int => (int) ($farm->demo_devices_count ?? 0));

        return [
            'platform' => 'REGEN Enterprise Suite',
            'api_version' => 'v1',
            'generated_at' => now()->toIso8601String(),
            'organization' => [
                'id' => $this->resource->id,
                'code' => $this->resource->code,
                'name' => $this->resource->name,
                'slug' => $this->resource->slug,
                'industry_type' => $this->resource->industry_type,
                'subscription_tier' => $this->resource->subscription_tier,
            ],
            'metrics' => [
                'farms_count' => $this->farms->count(),
                'lands_count' => (int) $this->farms->sum('lands_count'),
                'mapped_area_ha' => $totalMappedHa,
                'active_crop_cycles_count' => (int) $this->farms->sum('active_cycles_count'),
                'saleable_harvest_kg' => round((float) $this->farms->sum(fn (Farm $farm): float => (float) ($farm->saleable_harvest_kg ?? 0)), 2),
                'devices' => [
                    'total' => $liveDevices + $demoDevices,
                    'live_operational' => $liveDevices,
                    'demo_simulation' => $demoDevices,
                ],
                'financials_idr' => [
                    'total_expenses' => $totalExpenses,
                    'effective_revenue' => $totalRevenue,
                    'estimated_profit_loss' => round($totalRevenue - $totalExpenses, 2),
                    'disclaimer' => 'Estimasi kinerja finansial berdasarkan buku besar organisasi; bukan jaminan keuntungan.',
                ],
            ],
            'farms' => $this->farms->map(function (Farm $farm): array {
                $expenses = round((float) ($farm->total_expenses ?? 0), 2);
                $revenue = round(max((float) ($farm->ledger_revenue ?? 0), (float) ($farm->gross_sales_revenue ?? 0)), 2);

                return [
                    'id' => $farm->id,
                    'code' => $farm->code,
                    'name' => $farm->name,
                    'location' => $farm->location,
                    'province' => $farm->province,
                    'status' => $farm->status,
                    'lands_count' => (int) ($farm->lands_count ?? 0),
                    'mapped_area_ha' => round((float) ($farm->lands_sum_area_ha ?? 0), 2),
                    'active_cycles_count' => (int) ($farm->active_cycles_count ?? 0),
                    'devices_live' => (int) ($farm->live_devices_count ?? 0),
                    'devices_demo' => (int) ($farm->demo_devices_count ?? 0),
                    'expenses_idr' => $expenses,
                    'effective_revenue_idr' => $revenue,
                    'estimated_profit_loss_idr' => round($revenue - $expenses, 2),
                ];
            })->values()->all(),
        ];
    }
}
