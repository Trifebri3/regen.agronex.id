<x-layouts.portal title="Harvest & Post-Harvest Grading" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.harvests.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Catat Hasil Panen</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Siklus Tanaman *</label>
                    <select name="crop_cycle_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}">{{ $crop->crop_name }} ({{ $crop->cycle_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal Panen *</label>
                        <input type="date" name="harvest_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Grade Produk *</label>
                        <select name="product_grade" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="Grade A">Grade A</option>
                            <option value="Grade B">Grade B</option>
                            <option value="Grade C">Grade C</option>
                            <option value="Mixed">Mixed</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Total Panen Bruto (kg) *</label>
                        <input type="number" step="0.1" name="total_quantity_kg" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Layak Jual / Saleable (kg) *</label>
                        <input type="number" step="0.1" name="saleable_quantity_kg" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Sudah Terjual (kg)</label>
                        <input type="number" step="0.1" name="sold_quantity_kg" value="0" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Susut Pascapanen (kg)</label>
                        <input type="number" step="0.1" name="post_harvest_loss_kg" placeholder="Otomatis bila kosong" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Catatan Kualitas</label>
                    <input type="text" name="quality_notes" placeholder="Kadar air optimal, ukuran seragam..." class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Catatan Panen</button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-8">
            @forelse ($harvests as $hv)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="rounded bg-sage-100 px-2.5 py-0.5 font-semibold text-forest-800">{{ $hv->product_grade }}</span>
                        <span class="text-charcoal-500">{{ $hv->harvest_date?->format('d M Y') }} &bull; {{ $hv->farm?->name }}</span>
                    </div>
                    <h3 class="mt-2 text-base font-semibold text-charcoal-900">{{ $hv->cropCycle?->crop_name }} ({{ $hv->cropCycle?->variety }})</h3>
                    <div class="mt-3 grid grid-cols-4 gap-2 rounded-xl bg-ivory-50 p-3 text-center text-xs tabular-nums">
                        <div><span class="block text-charcoal-500">Bruto</span><strong>{{ number_format((float) $hv->total_quantity_kg, 0, ',', '.') }} kg</strong></div>
                        <div><span class="block text-charcoal-500">Layak Jual</span><strong class="text-forest-800">{{ number_format((float) $hv->saleable_quantity_kg, 0, ',', '.') }} kg</strong></div>
                        <div><span class="block text-charcoal-500">Terjual</span><strong>{{ number_format((float) $hv->sold_quantity_kg, 0, ',', '.') }} kg</strong></div>
                        <div><span class="block text-charcoal-500">Susut</span><strong class="text-red-700">{{ number_format((float) $hv->post_harvest_loss_kg, 0, ',', '.') }} kg</strong></div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada catatan panen.</div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>

