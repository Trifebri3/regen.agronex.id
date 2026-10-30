<x-layouts.portal title="REGEN Enterprise — Multi-Farm Organization Command Center" portalType="enterprise" :organization="$organization">
    {{-- Organization Context Header, SuperAdmin Tenant Switcher & Export Actions --}}
    <div class="mb-6 rounded-2xl border border-forest-800 bg-forest-950 p-6 text-ivory-100">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-forest-800 px-3 py-0.5 text-xs font-semibold text-sage-200">
                        Multi-Tenant Isolated Scope
                    </span>
                    <span class="rounded-full border border-forest-700 px-3 py-0.5 text-xs text-ivory-200">
                        {{ $organization->code }} &bull; Tier {{ ucfirst($organization->subscription_tier) }}
                    </span>
                </div>
                <h2 class="mt-3 text-xl font-bold text-white sm:text-2xl">{{ $organization->name }}</h2>
                <p class="mt-1 text-xs text-sage-200">
                    Hierarki Terisolasi: Organization &rarr; Multi-Farm &rarr; Land / Plot &rarr; IoT Devices, Crop Cycles &amp; Consolidated Ledger.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                @if ($availableOrganizations->isNotEmpty())
                    <form method="GET" action="{{ route('enterprise.dashboard') }}" class="flex items-center gap-2">
                        <select
                            name="organization_id"
                            aria-label="Pilih Organisasi Enterprise"
                            class="rounded-xl border border-forest-700 bg-forest-900 px-3 py-2 text-xs text-white"
                        >
                            @foreach ($availableOrganizations as $orgOption)
                                <option value="{{ $orgOption->id }}" @selected($orgOption->id === $organization->id)>
                                    {{ $orgOption->code }} — {{ $orgOption->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-xl bg-sage-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sage-500">
                            Beralih Tenant
                        </button>
                    </form>
                @endif

                <a
                    href="{{ route('enterprise.export', request()->only('organization_id')) }}"
                    class="rounded-xl bg-white px-4 py-2 text-xs font-semibold text-forest-950 transition hover:bg-ivory-200"
                >
                    Ekspor Laporan Organisasi (CSV)
                </a>
                <a
                    href="{{ route('api.v1.enterprise.summary', request()->only('organization_id')) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-xl border border-sage-400/50 bg-forest-900 px-4 py-2 text-xs font-semibold text-sage-200 transition hover:bg-forest-800 hover:text-white"
                >
                    API JSON Summary (v1)
                </a>
            </div>
        </div>
    </div>

    {{-- Organization Consolidated KPIs --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card
            label="Total Unit Kebun (Multi-Farm)"
            :value="number_format($farms->count(), 0, ',', '.').' Kebun'"
            :hint="number_format((int) $farms->sum('lands_count'), 0, ',', '.').' blok lahan terdaftar'"
            status="operational"
            statusLabel="Isolated"
        />
        <x-stat-card
            label="Luas Lahan Terpetakan"
            :value="number_format($totalMappedHa, 2, ',', '.').' ha'"
            :hint="number_format((int) $farms->sum('active_cycles_count'), 0, ',', '.').' siklus budidaya aktif'"
        />
        <x-stat-card
            label="Armada Perangkat IoT"
            :value="number_format($liveDevicesCount + $demoDevicesCount, 0, ',', '.').' Unit'"
            :hint="$liveDevicesCount.' Live Device • '.$demoDevicesCount.' Demo Simulasi'"
            :status="$demoDevicesCount > 0 && $liveDevicesCount === 0 ? 'demo' : 'operational'"
            :statusLabel="$liveDevicesCount > 0 ? 'Hybrid Fleet' : 'Demo Fleet'"
        />
        <x-stat-card
            label="Estimasi Laba / Rugi Organisasi"
            :value="'Rp '.number_format($estimatedProfitLoss, 0, ',', '.')"
            :hint="'Pendapatan Rp '.number_format($totalRevenue, 0, ',', '.').' • Biaya Rp '.number_format($totalExpenses, 0, ',', '.')"
        />
    </div>

    {{-- Non-Guaranteed Financial Disclaimer --}}
    <div class="mt-4 rounded-2xl border border-olive-200 bg-olive-100/70 px-4 py-3 text-xs text-olive-800">
        <strong>Catatan Tata Kelola Finansial:</strong> Angka pendapatan dan selisih laba/rugi konsolidasi merupakan rekapitulasi catatan buku besar unit kebun dan bukan proyeksi keuntungan yang dijamin.
    </div>

    {{-- Multi-Farm Consolidated Matrix + Add Farm Unit Form --}}
    <div class="mt-8 grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-ivory-200 pb-4">
                    <div>
                        <h3 class="text-base font-semibold text-charcoal-900">Matriks Kinerja Multi-Kebun (Organization Farms)</h3>
                        <p class="text-xs text-charcoal-500">Pemantauan terpusat luas lahan, siklus aktif, armada sensor, panen layak jual, dan buku besar per kebun.</p>
                    </div>
                    <span class="rounded-full bg-ivory-100 px-3 py-1 text-xs font-medium text-charcoal-700">
                        Total Panen Layak Jual: {{ number_format($totalHarvestKg, 0, ',', '.') }} kg
                    </span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-ivory-200 text-left text-xs">
                        <thead>
                            <tr class="text-[11px] font-semibold uppercase tracking-wider text-charcoal-500">
                                <th class="py-2.5 pr-4">Unit Kebun</th>
                                <th class="px-3 py-2.5">Lahan &amp; Luas</th>
                                <th class="px-3 py-2.5">Siklus Aktif</th>
                                <th class="px-3 py-2.5">Perangkat</th>
                                <th class="px-3 py-2.5 text-right">Biaya (Rp)</th>
                                <th class="px-3 py-2.5 text-right">Pendapatan (Rp)</th>
                                <th class="py-2.5 pl-3 text-right">Est. Laba/Rugi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ivory-200">
                            @forelse ($farms as $farm)
                                @php
                                    $farmExpense = (float) ($farm->total_expenses ?? 0);
                                    $farmRevenue = max((float) ($farm->ledger_revenue ?? 0), (float) ($farm->gross_sales_revenue ?? 0));
                                    $farmNet = $farmRevenue - $farmExpense;
                                @endphp
                                <tr class="align-top">
                                    <td class="py-3 pr-4">
                                        <p class="font-semibold text-charcoal-900">{{ $farm->name }}</p>
                                        <p class="text-[11px] text-charcoal-500">{{ $farm->code }} &bull; {{ $farm->location }}, {{ $farm->province }}</p>
                                        @if ($farm->manager)
                                            <p class="mt-0.5 text-[11px] text-forest-700">PIC: {{ $farm->manager->name }}</p>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 tabular-nums">
                                        <span class="font-semibold text-charcoal-900">{{ number_format((float) ($farm->lands_sum_area_ha ?? 0), 2, ',', '.') }} ha</span>
                                        <span class="block text-[11px] text-charcoal-500">{{ (int) ($farm->lands_count ?? 0) }} blok lahan</span>
                                    </td>
                                    <td class="px-3 py-3 tabular-nums">
                                        <span class="font-semibold text-forest-800">{{ (int) ($farm->active_cycles_count ?? 0) }} siklus</span>
                                        <span class="block text-[11px] text-charcoal-500">{{ number_format((float) ($farm->saleable_harvest_kg ?? 0), 0, ',', '.') }} kg panen</span>
                                    </td>
                                    <td class="px-3 py-3 tabular-nums">
                                        <span class="font-medium text-charcoal-800">{{ (int) ($farm->live_devices_count ?? 0) }} Live</span>
                                        <span class="block text-[11px] text-olive-700">{{ (int) ($farm->demo_devices_count ?? 0) }} Demo</span>
                                    </td>
                                    <td class="px-3 py-3 text-right tabular-nums text-charcoal-700">
                                        {{ number_format($farmExpense, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-right tabular-nums text-forest-800 font-medium">
                                        {{ number_format($farmRevenue, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 pl-3 text-right tabular-nums font-semibold {{ $farmNet >= 0 ? 'text-forest-800' : 'text-red-700' }}">
                                        {{ number_format($farmNet, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-charcoal-500">Belum ada unit kebun terdaftar pada organisasi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Register New Farm Unit within Tenant --}}
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('enterprise.farms.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-4">
                @csrf
                @if (request()->filled('organization_id'))
                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                @endif
                <div class="border-b border-ivory-200 pb-3">
                    <h3 class="text-base font-semibold text-charcoal-900">Tambah Unit Kebun Baru</h3>
                    <p class="text-xs text-charcoal-500">Kebun otomatis terisolasi di bawah organisasi {{ $organization->code }}.</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-700">Nama Unit Kebun</label>
                    <input type="text" name="name" required placeholder="Contoh: Kebun Riset Hortikultura Garut" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-700">Lokasi / Kabupaten</label>
                        <input type="text" name="location" required placeholder="Cikajang, Garut" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-700">Provinsi</label>
                        <input type="text" name="province" required placeholder="Jawa Barat" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-charcoal-700">Total Luas Area (ha)</label>
                        <input type="number" step="0.01" min="0.1" name="total_area_ha" required placeholder="15.50" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-charcoal-700">Elevasi (mdpl)</label>
                        <input type="number" min="0" name="elevation_m" placeholder="1150" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs tabular-nums">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-700">Penanggung Jawab (Farm Manager)</label>
                    <select name="manager_id" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="">-- Pilih dari Anggota Organisasi --</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->role->label() }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-charcoal-700">Catatan Operasional</label>
                    <textarea name="notes" rows="2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs" placeholder="Komoditas fokus atau skema kemitraan..."></textarea>
                </div>

                <button type="submit" class="w-full rounded-xl bg-forest-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-forest-800">
                    Daftarkan Unit Kebun Enterprise
                </button>
            </form>
        </div>
    </div>

    {{-- Centralized IoT Fleet Status & Telemetry Honesty Matrix --}}
    <div class="mt-8 rounded-2xl border border-ivory-300 bg-white p-6">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-ivory-200 pb-4">
            <div>
                <h3 class="text-base font-semibold text-charcoal-900">Monitoring Terpusat Armada Perangkat (Multi-Device Fleet)</h3>
                <p class="text-xs text-charcoal-500">Setiap perangkat menampilkan status koneksi jujur antara perangkat fisik terhubung (Live) dan unit simulasi (Demo).</p>
            </div>
            <a href="{{ route('app.devices.index') }}" class="rounded-xl border border-forest-800 px-3.5 py-1.5 text-xs font-semibold text-forest-800 hover:bg-forest-900 hover:text-white">
                Kelola Perangkat &amp; Ingesti Sensor &rarr;
            </a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-ivory-200 text-left text-xs">
                <thead>
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-charcoal-500">
                        <th class="py-2.5 pr-4">UID &amp; Nama Perangkat</th>
                        <th class="px-3 py-2.5">Lini Produk</th>
                        <th class="px-3 py-2.5">Lokasi Kebun / Lahan</th>
                        <th class="px-3 py-2.5">Mode Data</th>
                        <th class="px-3 py-2.5">Kesehatan Unit</th>
                        <th class="py-2.5 pl-3">Telemetri Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ivory-200">
                    @forelse ($devices as $device)
                        <tr>
                            <td class="py-3 pr-4">
                                <p class="font-mono font-semibold text-charcoal-900">{{ $device->device_uid }}</p>
                                <p class="text-[11px] text-charcoal-500">{{ $device->name }} &bull; FW {{ $device->firmware_version }}</p>
                            </td>
                            <td class="px-3 py-3 uppercase font-semibold text-forest-800">{{ $device->product_line }}</td>
                            <td class="px-3 py-3">
                                <p class="font-medium text-charcoal-800">{{ $device->farm?->name ?? '-' }}</p>
                                <p class="text-[11px] text-charcoal-500">{{ $device->land?->name ?? 'Level Kebun' }}</p>
                            </td>
                            <td class="px-3 py-3">
                                <x-status-badge
                                    :status="$device->is_demo ? 'demo' : 'live'"
                                    :label="$device->is_demo ? 'Demo / Data Simulasi' : 'Live Telemetry'"
                                />
                            </td>
                            <td class="px-3 py-3 tabular-nums text-charcoal-700">
                                {{ strtoupper($device->status) }} &bull; Baterai {{ $device->battery_level ?? '-' }}%
                            </td>
                            <td class="py-3 pl-3 text-[11px] text-charcoal-600 tabular-nums">
                                @if ($device->latestReading)
                                    @if ($device->latestReading->soil_ph !== null)
                                        pH {{ $device->latestReading->soil_ph }} &bull; Moist {{ $device->latestReading->soil_moisture_pct ?? '-' }}%
                                    @elseif ($device->latestReading->water_flow_lpm !== null)
                                        Flow {{ $device->latestReading->water_flow_lpm }} L/m &bull; Lvl {{ $device->latestReading->water_level_cm ?? '-' }} cm
                                    @elseif ($device->latestReading->ambient_temp_c !== null)
                                        {{ $device->latestReading->ambient_temp_c }}&deg;C &bull; RH {{ $device->latestReading->humidity_pct ?? '-' }}%
                                    @else
                                        Terekam {{ $device->latestReading->recorded_at?->diffForHumans() }}
                                    @endif
                                @else
                                    Belum ada pembacaan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-charcoal-500">Belum ada perangkat IoT yang terdaftar pada organisasi ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RBAC Members, Audit Trail & Enterprise API Readiness --}}
    <div class="mt-8 grid gap-8 lg:grid-cols-12">
        {{-- Organization Users & RBAC --}}
        <div class="lg:col-span-5">
            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <h3 class="text-base font-semibold text-charcoal-900">Personel Organisasi &amp; Hak Akses (RBAC)</h3>
                <p class="mt-0.5 text-xs text-charcoal-500">Pengguna yang terikat pada tenant {{ $organization->name }}.</p>

                <ul class="mt-4 divide-y divide-ivory-200 text-xs">
                    @forelse ($members as $member)
                        <li class="flex items-center justify-between gap-3 py-2.5">
                            <div>
                                <p class="font-semibold text-charcoal-900">{{ $member->name }}</p>
                                <p class="text-[11px] text-charcoal-500">{{ $member->email }} {{ $member->job_title ? '• '.$member->job_title : '' }}</p>
                            </div>
                            <span class="rounded-full bg-ivory-100 border border-ivory-300 px-2.5 py-0.5 text-[11px] font-medium text-forest-800">
                                {{ $member->role->label() }}
                            </span>
                        </li>
                    @empty
                        <li class="py-4 text-charcoal-500">Belum ada anggota terdaftar pada organisasi ini.</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6 rounded-2xl border border-ivory-300 bg-white p-6">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-charcoal-900">Kesiapan Integrasi API Enterprise</h3>
                    <x-status-badge status="operational" label="REST JSON v1" />
                </div>
                <p class="mt-1 text-xs text-charcoal-600">
                    Endpoint ringkasan organisasi terisolasi siap diintegrasikan dengan ERP agribisnis internal:
                </p>
                <div class="mt-3 rounded-xl bg-charcoal-950 p-3 font-mono text-[11px] text-sage-200 overflow-x-auto">
                    GET {{ url('/api/v1/enterprise/summary') }}<br>
                    GET {{ url('/api/v1/health') }}
                </div>
                <div class="mt-3 flex items-center justify-between text-[11px] text-charcoal-500">
                    <span>Webhook ERP &amp; OAuth2 Machine-to-Machine</span>
                    <x-status-badge status="roadmap" label="Roadmap Q2" />
                </div>
            </div>
        </div>

        {{-- Organization Audit Trail --}}
        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <div class="flex items-center justify-between border-b border-ivory-200 pb-3">
                    <div>
                        <h3 class="text-base font-semibold text-charcoal-900">Jejak Audit Organisasi (Audit Trail)</h3>
                        <p class="text-xs text-charcoal-500">Log aktivitas perubahan struktur kebun, perangkat, dan transaksi dalam ruang lingkup tenant.</p>
                    </div>
                    <span class="text-[11px] font-medium text-forest-700">{{ $auditLogs->count() }} log terbaru</span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-ivory-200 text-left text-xs">
                        <thead>
                            <tr class="text-[11px] font-semibold uppercase tracking-wider text-charcoal-500">
                                <th class="py-2 pr-3">Waktu</th>
                                <th class="px-3 py-2">Aktor</th>
                                <th class="px-3 py-2">Aksi</th>
                                <th class="py-2 pl-3">Target Entitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ivory-200">
                            @forelse ($auditLogs as $log)
                                <tr>
                                    <td class="py-2.5 pr-3 whitespace-nowrap text-[11px] text-charcoal-500 tabular-nums">
                                        {{ $log->created_at?->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span class="font-medium text-charcoal-900">{{ $log->user?->name ?? 'System' }}</span>
                                    </td>
                                    <td class="px-3 py-2.5 font-mono text-[11px] text-forest-800">
                                        {{ $log->action }}
                                    </td>
                                    <td class="py-2.5 pl-3 text-[11px] text-charcoal-600">
                                        {{ $log->auditable_type ? class_basename($log->auditable_type).' #'.$log->auditable_id : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-charcoal-500">Belum ada catatan jejak audit pada organisasi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.portal>

