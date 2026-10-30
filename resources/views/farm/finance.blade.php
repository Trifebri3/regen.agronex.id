<x-layouts.portal title="Farm Financial Ledger" portalType="farm" :organization="$organization">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Modal Awal Tercatat" :value="'Rp '.number_format($totalCapital, 0, ',', '.')" />
        <x-stat-card label="Total Biaya Produksi" :value="'Rp '.number_format($totalExpense, 0, ',', '.')" />
        <x-stat-card label="Total Penerimaan" :value="'Rp '.number_format($totalRevenue, 0, ',', '.')" />
        <x-stat-card
            label="Laba / Rugi Bersih"
            :value="'Rp '.number_format($netProfitLoss, 0, ',', '.')"
            :status="$netProfitLoss >= 0 ? 'operational' : 'critical'"
            :statusLabel="$netProfitLoss >= 0 ? 'Laba' : 'Rugi'"
        />
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.finance.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Tambah Catatan Keuangan</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kebun *</label>
                    <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tipe Transaksi *</label>
                        <select name="transaction_type" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="expense">Pengeluaran (Expense)</option>
                            <option value="revenue">Pendapatan (Revenue)</option>
                            <option value="capital">Modal Awal (Capital)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Sifat Biaya *</label>
                        <select name="cost_nature" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="variable">Biaya Variabel</option>
                            <option value="fixed">Biaya Tetap</option>
                            <option value="revenue">Penerimaan</option>
                            <option value="capital">Ekuitas / Modal</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kategori Pos Keuangan *</label>
                    <select name="category" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="land_preparation">Biaya Persiapan Lahan</option>
                        <option value="input_cost">Biaya Input Saprotan</option>
                        <option value="labor_cost">Biaya Tenaga Kerja</option>
                        <option value="operational_cost">Biaya Operasional</option>
                        <option value="equipment_cost">Biaya Peralatan / Penyusutan</option>
                        <option value="transportation_cost">Biaya Transportasi</option>
                        <option value="sales_revenue">Pendapatan Penjualan</option>
                        <option value="initial_capital">Modal Awal</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Uraian Transaksi *</label>
                    <input type="text" name="title" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nominal (Rp) *</label>
                        <input type="number" step="100" name="amount" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal *</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Transaksi</button>
            </form>
        </div>

        <div class="lg:col-span-8">
            <div class="overflow-x-auto rounded-2xl border border-ivory-300 bg-white">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-ivory-200 bg-ivory-50 uppercase text-charcoal-600">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Uraian &amp; Kategori</th>
                            <th class="px-4 py-3">Sifat</th>
                            <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ivory-200">
                        @forelse ($records as $rec)
                            <tr>
                                <td class="px-4 py-3 text-charcoal-500">{{ $rec->transaction_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-charcoal-900">{{ $rec->title }}</p>
                                    <p class="text-charcoal-500">{{ str_replace('_', ' ', $rec->category) }} &bull; {{ $rec->farm?->name }}</p>
                                </td>
                                <td class="px-4 py-3 uppercase">{{ $rec->cost_nature }}</td>
                                <td class="px-4 py-3 text-right font-semibold tabular-nums {{ $rec->transaction_type === 'revenue' ? 'text-forest-800' : ($rec->transaction_type === 'expense' ? 'text-red-700' : 'text-charcoal-800') }}">
                                    {{ $rec->transaction_type === 'expense' ? '-' : '' }}Rp {{ number_format((float) $rec->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-charcoal-500">Belum ada buku besar keuangan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.portal>

