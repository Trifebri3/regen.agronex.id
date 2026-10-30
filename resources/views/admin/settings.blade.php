<x-layouts.portal title="Platform Settings & Security Audit Trail" portalType="admin">
    <div class="rounded-2xl border border-ivory-300 bg-white p-6">
        <h2 class="text-base font-semibold text-charcoal-900">Konfigurasi Keamanan Ekosistem REGEN</h2>
        <dl class="mt-4 grid gap-4 text-xs sm:grid-cols-3">
            <div class="rounded-xl bg-ivory-50 p-3.5">
                <dt class="text-charcoal-500">Domain Produk Utama</dt>
                <dd class="mt-1 font-semibold text-charcoal-900">https://regen.agronex.id</dd>
            </div>
            <div class="rounded-xl bg-ivory-50 p-3.5">
                <dt class="text-charcoal-500">Penyimpanan Dokumen HAKI/Privat</dt>
                <dd class="mt-1 font-semibold text-forest-800">Private Local Disk (Non-Public URL)</dd>
            </div>
            <div class="rounded-xl bg-ivory-50 p-3.5">
                <dt class="text-charcoal-500">Induk Ekosistem</dt>
                <dd class="mt-1 font-semibold text-charcoal-900">AGRONEX (https://agronex.id)</dd>
            </div>
        </dl>
    </div>

    <div class="mt-6 overflow-x-auto rounded-2xl border border-ivory-300 bg-white">
        <table class="w-full text-left text-xs">
            <thead class="border-b border-ivory-200 bg-ivory-50 uppercase text-charcoal-600">
                <tr>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Pengguna</th>
                    <th class="px-4 py-3">Organisasi</th>
                    <th class="px-4 py-3">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ivory-200">
                @foreach ($auditLogs as $log)
                    <tr>
                        <td class="px-4 py-3 text-charcoal-500">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-3 font-mono font-semibold text-forest-800">{{ $log->action }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? 'Guest' }}</td>
                        <td class="px-4 py-3">{{ $log->organization?->name ?? 'Global' }}</td>
                        <td class="px-4 py-3 font-mono">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.portal>

