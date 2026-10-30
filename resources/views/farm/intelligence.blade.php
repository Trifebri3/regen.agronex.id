<x-layouts.portal title="Business Intelligence — HPP & Break-Even Calculator" portalType="farm" :organization="$organization">
    {{-- Mandatory Non-Guaranteed Profit Disclaimer --}}
    <div class="mb-6 rounded-2xl border border-olive-200 bg-olive-100/80 p-4 text-xs text-olive-800">
        <strong>{{ $result['disclaimer'] }}</strong>
    </div>

    @if (! empty($result['warnings']))
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-900">
            <p class="font-semibold">Perhatian Validasi Kalkulasi:</p>
            <ul class="mt-1 list-disc pl-4 space-y-0.5">
                @foreach ($result['warnings'] as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Financial Summary KPI Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl border border-ivory-300 bg-white p-5">
            <p class="text-xs font-medium uppercase text-charcoal-500">Total Biaya Produksi</p>
            <p id="live-total-cost" class="mt-2 text-xl font-bold text-charcoal-900 tabular-nums">Rp {{ number_format($result['total_production_cost'], 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-charcoal-500">Tetap: Rp {{ number_format($result['fixed_costs'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-ivory-300 bg-white p-5">
            <p class="text-xs font-medium uppercase text-charcoal-500">Estimasi Pendapatan</p>
            <p id="live-revenue" class="mt-2 text-xl font-bold text-forest-800 tabular-nums">Rp {{ number_format($result['revenue'], 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-charcoal-500">{{ number_format($result['quantity_sold'], 0, ',', '.') }} kg terjual</p>
        </div>
        <div class="rounded-2xl border border-ivory-300 bg-white p-5">
            <p class="text-xs font-medium uppercase text-charcoal-500">Estimasi Laba / Rugi</p>
            <p id="live-profit-loss" class="mt-2 text-xl font-bold tabular-nums {{ $result['profit_loss'] >= 0 ? 'text-forest-800' : 'text-red-700' }}">
                Rp {{ number_format($result['profit_loss'], 0, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-charcoal-500">
                Margin: {{ $result['margin_percentage'] !== null ? $result['margin_percentage'].'%' : 'N/A' }}
            </p>
        </div>
        <div class="rounded-2xl border border-ivory-300 bg-white p-5">
            <p class="text-xs font-medium uppercase text-charcoal-500">HPP per Kilogram</p>
            <p id="live-hpp-unit" class="mt-2 text-xl font-bold text-charcoal-900 tabular-nums">
                {{ $result['hpp_per_unit'] !== null ? 'Rp '.number_format($result['hpp_per_unit'], 0, ',', '.').' / kg' : 'N/A' }}
            </p>
            <p class="mt-1 text-[11px] text-forest-700">Basis: {{ $result['hpp_basis'] === 'harvested' ? 'Panen Bruto' : 'Layak Jual' }}</p>
        </div>
        <div class="rounded-2xl border border-ivory-300 bg-white p-5">
            <p class="text-xs font-medium uppercase text-charcoal-500">Titik Impas (BEP)</p>
            <p id="live-bep-qty" class="mt-2 text-xl font-bold text-olive-700 tabular-nums">
                {{ $result['break_even_quantity'] !== null ? number_format($result['break_even_quantity'], 1, ',', '.').' kg' : 'Tidak Tercapai' }}
            </p>
            <p class="mt-1 text-[11px] text-charcoal-500">
                {{ $result['break_even_revenue'] !== null ? 'Rp '.number_format($result['break_even_revenue'], 0, ',', '.') : 'Periksa margin unit' }}
            </p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-12">
        {{-- Editable Financial Calculator Form --}}
        <div class="lg:col-span-7">
            <form id="regen-bi-calculator" method="POST" action="{{ route('app.intelligence.calculate') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-5">
                @csrf
                <input type="hidden" name="calculate" value="1">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-ivory-200 pb-3">
                    <div>
                        <h2 class="text-base font-semibold text-charcoal-900">Kalkulator Finansial Produksi Interaktif</h2>
                        <p class="text-xs text-charcoal-500">Ubah parameter biaya atau hasil panen untuk simulasi otomatis &amp; perhitungan server.</p>
                    </div>
                    <a
                        href="{{ route('app.intelligence.export', $input) }}"
                        class="rounded-xl border border-forest-800 px-3.5 py-2 text-xs font-semibold text-forest-800 hover:bg-forest-900 hover:text-white"
                    >
                        Unduh Laporan CSV
                    </a>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-olive-700">1. Komponen Biaya Tetap (Fixed Costs)</h3>
                    <div class="mt-2.5 grid gap-3 sm:grid-cols-3">
                        <div>
                            <label class="block text-xs text-charcoal-600">Sewa Lahan Musiman (Rp)</label>
                            <input type="number" step="1000" min="0" name="land_lease_fixed" value="{{ $input['land_lease_fixed'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Penyusutan Alat &amp; Pipa (Rp)</label>
                            <input type="number" step="1000" min="0" name="equipment_fixed" value="{{ $input['equipment_fixed'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Biaya Tetap Lainnya (Rp)</label>
                            <input type="number" step="1000" min="0" name="other_fixed" value="{{ $input['other_fixed'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-olive-700">2. Komponen Biaya Variabel (Variable Production Costs)</h3>
                    <div class="mt-2.5 grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs text-charcoal-600">Persiapan Lahan &amp; Mulsa (Rp)</label>
                            <input type="number" step="1000" min="0" name="land_preparation_cost" value="{{ $input['land_preparation_cost'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Benih, Pupuk &amp; Pestisida (Rp)</label>
                            <input type="number" step="1000" min="0" name="input_cost" value="{{ $input['input_cost'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Tenaga Kerja Tanam–Panen (Rp)</label>
                            <input type="number" step="1000" min="0" name="labor_cost" value="{{ $input['labor_cost'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Operasional &amp; Irigasi (Rp)</label>
                            <input type="number" step="1000" min="0" name="operational_cost" value="{{ $input['operational_cost'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs text-charcoal-600">Transportasi &amp; Distribusi (Rp)</label>
                            <input type="number" step="1000" min="0" name="transportation_cost" value="{{ $input['transportation_cost'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-olive-700">3. Kuantitas Produksi, Basis HPP &amp; Harga Jual</h3>
                    <div class="mt-2.5 grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs text-charcoal-600">Total Panen Bruto / Harvested (kg)</label>
                            <input type="number" step="1" min="0" name="harvested_quantity" value="{{ $input['harvested_quantity'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Kuantitas Layak Jual / Saleable (kg)</label>
                            <input type="number" step="1" min="0" name="saleable_quantity" value="{{ $input['saleable_quantity'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Kuantitas Terjual Aktual (kg)</label>
                            <input type="number" step="1" min="0" name="quantity_sold" value="{{ $input['quantity_sold'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs text-charcoal-600">Harga Jual per Kilogram (Rp)</label>
                            <input type="number" step="100" min="0" name="selling_price_per_unit" value="{{ $input['selling_price_per_unit'] ?? 0 }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-ivory-200 bg-ivory-50 p-3.5 text-xs">
                        <span class="font-semibold text-charcoal-800">Pilih Basis Perhitungan HPP per Unit:</span>
                        <div class="mt-2 flex flex-wrap gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="hpp_basis" value="saleable" @checked(($result['hpp_basis'] ?? 'saleable') === 'saleable')>
                                <span>Kuantitas Layak Jual (Saleable Quantity — Direkomendasikan)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="hpp_basis" value="harvested" @checked(($result['hpp_basis'] ?? '') === 'harvested')>
                                <span>Total Panen Bruto (Harvested Quantity)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="rounded-xl bg-forest-900 px-6 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">
                        Hitung &amp; Perbarui Grafik BI
                    </button>
                    <a href="{{ route('app.intelligence') }}" class="rounded-xl border border-ivory-300 px-4 py-2.5 text-xs font-medium text-charcoal-600 hover:bg-ivory-100">
                        Muat Ulang Data Buku Besar Kebun
                    </a>
                </div>
            </form>
        </div>

        {{-- Visual Cost Breakdown & Revenue vs Cost Charts --}}
        <div class="space-y-6 lg:col-span-5">
            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <h2 class="text-base font-semibold text-charcoal-900">Grafik Komparasi Pendapatan vs Biaya</h2>
                @php
                    $maxBar = max($result['revenue'], $result['total_production_cost'], 1);
                    $revPct = min(100, round(($result['revenue'] / $maxBar) * 100, 1));
                    $costPct = min(100, round(($result['total_production_cost'] / $maxBar) * 100, 1));
                @endphp
                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <div class="flex justify-between font-medium">
                            <span>Total Pendapatan (Revenue)</span>
                            <span class="tabular-nums font-semibold text-forest-800">Rp {{ number_format($result['revenue'], 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-1.5 h-3.5 w-full overflow-hidden rounded-full bg-ivory-200">
                            <div class="h-full rounded-full bg-forest-700" style="width: {{ $revPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between font-medium">
                            <span>Total Biaya Produksi</span>
                            <span class="tabular-nums font-semibold text-charcoal-900">Rp {{ number_format($result['total_production_cost'], 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-1.5 h-3.5 w-full overflow-hidden rounded-full bg-ivory-200">
                            <div class="h-full rounded-full bg-olive-600" style="width: {{ $costPct }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 rounded-xl bg-ivory-50 p-3 text-xs text-charcoal-700">
                    <strong>Basis Aktif HPP:</strong> {{ $result['hpp_basis_label'] }} ({{ number_format($result['hpp_basis_quantity'], 0, ',', '.') }} kg) &bull;
                    Biaya Variabel/unit: {{ $result['variable_cost_per_unit'] !== null ? 'Rp '.number_format($result['variable_cost_per_unit'], 0, ',', '.') : 'N/A' }}
                </p>
            </div>

            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <h2 class="text-base font-semibold text-charcoal-900">Distribusi Struktur Biaya Produksi</h2>
                @php
                    $costLabels = [
                        'input_cost' => 'Benih, Pupuk & Saprotan',
                        'labor_cost' => 'Tenaga Kerja',
                        'equipment_fixed' => 'Penyusutan Alat & Infrastruktur',
                        'land_preparation_cost' => 'Persiapan Lahan',
                        'operational_cost' => 'Operasional & Irigasi',
                        'transportation_cost' => 'Transportasi & Distribusi',
                        'land_lease_fixed' => 'Sewa Lahan',
                        'other_fixed' => 'Biaya Tetap Lainnya',
                    ];
                @endphp
                <div class="mt-4 space-y-3 text-xs">
                    @foreach ($costLabels as $cKey => $cLabel)
                        @php $pct = $result['cost_breakdown_pct'][$cKey] ?? 0; @endphp
                        @if (($result['costs'][$cKey] ?? 0) > 0)
                            <div>
                                <div class="flex justify-between">
                                    <span class="text-charcoal-700">{{ $cLabel }}</span>
                                    <span class="tabular-nums font-semibold">{{ $pct }}% (Rp {{ number_format($result['costs'][$cKey], 0, ',', '.') }})</span>
                                </div>
                                <div class="mt-1 h-2 w-full overflow-hidden rounded-full bg-ivory-200">
                                    <div class="h-full rounded-full bg-sage-600" style="width: {{ min(100, $pct) }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.portal>

