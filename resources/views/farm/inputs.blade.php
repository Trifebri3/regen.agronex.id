<x-layouts.portal title="Production Input Management" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.inputs.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Catat Sarana Produksi (Saprotan)</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kebun *</label>
                    <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kategori Input *</label>
                    <select name="input_category" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="seeds">Benih / Bibit (Seeds)</option>
                        <option value="fertilizers">Pupuk (Fertilizers)</option>
                        <option value="pesticides">Pestisida / Agen Hayati</option>
                        <option value="organic_materials">Bahan Organik / Kompos</option>
                        <option value="other">Sarana Produksi Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Nama Input *</label>
                    <input type="text" name="name" required placeholder="Pupuk NPK 16-16-16" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Jml Beli *</label>
                        <input type="number" step="0.01" name="quantity_purchased" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Terpakai</label>
                        <input type="number" step="0.01" name="quantity_used" value="0" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Satuan</label>
                        <select name="unit" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="kg">kg</option>
                            <option value="liter">liter</option>
                            <option value="pack">pack</option>
                            <option value="sak">sak</option>
                            <option value="unit">unit</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Harga Beli per Satuan (Rp) *</label>
                    <input type="number" step="100" name="unit_price" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Input</button>
            </form>
        </div>

        <div class="lg:col-span-8">
            <div class="overflow-x-auto rounded-2xl border border-ivory-300 bg-white">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-ivory-200 bg-ivory-50 uppercase text-charcoal-600">
                        <tr>
                            <th class="px-4 py-3">Nama &amp; Kategori</th>
                            <th class="px-4 py-3">Beli / Terpakai</th>
                            <th class="px-4 py-3">Harga Satuan</th>
                            <th class="px-4 py-3">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ivory-200">
                        @forelse ($inputs as $inp)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-charcoal-900">{{ $inp->name }}</p>
                                    <p class="text-charcoal-500">{{ ucfirst(str_replace('_', ' ', $inp->input_category)) }} &bull; {{ $inp->farm?->name }}</p>
                                </td>
                                <td class="px-4 py-3 tabular-nums">{{ number_format((float) $inp->quantity_purchased, 1) }} / {{ number_format((float) $inp->quantity_used, 1) }} {{ $inp->unit }}</td>
                                <td class="px-4 py-3 tabular-nums">Rp {{ number_format((float) $inp->unit_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 font-semibold text-charcoal-900 tabular-nums">Rp {{ number_format((float) $inp->total_cost, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-charcoal-500">Belum ada input produksi tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.portal>

