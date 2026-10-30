<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\Advisory;
use App\Models\CropCycle;
use App\Models\Device;
use App\Models\Farm;
use App\Models\FarmActivity;
use App\Models\FinancialRecord;
use App\Models\HarvestRecord;
use App\Models\Land;
use App\Models\LearningArticle;
use App\Models\SalesRecord;
use App\Services\FinancialCalculatorService;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FarmDashboardController extends Controller
{
    public function dashboard(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        $farms = Farm::forOrganization($orgId)->withCount(['lands', 'cropCycles', 'devices'])->get();
        $totalLandArea = (float) Land::forOrganization($orgId)->sum('area_ha');
        $activeCycles = CropCycle::forOrganization($orgId)->active()->with(['farm', 'land'])->orderBy('estimated_harvest_date')->get();

        $totalExpenses = (float) FinancialRecord::forOrganization($orgId)->where('transaction_type', 'expense')->sum('amount');
        $ledgerRevenue = (float) FinancialRecord::forOrganization($orgId)->where('transaction_type', 'revenue')->sum('amount');
        $salesGross = (float) SalesRecord::forOrganization($orgId)->sum('gross_revenue');
        $effectiveRevenue = max($ledgerRevenue, $salesGross);

        $totalHarvestKg = (float) HarvestRecord::forOrganization($orgId)->sum('total_quantity_kg');
        $saleableHarvestKg = (float) HarvestRecord::forOrganization($orgId)->sum('saleable_quantity_kg');

        $recentActivities = FarmActivity::forOrganization($orgId)
            ->with(['farm', 'cropCycle'])
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $advisories = Advisory::forOrganization($orgId)
            ->with(['farm', 'device'])
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $devices = Device::forOrganization($orgId)->with('latestReading')->limit(4)->get();

        return view('farm.dashboard', [
            'organization' => $organization,
            'farms' => $farms,
            'totalFarms' => $farms->count(),
            'totalLandArea' => $totalLandArea,
            'activeCycles' => $activeCycles,
            'totalExpenses' => $totalExpenses,
            'effectiveRevenue' => $effectiveRevenue,
            'profitLoss' => $effectiveRevenue - $totalExpenses,
            'totalHarvestKg' => $totalHarvestKg,
            'saleableHarvestKg' => $saleableHarvestKg,
            'recentActivities' => $recentActivities,
            'advisories' => $advisories,
            'devices' => $devices,
        ]);
    }

    public function intelligence(
        Request $request,
        TenantContext $tenantContext,
        FinancialCalculatorService $calculator
    ): View {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        $defaults = $this->resolveTenantFinancialDefaults($orgId);
        $input = $request->isMethod('post') || $request->has('calculate')
            ? $request->except(['_token'])
            : $defaults;

        $result = $calculator->calculate($input);

        $cyclesSummary = CropCycle::forOrganization($orgId)
            ->with(['farm'])
            ->withSum('harvests', 'saleable_quantity_kg')
            ->withSum('sales', 'gross_revenue')
            ->orderByDesc('planting_date')
            ->limit(6)
            ->get();

        return view('farm.intelligence', [
            'organization' => $organization,
            'input' => $input,
            'result' => $result,
            'cyclesSummary' => $cyclesSummary,
        ]);
    }

    public function exportIntelligenceCsv(
        Request $request,
        TenantContext $tenantContext,
        FinancialCalculatorService $calculator
    ): StreamedResponse {
        $organization = $tenantContext->resolveOrganization($request->user());
        $defaults = $this->resolveTenantFinancialDefaults($organization?->id);
        $input = $request->query() !== [] ? $request->query() : $defaults;
        $result = $calculator->calculate($input);

        $filename = 'regen-financial-bi-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($organization, $result): void {
            $out = fopen('php://output', 'wb');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['REGEN Business Intelligence Financial Summary']);
            fputcsv($out, ['Organization', $organization?->name ?? 'N/A']);
            fputcsv($out, ['Generated At', now()->toIso8601String()]);
            fputcsv($out, []);
            fputcsv($out, ['Metric', 'Value', 'Notes']);
            fputcsv($out, ['Fixed Costs (IDR)', $result['fixed_costs'], 'Biaya Tetap']);
            fputcsv($out, ['Variable Costs (IDR)', $result['variable_costs'], 'Biaya Variabel']);
            fputcsv($out, ['Total Production Cost (IDR)', $result['total_production_cost'], 'Total Biaya Produksi']);
            fputcsv($out, ['Harvested Quantity (kg)', $result['harvested_quantity'], 'Panen Bruto']);
            fputcsv($out, ['Saleable Quantity (kg)', $result['saleable_quantity'], 'Panen Layak Jual']);
            fputcsv($out, ['Quantity Sold (kg)', $result['quantity_sold'], 'Kuantitas Terjual']);
            fputcsv($out, ['Selling Price per kg (IDR)', $result['selling_price_per_unit'], 'Harga Jual']);
            fputcsv($out, ['Total Revenue (IDR)', $result['revenue'], 'Pendapatan Kotor']);
            fputcsv($out, ['Estimated Profit / Loss (IDR)', $result['profit_loss'], $result['disclaimer']]);
            fputcsv($out, ['HPP Basis', $result['hpp_basis_label'], $result['hpp_basis_quantity'].' kg']);
            fputcsv($out, ['HPP per Unit (IDR/kg)', $result['hpp_per_unit'] ?? 'N/A', 'Harga Pokok Produksi']);
            fputcsv($out, ['Break-Even Quantity (kg)', $result['break_even_quantity'] ?? 'N/A', 'BEP Kuantitas']);
            fputcsv($out, ['Break-Even Revenue (IDR)', $result['break_even_revenue'] ?? 'N/A', 'BEP Nilai Penjualan']);
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function learning(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $level = $request->string('level')->toString();

        $articles = LearningArticle::published()
            ->when(in_array($level, ['pemula', 'menengah', 'lanjutan'], true), fn ($q) => $q->where('difficulty_level', $level))
            ->get();

        return view('farm.learning.index', [
            'organization' => $organization,
            'articles' => $articles,
            'selectedLevel' => $level,
        ]);
    }

    public function showLearning(Request $request, LearningArticle $learningArticle, TenantContext $tenantContext): View
    {
        if (! $learningArticle->is_published) {
            abort(404);
        }

        $organization = $tenantContext->resolveOrganization($request->user());
        $allArticles = LearningArticle::published()->get();

        return view('farm.learning.show', [
            'organization' => $organization,
            'article' => $learningArticle,
            'allArticles' => $allArticles,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveTenantFinancialDefaults(?int $orgId): array
    {
        $expenses = FinancialRecord::forOrganization($orgId)
            ->where('transaction_type', 'expense')
            ->get();

        $harvests = HarvestRecord::forOrganization($orgId)->get();
        $sales = SalesRecord::forOrganization($orgId)->get();

        $soldQty = (float) $sales->sum('quantity_sold_kg');
        $avgPrice = $soldQty > 0
            ? round(((float) $sales->sum('gross_revenue')) / $soldQty, 2)
            : 18500.0;

        return [
            'land_lease_fixed' => 6000000,
            'equipment_fixed' => (float) $expenses->where('category', 'equipment_cost')->sum('amount') ?: 12000000,
            'other_fixed' => 0,
            'land_preparation_cost' => (float) $expenses->where('category', 'land_preparation')->sum('amount') ?: 14500000,
            'input_cost' => (float) $expenses->where('category', 'input_cost')->sum('amount') ?: 63450000,
            'labor_cost' => (float) $expenses->where('category', 'labor_cost')->sum('amount') ?: 34200000,
            'operational_cost' => (float) $expenses->where('category', 'operational_cost')->sum('amount') ?: 8900000,
            'transportation_cost' => (float) $expenses->where('category', 'transportation_cost')->sum('amount') ?: 6800000,
            'harvested_quantity' => (float) $harvests->sum('total_quantity_kg') ?: 18000,
            'saleable_quantity' => (float) $harvests->sum('saleable_quantity_kg') ?: 17470,
            'quantity_sold' => $soldQty ?: 17200,
            'selling_price_per_unit' => $avgPrice,
            'hpp_basis' => 'saleable',
        ];
    }
}
