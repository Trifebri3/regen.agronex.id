<x-layouts.portal title="Farm Management Dashboard" portalType="farm" :organization="$organization">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total Kebun & Luas Lahan" :value="$totalFarms.' Kebun • '.number_format($totalLandArea, 2).' Ha'" hint="Aset lahan terdaftar" status="operational" />
        <x-stat-card label="Siklus Budidaya Aktif" :value="$activeCycles->count().' Siklus'" :hint="$activeCycles->pluck('crop_name')->unique()->implode(', ') ?: 'Belum ada siklus'" status="operational" />
        <x-stat-card label="Ringkasan Panen Layak Jual" :value="number_format($saleableHarvestKg, 0, ',', '.').' kg'" :hint="'Bruto: '.number_format($totalHarvestKg, 0, ',', '.').' kg'" status="operational" />
        <x-stat-card
            label="Laba / Rugi Operasional"
            :value="'Rp '.number_format($profitLoss, 0, ',', '.')"
            :hint="'Pendapatan Rp '.number_format($effectiveRevenue, 0, ',', '.').' • Biaya Rp '.number_format($totalExpenses, 0, ',', '.')"
            :status="$profitLoss >= 0 ? 'operational' : 'critical'"
            :statusLabel="$profitLoss >= 0 ? 'Surplus' : 'Defisit'"
        />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-12">
        {{-- Active Crops --}}
        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-7">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-charcoal-900">Siklus Tanaman Aktif</h2>
                <a href="{{ route('app.crops.index') }}" class="text-xs font-semibold text-forest-800 hover:underline">Kelola Siklus &rarr;</a>
            </div>
            <div class="mt-4 divide-y divide-ivory-200 text-xs">
                @forelse ($activeCycles as $cyc)
                    <div class="flex flex-wrap items-center justify-between gap-2 py-3">
                        <div>
                            <p class="text-sm font-semibold text-charcoal-900">{{ $cyc->crop_name }} ({{ $cyc->variety }})</p>
                            <p class="text-charcoal-500">{{ $cyc->farm?->name }} &bull; {{ $cyc->land?->name }} &bull; {{ number_format((float) $cyc->planting_area_ha, 2) }} Ha</p>
                        </div>
                        <div class="text-right">
                            <x-status-badge :status="$cyc->cultivation_status" />
                            <p class="mt-1 text-charcoal-500">Est. Panen: {{ $cyc->estimated_harvest_date?->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-charcoal-500">Belum ada siklus tanaman aktif.</p>
                @endforelse
            </div>
        </div>

        {{-- Alerts & Advisories --}}
        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-5">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-charcoal-900">Peringatan &amp; Rule-Based Advisory</h2>
                <a href="{{ route('app.devices.index') }}" class="text-xs font-semibold text-forest-800 hover:underline">Telemetry &rarr;</a>
            </div>
            <div class="mt-4 space-y-3 text-xs">
                @forelse ($advisories as $adv)
                    <div class="rounded-xl border border-ivory-200 bg-ivory-50 p-3.5">
                        <div class="flex flex-wrap items-center justify-between gap-1">
                            <span class="font-semibold text-charcoal-900">{{ $adv->title }}</span>
                            <x-status-badge :status="$adv->is_demo ? 'demo' : 'live'" :label="$adv->is_demo ? 'Demo Data' : 'Live'" />
                        </div>
                        <p class="mt-1 text-charcoal-600">{{ $adv->recommendation }}</p>
                    </div>
                @empty
                    <p class="py-3 text-charcoal-500">Tidak ada peringatan aktif.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Activities & Sensor Snapshot --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-7">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-charcoal-900">Log Aktivitas Budidaya Terbaru</h2>
                <a href="{{ route('app.activities.index') }}" class="text-xs font-semibold text-forest-800 hover:underline">Catat Aktivitas &rarr;</a>
            </div>
            <div class="mt-4 divide-y divide-ivory-200 text-xs">
                @forelse ($recentActivities as $act)
                    <div class="flex items-center justify-between gap-2 py-2.5">
                        <div>
                            <p class="font-semibold text-charcoal-900">{{ $act->title }}</p>
                            <p class="text-charcoal-500">{{ ucfirst(str_replace('_', ' ', $act->activity_type)) }} &bull; {{ $act->farm?->name }}</p>
                        </div>
                        <span class="shrink-0 text-charcoal-500">{{ $act->activity_date?->format('d M Y') }}</span>
                    </div>
                @empty
                    <p class="py-4 text-charcoal-500">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-5">
            <h2 class="text-base font-semibold text-charcoal-900">Status Perangkat Sensor</h2>
            <div class="mt-4 space-y-3 text-xs">
                @forelse ($devices as $dev)
                    <div class="flex items-center justify-between rounded-xl border border-ivory-200 p-3">
                        <div>
                            <p class="font-semibold text-charcoal-900">{{ $dev->name }}</p>
                            <p class="font-mono text-[11px] text-charcoal-500">{{ $dev->device_uid }}</p>
                        </div>
                        <x-status-badge :status="$dev->is_demo ? 'demo' : 'live'" :label="$dev->is_demo ? 'Demo' : 'Live'" />
                    </div>
                @empty
                    <p class="text-charcoal-500">Belum ada perangkat sensor terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.portal>

