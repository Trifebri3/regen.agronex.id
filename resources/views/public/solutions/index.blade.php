<x-layouts.public title="Product Solutions — REGEN by AGRONEX">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Katalog Produk &amp; Arsitektur Modular</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                REGEN Product Solutions
            </h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-sage-200">
                Delapan modul teknologi pertanian presisi dan manajemen usaha tani dari AGRONEX. Setiap modul dapat diimplementasikan secara mandiri maupun terhubung penuh dalam satu arsitektur data.
            </p>
            <div class="mt-6 flex flex-wrap gap-2.5 text-xs">
                <a href="{{ route('solutions.soilsense') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">SoilSense</a>
                <a href="{{ route('solutions.watersense') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">WaterSense</a>
                <a href="{{ route('solutions.envirosense') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">EnviroSense</a>
                <a href="{{ route('solutions.terra') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">Terra</a>
                <a href="{{ route('platform') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">Intelligence, Farm &amp; Business</a>
                <a href="{{ route('enterprise') }}" class="rounded-full border border-forest-700 bg-forest-900 px-3.5 py-1.5 text-sage-200 hover:border-sage-400 hover:text-white">Enterprise</a>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- SPECIAL BUNDLING & STARTER PACKAGE NOTE --}}
            <div class="mb-8 rounded-2xl border-2 border-forest-800 bg-forest-950 p-6 text-ivory-100 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-sage-400 px-3 py-0.5 text-xs font-bold uppercase tracking-wider text-forest-950">
                                Catatan Pengadaan &amp; Bundling
                            </span>
                            <span class="rounded-full border border-forest-700 bg-forest-900 px-3 py-0.5 text-xs text-sage-200">
                                Paket Starter &amp; Turnkey Solution
                            </span>
                        </div>
                        <h2 class="mt-2 text-xl font-bold text-white sm:text-2xl">
                            REGEN Tersedia Sebagai Produk Satuan, Paket Starter, dan Bundling Terpadu
                        </h2>
                        <p class="mt-2 max-w-3xl text-xs sm:text-sm leading-relaxed text-sage-200">
                            Setiap modul di bawah dapat diperoleh secara mandiri (modular) maupun melalui <strong>Paket Starter</strong> (bundling hardware SoilSense/EnviroSense + Farm Management cloud siap pakai untuk kebun pemula) dan <strong>Paket Bundling Enterprise</strong> dengan harga implementasi khusus.
                        </p>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="inline-flex rounded-xl bg-sage-400 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-forest-950 hover:bg-sage-300">
                            Pilih Paket Bundling &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-8 rounded-2xl border border-olive-200 bg-olive-100/70 p-4 text-xs text-olive-800">
                <strong>Kebijakan Kejujuran Operasional:</strong> Modul perangkat lunak manajemen kebun, kalkulator HPP/BEP, pemetaan lahan Terra, dan multi-tenant Enterprise berstatus <strong>Operational</strong>. Modul perangkat keras sensor IoT tanpa koneksi perangkat fisik aktif ditampilkan dengan label <strong>Demo / Data Simulasi</strong>, dan fitur prediktif AI lanjutan ditandai sebagai <strong>Roadmap</strong>.
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>

