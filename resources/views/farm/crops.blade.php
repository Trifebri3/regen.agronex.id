<x-layouts.portal title="Crop Planning & Cultivation Cycles" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.crops.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Jadwalkan Siklus Tanam Baru</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Pilih Lahan *</label>
                    <select name="land_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($lands as $land)
                            <option value="{{ $land->id }}">{{ $land->name }} ({{ $land->farm?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nama Komoditas *</label>
                        <input type="text" name="crop_name" required placeholder="Cabai Rawit / Padi" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Varietas *</label>
                        <input type="text" name="variety" required placeholder="Ori 212 / Inpari 32" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal Tanam *</label>
                        <input type="date" name="planting_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Estimasi Panen *</label>
                        <input type="date" name="estimated_harvest_date" value="{{ now()->addDays(90)->format('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Luas Tanam (Ha) *</label>
                        <input type="number" step="0.01" name="planting_area_ha" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kebutuhan Benih *</label>
                        <input type="number" step="0.01" name="seed_requirement" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Satuan</label>
                        <select name="seed_unit" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="kg">kg</option>
                            <option value="gram">gram</option>
                            <option value="pack">pack</option>
                            <option value="bibit">bibit</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Status Budidaya *</label>
                        <select name="cultivation_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="planned">Planned</option>
                            <option value="land_preparation">Land Preparation</option>
                            <option value="planted">Planted</option>
                            <option value="vegetative">Vegetative</option>
                            <option value="generative">Generative</option>
                            <option value="harvesting">Harvesting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Target Panen (kg)</label>
                        <input type="number" step="1" name="target_yield_kg" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Siklus Tanam</button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-8">
            @forelse ($crops as $crop)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-forest-800">{{ $crop->cycle_code }} &bull; {{ $crop->farm?->name }}</span>
                            <h3 class="text-base font-semibold text-charcoal-900">{{ $crop->crop_name }} &mdash; Varietas {{ $crop->variety }}</h3>
                            <p class="text-xs text-charcoal-600">
                                Lahan: {{ $crop->land?->name }} &bull; Luas: {{ number_format((float) $crop->planting_area_ha, 2) }} Ha &bull; Benih: {{ number_format((float) $crop->seed_requirement, 2) }} {{ $crop->seed_unit }}
                            </p>
                            <p class="mt-0.5 text-xs text-charcoal-500">
                                Kalender: Tanam {{ $crop->planting_date?->format('d M Y') }} &rarr; Est. Panen {{ $crop->estimated_harvest_date?->format('d M Y') }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('app.crops.update', $crop) }}" class="flex items-center gap-2 text-xs">
                            @csrf
                            @method('PATCH')
                            <select name="cultivation_status" class="rounded-xl border border-ivory-300 px-2.5 py-1.5">
                                @foreach (['planned', 'land_preparation', 'planted', 'vegetative', 'generative', 'harvesting', 'completed', 'failed'] as $st)
                                    <option value="{{ $st }}" @selected($crop->cultivation_status === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-forest-900 px-3 py-1.5 font-semibold text-white">Update</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada siklus tanaman tercatat.</div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>

