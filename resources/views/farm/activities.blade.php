<x-layouts.portal title="Farming Activity & Irrigation Logs" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('app.activities.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Catat Aktivitas Lapangan</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kebun *</label>
                    <select name="farm_id" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($farms as $farm)
                            <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Siklus Tanaman (Opsional)</label>
                    <select name="crop_cycle_id" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="">-- Umum Kebun --</option>
                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}">{{ $crop->crop_name }} ({{ $crop->cycle_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Jenis Aktivitas *</label>
                        <select name="activity_type" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="land_preparation">Persiapan Lahan</option>
                            <option value="planting">Penanaman</option>
                            <option value="irrigation">Irigasi / Penyiraman</option>
                            <option value="fertilization">Pemupukan</option>
                            <option value="pest_control">Pengendalian Hama (PHT)</option>
                            <option value="maintenance">Perawatan / Penyiangan</option>
                            <option value="harvest">Panen</option>
                            <option value="daily_log">Log Harian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal *</label>
                        <input type="date" name="activity_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Judul Aktivitas *</label>
                    <input type="text" name="title" required placeholder="Pemupukan Susulan NPK Kocor Petak A1" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Jumlah Pekerja</label>
                        <input type="number" name="labor_count" value="0" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Volume Air (Liter)</label>
                        <input type="number" step="1" name="water_volume_liters" placeholder="Khusus irigasi" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Biaya Operasional Aktivitas (Rp)</label>
                    <input type="number" step="100" name="cost_incurred" value="0" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Catatan</label>
                    <textarea name="notes" rows="2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Log Aktivitas</button>
            </form>
        </div>

        <div class="space-y-3 lg:col-span-8">
            @forelse ($activities as $act)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="rounded bg-sage-100 px-2.5 py-0.5 font-semibold uppercase text-forest-800">{{ str_replace('_', ' ', $act->activity_type) }}</span>
                        <span class="text-charcoal-500">{{ $act->activity_date?->format('d M Y') }} &bull; {{ $act->farm?->name }}</span>
                    </div>
                    <h3 class="mt-2 text-sm font-semibold text-charcoal-900">{{ $act->title }}</h3>
                    <p class="mt-1 text-xs text-charcoal-600">
                        Pekerja: {{ $act->labor_count }} orang
                        @if ($act->water_volume_liters) &bull; Air: {{ number_format((float) $act->water_volume_liters, 0, ',', '.') }} L @endif
                        &bull; Biaya: Rp {{ number_format((float) $act->cost_incurred, 0, ',', '.') }}
                    </p>
                    @if ($act->notes)
                        <p class="mt-1.5 text-xs text-charcoal-500">{{ $act->notes }}</p>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada log aktivitas.</div>
            @endforelse
            {{ $activities->links() }}
        </div>
    </div>
</x-layouts.portal>

