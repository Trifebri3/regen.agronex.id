<x-layouts.portal title="Device Telemetry & Rule-Based Advisory" portalType="farm" :organization="$organization">
    <div class="mb-6 rounded-2xl border border-olive-200 bg-olive-100/70 p-4 text-xs text-olive-900">
        <strong>Kejujuran Telemetri Perangkat:</strong> Unit tanpa sensor perangkat keras fisik yang terhubung langsung ke broker produksi wajib menggunakan mode <strong>Demo Simulation</strong>. Mesin <strong>Rule-Based Advisory</strong> aktif mengevaluasi pembacaan, sedangkan <strong>AI-Assisted Predictive Advisory</strong> ditandai sebagai <strong>Roadmap</strong>.
    </div>

    <div class="grid gap-8 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-4">
            @if (auth()->user()?->role->canManageFarmStructure())
                <form method="POST" action="{{ route('app.devices.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                    @csrf
                    <h2 class="text-base font-semibold text-charcoal-900">Registrasi Node Perangkat Baru</h2>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kebun *</label>
                        <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            @foreach ($farms as $farm)
                                <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Lahan (Opsional)</label>
                        <select name="land_id" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="">-- Umum Kebun --</option>
                            @foreach ($lands as $land)
                                <option value="{{ $land->id }}">{{ $land->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">UID Perangkat *</label>
                            <input type="text" name="device_uid" required placeholder="RGN-SS-2026-09" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">Lini Produk *</label>
                            <select name="product_line" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                                <option value="soilsense">REGEN SoilSense</option>
                                <option value="watersense">REGEN WaterSense</option>
                                <option value="envirosense">REGEN EnviroSense</option>
                                <option value="terra">REGEN Terra Node</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nama Perangkat *</label>
                        <input type="text" name="name" required placeholder="SoilSense Blok Cabai B2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Mode Koneksi Data *</label>
                        <select name="connection_mode" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="demo_simulation">Demo Simulation (Tanpa Perangkat Fisik)</option>
                            <option value="live_http">Live HTTP Ingestion</option>
                            <option value="live_mqtt">Live LoRaWAN / MQTT Bridge</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Daftarkan Perangkat</button>
                </form>
            @endif

            {{-- Advisory Stream --}}
            <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-base font-semibold text-charcoal-900">Advisory Feed</h2>
                    <x-status-badge status="roadmap" label="AI Advisory: Roadmap" />
                </div>
                <div class="mt-4 space-y-3 text-xs">
                    @forelse ($advisories as $adv)
                        <div class="rounded-xl border border-ivory-200 bg-ivory-50 p-3.5">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-semibold text-charcoal-900">{{ $adv->title }}</span>
                                <x-status-badge :status="$adv->is_demo ? 'demo' : 'live'" :label="$adv->is_demo ? 'Demo' : 'Live'" />
                            </div>
                            <p class="mt-1 text-charcoal-700">{{ $adv->recommendation }}</p>
                            @if ($adv->rationale)
                                <p class="mt-1 text-[11px] text-charcoal-500">{{ $adv->rationale }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-charcoal-500">Belum ada rekomendasi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-5 lg:col-span-8">
            @forelse ($devices as $device)
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-forest-800">{{ $device->device_uid }} &bull; {{ strtoupper($device->product_line) }}</span>
                            <h3 class="text-base font-semibold text-charcoal-900">{{ $device->name }}</h3>
                            <p class="text-xs text-charcoal-500">{{ $device->farm?->name }} {{ $device->land ? '• '.$device->land->name : '' }} &bull; Firmware {{ $device->firmware_version }}</p>
                        </div>
                        <x-status-badge :status="$device->is_demo ? 'demo' : 'live'" :label="$device->is_demo ? 'Demo Simulation Data' : 'Live Connected Device'" />
                    </div>

                    {{-- Reading History Table --}}
                    @if ($device->readings->isNotEmpty())
                        <div class="mt-4 overflow-x-auto rounded-xl border border-ivory-200">
                            <table class="w-full text-left text-xs tabular-nums">
                                <thead class="bg-ivory-50 text-charcoal-600">
                                    <tr>
                                        <th class="px-3 py-2">Waktu</th>
                                        <th class="px-3 py-2">Soil pH</th>
                                        <th class="px-3 py-2">Moisture</th>
                                        <th class="px-3 py-2">EC</th>
                                        <th class="px-3 py-2">Flow (LPM)</th>
                                        <th class="px-3 py-2">Temp / RH</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ivory-200">
                                    @foreach ($device->readings as $rd)
                                        <tr>
                                            <td class="px-3 py-2 text-charcoal-500">{{ $rd->recorded_at?->format('d M H:i') }}</td>
                                            <td class="px-3 py-2">{{ $rd->soil_ph ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $rd->soil_moisture_pct !== null ? $rd->soil_moisture_pct.'%' : '-' }}</td>
                                            <td class="px-3 py-2">{{ $rd->soil_ec_ds_m !== null ? $rd->soil_ec_ds_m.' dS/m' : '-' }}</td>
                                            <td class="px-3 py-2">{{ $rd->water_flow_lpm ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $rd->ambient_temp_c !== null ? $rd->ambient_temp_c.'°C / '.($rd->humidity_pct ?? '-').'%' : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Record Telemetry Form --}}
                    <form method="POST" action="{{ route('app.devices.readings.store', $device) }}" class="mt-4 grid gap-2 sm:grid-cols-6 text-xs">
                        @csrf
                        <input type="number" step="0.01" name="soil_ph" placeholder="pH (6.4)" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                        <input type="number" step="0.1" name="soil_moisture_pct" placeholder="Moisture %" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                        <input type="number" step="0.01" name="soil_ec_ds_m" placeholder="EC dS/m" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                        <input type="number" step="0.1" name="water_flow_lpm" placeholder="Flow LPM" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                        <input type="number" step="0.1" name="ambient_temp_c" placeholder="Suhu °C" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                        <button type="submit" class="rounded-lg bg-forest-900 px-3 py-1.5 font-semibold text-white hover:bg-forest-800">+ Input Telemetri</button>
                    </form>
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada perangkat terdaftar.</div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>

