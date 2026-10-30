<x-layouts.portal title="Centralized Farm Registry" portalType="admin">
    <div class="overflow-x-auto rounded-2xl border border-ivory-300 bg-white">
        <table class="w-full text-left text-xs">
            <thead class="border-b border-ivory-200 bg-ivory-50 text-charcoal-600 uppercase">
                <tr>
                    <th class="px-4 py-3">Kode &amp; Nama Kebun</th>
                    <th class="px-4 py-3">Organisasi (Tenant)</th>
                    <th class="px-4 py-3">Lokasi</th>
                    <th class="px-4 py-3">Luas (Ha)</th>
                    <th class="px-4 py-3">Lahan / Siklus / Device</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ivory-200">
                @foreach ($farms as $farm)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-charcoal-900">{{ $farm->code }} &mdash; {{ $farm->name }}</td>
                        <td class="px-4 py-3">{{ $farm->organization?->name }}</td>
                        <td class="px-4 py-3">{{ $farm->location }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ number_format((float) $farm->total_area_ha, 2) }} Ha</td>
                        <td class="px-4 py-3">{{ $farm->lands_count }} Lahan &bull; {{ $farm->crop_cycles_count }} Siklus &bull; {{ $farm->devices_count }} Node</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.portal>

