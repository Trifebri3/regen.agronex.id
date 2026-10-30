<x-layouts.portal title="Land & Plot Management (REGEN Terra)" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        @if (auth()->user()?->role->canManageFarmStructure())
            <div class="lg:col-span-4">
                <form method="POST" action="{{ route('app.lands.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                    @csrf
                    <h2 class="text-base font-semibold text-charcoal-900">Registrasi Lahan Baru</h2>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Pilih Kebun *</label>
                        <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            @foreach ($farms as $farm)
                                <option value="{{ $farm->id }}">{{ $farm->name }} ({{ $farm->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nama Lahan / Blok *</label>
                        <input type="text" name="name" required placeholder="Blok Utara Terasering C" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">Lokasi *</label>
                            <input type="text" name="location" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">Luas (Hektar) *</label>
                            <input type="number" step="0.01" name="area_ha" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">Status Pengelolaan</label>
                            <select name="ownership_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                                <option value="owned">Milik Sendiri (Owned)</option>
                                <option value="leased">Sewa Lahan (Leased)</option>
                                <option value="partnership">Kemitraan (Partnership)</option>
                                <option value="managed">Kelola Operasional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-charcoal-600">pH Dasar Tanah</label>
                            <input type="number" step="0.01" name="soil_ph" placeholder="6.40" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Jenis Tanah</label>
                        <input type="text" name="soil_type" placeholder="Andosol / Latosol / Aluvial" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Catatan Profil Tanah</label>
                        <textarea name="soil_profile_notes" rows="2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Foto Lahan (JPG/PNG/WEBP &le;4MB)</label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" class="mt-1 w-full text-xs">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Data Lahan</button>
                </form>
            </div>
        @endif

        <div class="space-y-5 {{ auth()->user()?->role->canManageFarmStructure() ? 'lg:col-span-8' : 'lg:col-span-12' }}">
            @forelse ($lands as $land)
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <span class="font-mono text-xs font-bold text-forest-800">{{ $land->code }} &bull; {{ $land->farm?->name }}</span>
                            <h3 class="mt-1 text-lg font-semibold text-charcoal-900">{{ $land->name }}</h3>
                            <p class="text-xs text-charcoal-600">
                                Lokasi: {{ $land->location }} &bull; Luas: <strong>{{ number_format((float) $land->area_ha, 2) }} Ha</strong> &bull; Status: {{ ucfirst($land->ownership_status) }}
                            </p>
                            <p class="mt-1 text-xs text-charcoal-500">
                                Profil Tanah: {{ $land->soil_type ?: '-' }} (pH {{ $land->soil_ph ?? '-' }}) &mdash; {{ $land->soil_profile_notes }}
                            </p>
                        </div>
                        @if ($land->resolvedPhotoUrl())
                            <img src="{{ $land->resolvedPhotoUrl() }}" alt="{{ $land->name }}" class="h-20 w-28 rounded-xl border border-ivory-300 object-cover">
                        @endif
                    </div>

                    {{-- Plots --}}
                    <div class="mt-4 border-t border-ivory-200 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-charcoal-500">Pembagian Petak Produksi (Plots: {{ $land->plots->count() }})</p>
                        <div class="mt-2 grid gap-2.5 sm:grid-cols-2">
                            @foreach ($land->plots as $plot)
                                <div class="rounded-xl border border-ivory-200 bg-ivory-50 p-3 text-xs">
                                    <p class="font-semibold text-charcoal-900">{{ $plot->plot_code }} &mdash; {{ $plot->name }} ({{ number_format((float) $plot->area_ha, 2) }} Ha)</p>
                                    <p class="mt-0.5 text-charcoal-600">{{ $plot->soil_condition ?: '-' }} &bull; Irigasi: {{ $plot->irrigation_type ?: '-' }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if (auth()->user()?->role->canManageFarmStructure())
                            <form method="POST" action="{{ route('app.lands.plots.store', $land) }}" class="mt-3 grid gap-2 sm:grid-cols-5 text-xs">
                                @csrf
                                <input type="text" name="plot_code" required placeholder="Kode (PLT-B1)" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                                <input type="text" name="name" required placeholder="Nama Petak" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                                <input type="number" step="0.01" name="area_ha" required placeholder="Luas Ha" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                                <input type="text" name="irrigation_type" placeholder="Sistem Irigasi" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                                <button type="submit" class="rounded-lg bg-sage-600 px-3 py-1.5 font-semibold text-white hover:bg-forest-800">+ Bagi Petak</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada lahan terdaftar untuk organisasi ini.</div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>

