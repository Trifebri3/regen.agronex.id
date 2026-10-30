<x-layouts.public title="About REGEN — Produk Unggulan Teknologi Pertanian AGRONEX">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Tentang Produk &amp; Visi Ekosistem</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                {{ $aboutContent?->title ?? 'Tentang REGEN oleh AGRONEX' }}
            </h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-sage-200">
                {{ $aboutContent?->subtitle ?? 'Grow Smarter. Manage Better. Regenerate the Future.' }}
            </p>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-start gap-12 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-7">
                    <h2 class="text-2xl font-semibold text-charcoal-900">Produk Unggulan Mandiri dari Ekosistem AGRONEX</h2>
                    <p class="text-base leading-relaxed text-charcoal-700">
                        {{ $aboutContent?->body ?? 'REGEN merupakan produk teknologi pertanian unggulan dari AGRONEX (https://agronex.id).' }}
                    </p>
                    <p class="text-sm leading-relaxed text-charcoal-600">
                        Website dan platform digital <strong>REGEN (regen.agronex.id)</strong> berdiri secara khusus sebagai ekosistem produk mandiri — mencakup katalog solusi modular, aplikasi operasional manajemen kebun, kalkulator intelijen bisnis tani, dokumentasi riset prototipe, repositori HAKI, serta arsitektur multi-tenant enterprise — tanpa mencampuradukkan konten profil korporat umum dari situs utama <a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-semibold text-forest-800 underline">agronex.id</a>.
                    </p>

                    <div class="rounded-2xl border-2 border-forest-800 bg-forest-950 p-5 text-ivory-100 shadow-sm">
                        <span class="rounded-full bg-sage-400 px-3 py-0.5 text-[11px] font-bold uppercase tracking-wider text-forest-950">
                            Model Penyediaan &bull; Bundling &amp; Starter
                        </span>
                        <h3 class="mt-2 text-base font-bold text-white">REGEN sebagai Produk Bundling &amp; Paket Starter Unggulan</h3>
                        <p class="mt-1 text-xs leading-relaxed text-sage-200">
                            Untuk mempercepat modernisasi agribisnis petani di Indonesia, REGEN dipasarkan melalui <strong>Paket Starter</strong> (paket masuk ramah biaya modal awal menggabungkan probe sensor dan akses platform) serta <strong>Paket Bundling Terintegrasi</strong> bagi kelompok tani, koperasi, dan korporasi perkebunan modern.
                        </p>
                    </div>

                    <div class="grid gap-4 pt-4 sm:grid-cols-3">
                        <x-stat-card label="Modul Produk" :value="$productsCount.' Modul'" hint="Modular & Integrated" />
                        <x-stat-card label="Hierarki Data" value="5 Tingkat" hint="Platform hingga Plot/Device" />
                        <x-stat-card label="Standar Data" value="100% Jujur" hint="Live vs Demo vs Roadmap" />
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="overflow-hidden rounded-3xl border border-ivory-300 bg-white">
                        <img src="{{ asset('images/regen-farmer-tablet.jpg') }}" alt="Tim Agronomi REGEN di lapangan" class="h-64 w-full object-cover">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-charcoal-900">Prinsip Rekayasa REGEN</h3>
                            <ul class="mt-3 space-y-2 text-xs leading-relaxed text-charcoal-600">
                                <li>&bull; <strong>Presisi Berbasis Data:</strong> Mengukur pH, kelembapan, iklim mikro, dan biaya secara kuantitatif.</li>
                                <li>&bull; <strong>Keberlanjutan Tanah:</strong> Mendorong efisiensi input kimia dan pemulihan bahan organik tanah.</li>
                                <li>&bull; <strong>Keamanan &amp; Privasi:</strong> Validasi server-side, penyimpanan dokumen privat aman, dan isolasi organisasi.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

