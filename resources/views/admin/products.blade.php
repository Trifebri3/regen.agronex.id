<x-layouts.portal title="Product Catalog Management" portalType="admin">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <form method="POST" action="{{ route('admin.products.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-4">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Tambah Modul Produk REGEN</h2>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nama Produk *</label>
                        <input type="text" name="name" required placeholder="REGEN CropSense" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kode *</label>
                        <input type="text" name="code" required placeholder="RGN-CS" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Tagline *</label>
                    <input type="text" name="tagline" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kategori</label>
                        <select name="category" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="hardware_iot">Hardware IoT</option>
                            <option value="spatial">Spatial</option>
                            <option value="intelligence">Intelligence</option>
                            <option value="software">Software</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Status Kesiapan (Jujur)</label>
                        <select name="readiness_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="operational">Operational</option>
                            <option value="pilot_demo">Pilot / Demo Simulasi</option>
                            <option value="roadmap">Roadmap (Planned)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Ringkasan Singkat *</label>
                    <textarea name="short_description" rows="2" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Deskripsi Lengkap *</label>
                    <textarea name="description" rows="3" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Daftar Fitur (Satu per baris) *</label>
                    <textarea name="features_text" rows="3" required placeholder="Pemantauan kanopi&#10;Integrasi modular" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                </div>
                <label class="flex items-center gap-2 text-xs">
                    <input type="checkbox" name="is_published" value="1" checked>
                    <span>Publikasikan di Katalog Website</span>
                </label>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Simpan Produk</button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-7">
            @foreach ($products as $prod)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <form method="POST" action="{{ route('admin.products.update', $prod) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="font-mono text-xs font-bold text-forest-800">{{ $prod->code }} &bull; /solutions/{{ $prod->slug }}</span>
                            <x-status-badge :status="$prod->readiness_status" />
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="text" name="name" value="{{ $prod->name }}" required class="rounded-xl border border-ivory-300 px-3 py-1.5 text-xs font-semibold">
                            <select name="readiness_status" class="rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                                <option value="operational" @selected($prod->readiness_status === 'operational')>Operational</option>
                                <option value="pilot_demo" @selected($prod->readiness_status === 'pilot_demo')>Pilot / Demo Simulasi</option>
                                <option value="roadmap" @selected($prod->readiness_status === 'roadmap')>Roadmap</option>
                            </select>
                        </div>
                        <input type="text" name="tagline" value="{{ $prod->tagline }}" required class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                        <textarea name="short_description" rows="2" required class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">{{ $prod->short_description }}</textarea>
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 text-xs">
                                <input type="checkbox" name="is_published" value="1" @checked($prod->is_published)>
                                <span>Published</span>
                            </label>
                            <button type="submit" class="rounded-lg bg-forest-900 px-3.5 py-1.5 text-xs font-semibold text-white">Update</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.portal>

