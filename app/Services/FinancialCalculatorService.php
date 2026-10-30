<?php

namespace App\Services;

class FinancialCalculatorService
{
    /**
     * Calculate complete farm production financials safely, handling zero, missing, or invalid inputs.
     *
     * @param  array<string, mixed>  $input
     * @return array{
     *     costs: array<string, float>,
     *     fixed_costs: float,
     *     variable_costs: float,
     *     total_production_cost: float,
     *     harvested_quantity: float,
     *     saleable_quantity: float,
     *     quantity_sold: float,
     *     selling_price_per_unit: float,
     *     hpp_basis: string,
     *     hpp_basis_label: string,
     *     hpp_basis_quantity: float,
     *     hpp_per_unit: ?float,
     *     variable_cost_per_unit: ?float,
     *     contribution_margin_per_unit: ?float,
     *     revenue: float,
     *     profit_loss: float,
     *     margin_percentage: ?float,
     *     is_profitable: bool,
     *     break_even_quantity: ?float,
     *     break_even_revenue: ?float,
     *     cost_breakdown_pct: array<string, float>,
     *     warnings: array<int, string>,
     *     disclaimer: string
     * }
     */
    public function calculate(array $input): array
    {
        $warnings = [];

        $landLeaseFixed = $this->sanitizeNonNegative($input['land_lease_fixed'] ?? 0, 'Sewa lahan / biaya tetap lahan', $warnings);
        $equipmentFixed = $this->sanitizeNonNegative($input['equipment_fixed'] ?? 0, 'Penyusutan peralatan & infrastruktur', $warnings);
        $otherFixed = $this->sanitizeNonNegative($input['other_fixed'] ?? 0, 'Biaya tetap manajemen lainnya', $warnings);

        $landPreparation = $this->sanitizeNonNegative($input['land_preparation_cost'] ?? 0, 'Biaya persiapan lahan', $warnings);
        $seedsAndInputs = $this->sanitizeNonNegative($input['input_cost'] ?? 0, 'Biaya benih, pupuk & sarana produksi', $warnings);
        $laborCost = $this->sanitizeNonNegative($input['labor_cost'] ?? 0, 'Biaya tenaga kerja', $warnings);
        $operationalCost = $this->sanitizeNonNegative($input['operational_cost'] ?? 0, 'Biaya operasional & irigasi', $warnings);
        $transportationCost = $this->sanitizeNonNegative($input['transportation_cost'] ?? 0, 'Biaya transportasi & distribusi', $warnings);

        $fixedCosts = round($landLeaseFixed + $equipmentFixed + $otherFixed, 2);
        $variableCosts = round($landPreparation + $seedsAndInputs + $laborCost + $operationalCost + $transportationCost, 2);
        $totalProductionCost = round($fixedCosts + $variableCosts, 2);

        $harvestedQuantity = $this->sanitizeNonNegative($input['harvested_quantity'] ?? 0, 'Total kuantitas panen', $warnings);
        $saleableQuantity = $this->sanitizeNonNegative($input['saleable_quantity'] ?? $harvestedQuantity, 'Kuantitas layak jual', $warnings);
        $quantitySold = $this->sanitizeNonNegative($input['quantity_sold'] ?? 0, 'Kuantitas terjual', $warnings);
        $sellingPricePerUnit = $this->sanitizeNonNegative($input['selling_price_per_unit'] ?? 0, 'Harga jual per unit', $warnings);

        if ($saleableQuantity > $harvestedQuantity && $harvestedQuantity > 0) {
            $warnings[] = 'Kuantitas layak jual melebihi total panen bruto; periksa kembali pencatatan susut pascapanen.';
        }

        $hppBasis = ($input['hpp_basis'] ?? 'saleable') === 'harvested' ? 'harvested' : 'saleable';
        $hppBasisLabel = $hppBasis === 'harvested'
            ? 'Total Kuantitas Panen Bruto (Harvested Quantity)'
            : 'Total Kuantitas Layak Jual (Saleable Quantity)';
        $hppBasisQuantity = $hppBasis === 'harvested' ? $harvestedQuantity : $saleableQuantity;

        $hppPerUnit = null;
        if ($hppBasisQuantity > 0) {
            $hppPerUnit = round($totalProductionCost / $hppBasisQuantity, 2);
        } else {
            $warnings[] = 'HPP per unit belum dapat dihitung karena kuantitas basis perhitungan bernilai 0.';
        }

        $customVariablePerUnit = isset($input['variable_cost_per_unit']) && $input['variable_cost_per_unit'] !== '' && $input['variable_cost_per_unit'] !== null
            ? $this->sanitizeNonNegative($input['variable_cost_per_unit'], 'Biaya variabel per unit', $warnings)
            : null;

        $variableCostPerUnit = null;
        if ($customVariablePerUnit !== null && $customVariablePerUnit > 0) {
            $variableCostPerUnit = round($customVariablePerUnit, 2);
        } elseif ($hppBasisQuantity > 0) {
            $variableCostPerUnit = round($variableCosts / $hppBasisQuantity, 2);
        }

        $revenue = round($quantitySold * $sellingPricePerUnit, 2);
        $profitLoss = round($revenue - $totalProductionCost, 2);
        $marginPercentage = $revenue > 0 ? round(($profitLoss / $revenue) * 100, 2) : null;

        $contributionMarginPerUnit = null;
        $breakEvenQuantity = null;
        $breakEvenRevenue = null;

        if ($variableCostPerUnit !== null) {
            $contributionMarginPerUnit = round($sellingPricePerUnit - $variableCostPerUnit, 2);

            if ($contributionMarginPerUnit > 0) {
                $breakEvenQuantity = round($fixedCosts / $contributionMarginPerUnit, 2);
                $breakEvenRevenue = round($breakEvenQuantity * $sellingPricePerUnit, 2);
            } else {
                $warnings[] = 'Titik Impas (BEP) tidak tercapai karena harga jual per unit lebih kecil atau sama dengan biaya variabel per unit.';
            }
        } else {
            $warnings[] = 'Titik Impas (BEP) memerlukan data kuantitas produksi atau biaya variabel per unit yang valid.';
        }

        $costs = [
            'land_lease_fixed' => $landLeaseFixed,
            'equipment_fixed' => $equipmentFixed,
            'other_fixed' => $otherFixed,
            'land_preparation_cost' => $landPreparation,
            'input_cost' => $seedsAndInputs,
            'labor_cost' => $laborCost,
            'operational_cost' => $operationalCost,
            'transportation_cost' => $transportationCost,
        ];

        $costBreakdownPct = [];
        foreach ($costs as $key => $value) {
            $costBreakdownPct[$key] = $totalProductionCost > 0
                ? round(($value / $totalProductionCost) * 100, 1)
                : 0.0;
        }

        return [
            'costs' => $costs,
            'fixed_costs' => $fixedCosts,
            'variable_costs' => $variableCosts,
            'total_production_cost' => $totalProductionCost,
            'harvested_quantity' => $harvestedQuantity,
            'saleable_quantity' => $saleableQuantity,
            'quantity_sold' => $quantitySold,
            'selling_price_per_unit' => $sellingPricePerUnit,
            'hpp_basis' => $hppBasis,
            'hpp_basis_label' => $hppBasisLabel,
            'hpp_basis_quantity' => $hppBasisQuantity,
            'hpp_per_unit' => $hppPerUnit,
            'variable_cost_per_unit' => $variableCostPerUnit,
            'contribution_margin_per_unit' => $contributionMarginPerUnit,
            'revenue' => $revenue,
            'profit_loss' => $profitLoss,
            'margin_percentage' => $marginPercentage,
            'is_profitable' => $profitLoss > 0,
            'break_even_quantity' => $breakEvenQuantity,
            'break_even_revenue' => $breakEvenRevenue,
            'cost_breakdown_pct' => $costBreakdownPct,
            'warnings' => array_values(array_unique($warnings)),
            'disclaimer' => 'Catatan Penting: Estimasi laba/rugi, HPP, dan BEP ini merupakan proyeksi kalkulasi finansial berbasis input data, bukan jaminan keuntungan pasti. Hasil aktual dipengaruhi dinamika pasar, cuaca, dan risiko budidaya.',
        ];
    }

    /**
     * @param  array<int, string>  $warnings
     */
    private function sanitizeNonNegative(mixed $value, string $fieldLabel, array &$warnings): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (! is_numeric($value)) {
            $warnings[] = "{$fieldLabel} mengandung format tidak valid dan telah diatur ke 0.";

            return 0.0;
        }

        $numeric = (float) $value;

        if ($numeric < 0) {
            $warnings[] = "{$fieldLabel} tidak boleh bernilai negatif dan telah disesuaikan ke 0.";

            return 0.0;
        }

        return round($numeric, 2);
    }
}
