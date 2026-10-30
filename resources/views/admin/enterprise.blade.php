<x-layouts.portal title="Enterprise Organization Management" portalType="admin">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('admin.enterprise.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Daftarkan Organisasi Tenant Baru</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Nama Organisasi *</label>
                    <input type="text" name="name" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kode Organisasi *</label>
                    <input type="text" name="code" required placeholder="ORG-MTR-03" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Sektor Industri *</label>
                    <input type="text" name="industry_type" required value="Agribisnis Terpadu" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Tier Paket *</label>
                    <select name="subscription_tier" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="enterprise">Enterprise Multi-Farm</option>
                        <option value="integrated">Integrated Solution</option>
                        <option value="modular">Modular Deployment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Email Kontak</label>
                    <input type="email" name="contact_email" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Buat Tenant Terisolasi</button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-8">
            @foreach ($organizations as $org)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-forest-800">{{ $org->code }}</span>
                            <h3 class="text-base font-semibold text-charcoal-900">{{ $org->name }}</h3>
                        </div>
                        <span class="rounded-full bg-sage-100 px-3 py-0.5 text-xs font-semibold uppercase text-forest-800">{{ $org->subscription_tier }}</span>
                    </div>
                    <p class="mt-1 text-xs text-charcoal-500">{{ $org->industry_type }} &bull; {{ $org->contact_email }}</p>
                    <div class="mt-4 grid grid-cols-4 gap-2 border-t border-ivory-200 pt-3 text-center text-xs">
                        <div><strong class="text-charcoal-900">{{ $org->users_count }}</strong> Pengguna</div>
                        <div><strong class="text-charcoal-900">{{ $org->farms_count }}</strong> Kebun</div>
                        <div><strong class="text-charcoal-900">{{ $org->lands_count }}</strong> Lahan</div>
                        <div><strong class="text-charcoal-900">{{ $org->devices_count }}</strong> Device</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.portal>

