<x-layouts.portal title="Administrator CMS Overview" portalType="admin">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <x-stat-card label="Produk Katalog" :value="$stats['products']" hint="Modul REGEN aktif" />
        <x-stat-card label="Prototype CMS" :value="$stats['prototypes_published'].' / '.$stats['prototypes_total']" hint="Published / Total" />
        <x-stat-card label="Dokumen & HAKI" :value="$stats['documents_public'].' / '.$stats['documents_total']" hint="Publik / Total Aman" />
        <x-stat-card label="Organisasi & Kebun" :value="$stats['organizations'].' Org • '.$stats['farms'].' Kebun'" hint="Multi-tenant aktif" />
        <x-stat-card label="Permintaan Demo Baru" :value="$stats['new_contacts']" hint="Menunggu tindak lanjut" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-12">
        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-6">
            <h2 class="text-base font-semibold text-charcoal-900">Permintaan Kontak &amp; Demo Terbaru</h2>
            <div class="mt-4 divide-y divide-ivory-200 text-xs">
                @forelse ($recentContacts as $req)
                    <div class="py-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-semibold text-charcoal-900">{{ $req->full_name }} ({{ $req->organization_name ?: 'Individu' }})</span>
                            <x-status-badge :status="$req->status" />
                        </div>
                        <p class="mt-1 text-charcoal-600">{{ \Illuminate\Support\Str::limit($req->message, 110) }}</p>
                    </div>
                @empty
                    <p class="py-4 text-charcoal-500">Belum ada permintaan masuk.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-ivory-300 bg-white p-6 lg:col-span-6">
            <h2 class="text-base font-semibold text-charcoal-900">Jejak Audit Aktivitas Sistem Terbaru</h2>
            <div class="mt-4 divide-y divide-ivory-200 text-xs">
                @forelse ($recentLogs as $log)
                    <div class="flex items-center justify-between gap-2 py-2.5">
                        <div>
                            <p class="font-mono font-semibold text-forest-800">{{ $log->action }}</p>
                            <p class="text-charcoal-500">{{ $log->user?->name ?? 'Guest / Public' }} {{ $log->organization ? '• '.$log->organization->name : '' }}</p>
                        </div>
                        <span class="shrink-0 text-charcoal-500">{{ $log->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="py-4 text-charcoal-500">Belum ada log audit.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.portal>

