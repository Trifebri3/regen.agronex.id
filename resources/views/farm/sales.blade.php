<x-layouts.portal title="Sales & Offtaker Transactions" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.sales.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Catat Penjualan Hasil Panen</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kebun *</label>
                    <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Nama Pembeli / Offtaker *</label>
                    <input type="text" name="buyer_name" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tipe Pembeli</label>
                        <select name="buyer_type" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="offtaker">Kontrak Offtaker</option>
                            <option value="wholesaler">Pasar Induk / Grosir</option>
                            <option value="retail">Ritel Modern</option>
                            <option value="cooperative">Koperasi</option>
                            <option value="direct">Konsumen Langsung</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kontak Pembeli</label>
                        <input type="text" name="buyer_contact" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Nama Produk &amp; Grade *</label>
                    <input type="text" name="product_name" required placeholder="Cabai Merah Pilar Grade A" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kuantitas Jual (kg) *</label>
                        <input type="number" step="0.1" name="quantity_sold_kg" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Harga per kg (Rp) *</label>
                        <input type="number" step="50" name="price_per_kg" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Biaya Distribusi (Rp)</label>
                        <input type="number" step="100" name="distribution_cost" value="0" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Status Pembayaran</label>
                        <select name="payment_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="paid">Lunas (Paid)</option>
                            <option value="partial">Sebagian (Partial)</option>
                            <option value="pending">Piutang (Pending)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Tanggal Transaksi *</label>
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Penjualan</button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-8">
            @forelse ($sales as $sale)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="font-mono font-bold text-forest-800">{{ $sale->invoice_number }} &bull; {{ $sale->transaction_date?->format('d M Y') }}</span>
                        <x-status-badge :status="$sale->payment_status" />
                    </div>
                    <h3 class="mt-1.5 text-base font-semibold text-charcoal-900">{{ $sale->buyer_name }} &mdash; {{ $sale->product_name }}</h3>
                    <div class="mt-3 grid grid-cols-3 gap-2 rounded-xl bg-ivory-50 p-3 text-xs tabular-nums">
                        <div><span class="block text-charcoal-500">Volume &amp; Harga</span><strong>{{ number_format((float) $sale->quantity_sold_kg, 0, ',', '.') }} kg @ Rp {{ number_format((float) $sale->price_per_kg, 0, ',', '.') }}</strong></div>
                        <div><span class="block text-charcoal-500">Pendapatan Kotor</span><strong>Rp {{ number_format((float) $sale->gross_revenue, 0, ',', '.') }}</strong></div>
                        <div><span class="block text-charcoal-500">Bersih (-Distribusi)</span><strong class="text-forest-800">Rp {{ number_format((float) $sale->net_revenue, 0, ',', '.') }}</strong></div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada transaksi penjualan.</div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>

