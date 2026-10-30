<x-layouts.portal title="Platform Reports & Backup Recovery" portalType="admin">
    <div class="rounded-2xl border border-ivory-300 bg-white p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-charcoal-900">Ekspor Manifes Cadangan Sistem (System Backup JSON)</h2>
                <p class="mt-1 text-xs text-charcoal-600">Unduh snapshot metadata katalog produk, konten CMS, prototipe, dokumen pendukung, dan ringkasan multi-organisasi untuk pemulihan &amp; audit berkala.</p>
            </div>
            <a href="{{ route('admin.reports.export') }}" class="rounded-xl bg-forest-900 px-4 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">
                Unduh Backup Manifest (.json)
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto rounded-2xl border border-ivory-300 bg-white">
        <table class="w-full text-left text-xs">
            <thead class="border-b border-ivory-200 bg-ivory-50 uppercase text-charcoal-600">
                <tr>
                    <th class="px-4 py-3">Organisasi</th>
                    <th class="px-4 py-3">Tier</th>
                    <th class="px-4 py-3">Kebun</th>
                    <th class="px-4 py-3">Lahan</th>
                    <th class="px-4 py-3">Siklus Tanam</th>
                    <th class="px-4 py-3">Perangkat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ivory-200">
                @foreach ($organizations as $org)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-charcoal-900">{{ $org->name }}</td>
                        <td class="px-4 py-3 uppercase">{{ $org->subscription_tier }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $org->farms_count }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $org->lands_count }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $org->crop_cycles_count }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $org->devices_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.portal>

