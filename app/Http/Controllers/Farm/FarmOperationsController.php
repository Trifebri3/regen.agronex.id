<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\CropCycle;
use App\Models\Farm;
use App\Models\FarmActivity;
use App\Models\FarmInput;
use App\Models\FinancialRecord;
use App\Models\HarvestRecord;
use App\Models\Land;
use App\Models\LandPlot;
use App\Models\SalesRecord;
use App\Services\AuditLogger;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FarmOperationsController extends Controller
{
    public function lands(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.lands', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'lands' => Land::forOrganization($orgId)->with(['farm', 'plots'])->orderBy('name')->get(),
        ]);
    }

    public function storeLand(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        abort_if($organization === null, 403, 'Organisasi tidak ditemukan.');

        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:160'],
            'location' => ['required', 'string', 'max:200'],
            'area_ha' => ['required', 'numeric', 'min:0.01', 'max:50000'],
            'ownership_status' => ['required', 'string', Rule::in(['owned', 'leased', 'partnership', 'managed'])],
            'soil_type' => ['nullable', 'string', 'max:120'],
            'soil_ph' => ['nullable', 'numeric', 'min:2.0', 'max:11.0'],
            'soil_profile_notes' => ['nullable', 'string', 'max:1500'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('lands/photos', 'public')
            : null;

        $land = Land::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'name' => $validated['name'],
            'code' => 'LND-'.strtoupper(Str::random(5)),
            'location' => $validated['location'],
            'area_ha' => $validated['area_ha'],
            'ownership_status' => $validated['ownership_status'],
            'soil_type' => $validated['soil_type'] ?? null,
            'soil_ph' => $validated['soil_ph'] ?? null,
            'soil_profile_notes' => $validated['soil_profile_notes'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'active',
        ]);

        $auditLogger->record('farm.land_created', $land);

        return back()->with('status', "Lahan '{$land->name}' berhasil didaftarkan.");
    }

    public function storePlot(Request $request, Land $land, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $tenantContext->assertSameOrganization((int) $land->organization_id, $request->user());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'plot_code' => ['required', 'string', 'max:40'],
            'area_ha' => ['required', 'numeric', 'min:0.01', 'max:10000'],
            'soil_condition' => ['nullable', 'string', 'max:180'],
            'irrigation_type' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:800'],
        ]);

        $plot = LandPlot::create(array_merge($validated, [
            'organization_id' => $land->organization_id,
            'land_id' => $land->id,
        ]));

        $auditLogger->record('farm.land_plot_created', $plot);

        return back()->with('status', "Petak '{$plot->name}' berhasil ditambahkan pada lahan {$land->name}.");
    }

    public function destroyLand(Request $request, Land $land, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $tenantContext->assertSameOrganization((int) $land->organization_id, $request->user());

        $name = $land->name;
        $auditLogger->record('farm.land_deleted', $land, ['name' => $name]);
        $land->delete();

        return back()->with('status', "Lahan '{$name}' telah dihapus.");
    }

    public function crops(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.crops', [
            'organization' => $organization,
            'lands' => Land::forOrganization($orgId)->with(['farm', 'plots'])->orderBy('name')->get(),
            'crops' => CropCycle::forOrganization($orgId)->with(['farm', 'land', 'plot'])->orderByDesc('planting_date')->get(),
        ]);
    }

    public function storeCrop(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'land_id' => ['required', 'integer'],
            'land_plot_id' => ['nullable', 'integer'],
            'crop_name' => ['required', 'string', 'max:150'],
            'variety' => ['required', 'string', 'max:150'],
            'planting_date' => ['required', 'date'],
            'estimated_harvest_date' => ['required', 'date', 'after_or_equal:planting_date'],
            'planting_area_ha' => ['required', 'numeric', 'min:0.01', 'max:10000'],
            'seed_requirement' => ['required', 'numeric', 'min:0'],
            'seed_unit' => ['required', 'string', Rule::in(['kg', 'gram', 'bibit', 'pack'])],
            'cultivation_status' => ['required', 'string', Rule::in(['planned', 'land_preparation', 'planted', 'vegetative', 'generative', 'harvesting', 'completed'])],
            'target_yield_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $land = Land::findOrFail((int) $validated['land_id']);
        $tenantContext->assertSameOrganization((int) $land->organization_id, $request->user());

        $plotId = null;
        if (! empty($validated['land_plot_id'])) {
            $plot = LandPlot::findOrFail((int) $validated['land_plot_id']);
            $tenantContext->assertSameOrganization((int) $plot->organization_id, $request->user());
            $plotId = $plot->id;
        }

        $cycle = CropCycle::create([
            'organization_id' => $land->organization_id,
            'farm_id' => $land->farm_id,
            'land_id' => $land->id,
            'land_plot_id' => $plotId,
            'crop_name' => $validated['crop_name'],
            'variety' => $validated['variety'],
            'cycle_code' => 'CYC-'.date('Y').'-'.strtoupper(Str::random(4)),
            'planting_date' => $validated['planting_date'],
            'estimated_harvest_date' => $validated['estimated_harvest_date'],
            'planting_area_ha' => $validated['planting_area_ha'],
            'seed_requirement' => $validated['seed_requirement'],
            'seed_unit' => $validated['seed_unit'],
            'cultivation_status' => $validated['cultivation_status'],
            'target_yield_kg' => $validated['target_yield_kg'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $auditLogger->record('farm.crop_cycle_created', $cycle);

        return back()->with('status', "Siklus tanam '{$cycle->crop_name} ({$cycle->variety})' berhasil dijadwalkan.");
    }

    public function updateCropStatus(Request $request, CropCycle $cropCycle, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $tenantContext->assertSameOrganization((int) $cropCycle->organization_id, $request->user());

        $validated = $request->validate([
            'cultivation_status' => ['required', 'string', Rule::in(['planned', 'land_preparation', 'planted', 'vegetative', 'generative', 'harvesting', 'completed', 'failed'])],
        ]);

        $cropCycle->update(['cultivation_status' => $validated['cultivation_status']]);
        $auditLogger->record('farm.crop_status_updated', $cropCycle, $validated);

        return back()->with('status', 'Status siklus budidaya berhasil diperbarui.');
    }

    public function activities(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.activities', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'crops' => CropCycle::forOrganization($orgId)->orderByDesc('planting_date')->get(),
            'activities' => FarmActivity::forOrganization($orgId)
                ->with(['farm', 'cropCycle', 'recorder'])
                ->orderByDesc('activity_date')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }

    public function storeActivity(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'crop_cycle_id' => ['nullable', 'integer'],
            'activity_type' => ['required', 'string', Rule::in(['land_preparation', 'planting', 'irrigation', 'fertilization', 'pest_control', 'maintenance', 'harvest', 'daily_log'])],
            'title' => ['required', 'string', 'max:200'],
            'activity_date' => ['required', 'date'],
            'labor_count' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'labor_hours' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'water_volume_liters' => ['nullable', 'numeric', 'min:0'],
            'cost_incurred' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $cropId = null;
        $landId = null;
        if (! empty($validated['crop_cycle_id'])) {
            $crop = CropCycle::findOrFail((int) $validated['crop_cycle_id']);
            $tenantContext->assertSameOrganization((int) $crop->organization_id, $request->user());
            $cropId = $crop->id;
            $landId = $crop->land_id;
        }

        $activity = FarmActivity::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'land_id' => $landId,
            'crop_cycle_id' => $cropId,
            'recorded_by' => $request->user()?->id,
            'activity_type' => $validated['activity_type'],
            'title' => $validated['title'],
            'activity_date' => $validated['activity_date'],
            'labor_count' => (int) ($validated['labor_count'] ?? 0),
            'labor_hours' => $validated['labor_hours'] ?? null,
            'water_volume_liters' => $validated['water_volume_liters'] ?? null,
            'cost_incurred' => (float) ($validated['cost_incurred'] ?? 0),
            'status' => 'completed',
            'notes' => $validated['notes'] ?? null,
        ]);

        $auditLogger->record('farm.activity_logged', $activity);

        return back()->with('status', 'Catatan aktivitas lapangan berhasil disimpan.');
    }

    public function inputs(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.inputs', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'crops' => CropCycle::forOrganization($orgId)->orderByDesc('planting_date')->get(),
            'inputs' => FarmInput::forOrganization($orgId)->with(['farm', 'cropCycle'])->orderByDesc('purchase_date')->orderByDesc('id')->get(),
        ]);
    }

    public function storeInput(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'crop_cycle_id' => ['nullable', 'integer'],
            'input_category' => ['required', 'string', Rule::in(['seeds', 'fertilizers', 'pesticides', 'organic_materials', 'other'])],
            'name' => ['required', 'string', 'max:180'],
            'brand_or_source' => ['nullable', 'string', 'max:150'],
            'unit' => ['required', 'string', Rule::in(['kg', 'liter', 'pack', 'sak', 'unit'])],
            'quantity_purchased' => ['required', 'numeric', 'min:0.01'],
            'quantity_used' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['nullable', 'date'],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $totalCost = round(((float) $validated['quantity_purchased']) * ((float) $validated['unit_price']), 2);

        $cropId = null;
        if (! empty($validated['crop_cycle_id'])) {
            $crop = CropCycle::findOrFail((int) $validated['crop_cycle_id']);
            $tenantContext->assertSameOrganization((int) $crop->organization_id, $request->user());
            $cropId = $crop->id;
        }

        $inputItem = FarmInput::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'crop_cycle_id' => $cropId,
            'input_category' => $validated['input_category'],
            'name' => $validated['name'],
            'brand_or_source' => $validated['brand_or_source'] ?? null,
            'unit' => $validated['unit'],
            'quantity_purchased' => $validated['quantity_purchased'],
            'quantity_used' => (float) ($validated['quantity_used'] ?? 0),
            'unit_price' => $validated['unit_price'],
            'total_cost' => $totalCost,
            'purchase_date' => $validated['purchase_date'] ?? now()->toDateString(),
        ]);

        $auditLogger->record('farm.input_recorded', $inputItem);

        return back()->with('status', "Input produksi '{$inputItem->name}' berhasil dicatat.");
    }

    public function harvests(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.harvests', [
            'organization' => $organization,
            'crops' => CropCycle::forOrganization($orgId)->with('farm')->orderByDesc('planting_date')->get(),
            'harvests' => HarvestRecord::forOrganization($orgId)->with(['farm', 'cropCycle'])->orderByDesc('harvest_date')->get(),
        ]);
    }

    public function storeHarvest(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'crop_cycle_id' => ['required', 'integer'],
            'harvest_date' => ['required', 'date'],
            'total_quantity_kg' => ['required', 'numeric', 'min:0.1'],
            'product_grade' => ['required', 'string', Rule::in(['Grade A', 'Grade B', 'Grade C', 'Mixed'])],
            'quality_notes' => ['nullable', 'string', 'max:300'],
            'saleable_quantity_kg' => ['required', 'numeric', 'min:0'],
            'sold_quantity_kg' => ['nullable', 'numeric', 'min:0'],
            'post_harvest_loss_kg' => ['nullable', 'numeric', 'min:0'],
        ]);

        $crop = CropCycle::findOrFail((int) $validated['crop_cycle_id']);
        $tenantContext->assertSameOrganization((int) $crop->organization_id, $request->user());

        $saleable = (float) $validated['saleable_quantity_kg'];
        $sold = (float) ($validated['sold_quantity_kg'] ?? 0);
        $unsold = max(0, round($saleable - $sold, 2));

        $harvest = HarvestRecord::create([
            'organization_id' => $crop->organization_id,
            'farm_id' => $crop->farm_id,
            'crop_cycle_id' => $crop->id,
            'harvest_date' => $validated['harvest_date'],
            'total_quantity_kg' => $validated['total_quantity_kg'],
            'product_grade' => $validated['product_grade'],
            'quality_notes' => $validated['quality_notes'] ?? null,
            'saleable_quantity_kg' => $saleable,
            'sold_quantity_kg' => $sold,
            'unsold_quantity_kg' => $unsold,
            'post_harvest_loss_kg' => (float) ($validated['post_harvest_loss_kg'] ?? max(0, (float) $validated['total_quantity_kg'] - $saleable)),
            'recorded_by' => $request->user()?->id,
        ]);

        $auditLogger->record('farm.harvest_recorded', $harvest);

        return back()->with('status', 'Data hasil panen berhasil disimpan.');
    }

    public function sales(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        return view('farm.sales', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'crops' => CropCycle::forOrganization($orgId)->orderByDesc('planting_date')->get(),
            'sales' => SalesRecord::forOrganization($orgId)->with(['farm', 'cropCycle'])->orderByDesc('transaction_date')->get(),
        ]);
    }

    public function storeSale(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'crop_cycle_id' => ['nullable', 'integer'],
            'buyer_name' => ['required', 'string', 'max:180'],
            'buyer_contact' => ['nullable', 'string', 'max:150'],
            'buyer_type' => ['required', 'string', Rule::in(['offtaker', 'wholesaler', 'retail', 'export', 'cooperative', 'direct'])],
            'product_name' => ['required', 'string', 'max:180'],
            'quantity_sold_kg' => ['required', 'numeric', 'min:0.1'],
            'price_per_kg' => ['required', 'numeric', 'min:0'],
            'distribution_cost' => ['nullable', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'payment_status' => ['required', 'string', Rule::in(['pending', 'partial', 'paid'])],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $gross = round(((float) $validated['quantity_sold_kg']) * ((float) $validated['price_per_kg']), 2);
        $dist = (float) ($validated['distribution_cost'] ?? 0);

        $cropId = null;
        if (! empty($validated['crop_cycle_id'])) {
            $crop = CropCycle::findOrFail((int) $validated['crop_cycle_id']);
            $tenantContext->assertSameOrganization((int) $crop->organization_id, $request->user());
            $cropId = $crop->id;
        }

        $sale = SalesRecord::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'crop_cycle_id' => $cropId,
            'invoice_number' => 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4)),
            'buyer_name' => $validated['buyer_name'],
            'buyer_contact' => $validated['buyer_contact'] ?? null,
            'buyer_type' => $validated['buyer_type'],
            'product_name' => $validated['product_name'],
            'quantity_sold_kg' => $validated['quantity_sold_kg'],
            'price_per_kg' => $validated['price_per_kg'],
            'gross_revenue' => $gross,
            'distribution_cost' => $dist,
            'net_revenue' => round($gross - $dist, 2),
            'transaction_date' => $validated['transaction_date'],
            'payment_status' => $validated['payment_status'],
        ]);

        $auditLogger->record('farm.sale_recorded', $sale);

        return back()->with('status', "Transaksi penjualan {$sale->invoice_number} berhasil dicatat.");
    }

    public function finance(Request $request, TenantContext $tenantContext): View
    {
        $organization = $tenantContext->resolveOrganization($request->user());
        $orgId = $organization?->id;

        $records = FinancialRecord::forOrganization($orgId)
            ->with(['farm', 'cropCycle'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        $totalCapital = (float) $records->where('transaction_type', 'capital')->sum('amount');
        $totalExpense = (float) $records->where('transaction_type', 'expense')->sum('amount');
        $totalRevenue = (float) $records->where('transaction_type', 'revenue')->sum('amount');

        return view('farm.finance', [
            'organization' => $organization,
            'farms' => Farm::forOrganization($orgId)->orderBy('name')->get(),
            'crops' => CropCycle::forOrganization($orgId)->orderByDesc('planting_date')->get(),
            'records' => $records,
            'totalCapital' => $totalCapital,
            'totalExpense' => $totalExpense,
            'totalRevenue' => $totalRevenue,
            'netProfitLoss' => $totalRevenue - $totalExpense,
        ]);
    }

    public function storeFinancialRecord(Request $request, TenantContext $tenantContext, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'integer'],
            'crop_cycle_id' => ['nullable', 'integer'],
            'transaction_type' => ['required', 'string', Rule::in(['capital', 'expense', 'revenue'])],
            'category' => ['required', 'string', Rule::in([
                'initial_capital',
                'land_preparation',
                'input_cost',
                'labor_cost',
                'operational_cost',
                'equipment_cost',
                'transportation_cost',
                'sales_revenue',
                'other',
            ])],
            'cost_nature' => ['required', 'string', Rule::in(['fixed', 'variable', 'capital', 'revenue'])],
            'title' => ['required', 'string', 'max:200'],
            'amount' => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $farm = Farm::findOrFail((int) $validated['farm_id']);
        $tenantContext->assertSameOrganization((int) $farm->organization_id, $request->user());

        $cropId = null;
        if (! empty($validated['crop_cycle_id'])) {
            $crop = CropCycle::findOrFail((int) $validated['crop_cycle_id']);
            $tenantContext->assertSameOrganization((int) $crop->organization_id, $request->user());
            $cropId = $crop->id;
        }

        $record = FinancialRecord::create([
            'organization_id' => $farm->organization_id,
            'farm_id' => $farm->id,
            'crop_cycle_id' => $cropId,
            'transaction_type' => $validated['transaction_type'],
            'category' => $validated['category'],
            'cost_nature' => $validated['cost_nature'],
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'reference_code' => 'FIN-'.strtoupper(Str::random(5)),
            'notes' => $validated['notes'] ?? null,
            'recorded_by' => $request->user()?->id,
        ]);

        $auditLogger->record('farm.financial_record_created', $record);

        return back()->with('status', 'Catatan keuangan kebun berhasil disimpan.');
    }
}
