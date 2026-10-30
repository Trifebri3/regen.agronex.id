<x-layouts.public title="REGEN — Grow Smarter. Manage Better. Regenerate the Future.">
    {{-- 1. HERO SECTION --}}
    <section class="relative overflow-hidden border-b border-forest-800 bg-forest-950 text-ivory-50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <div class="inline-flex flex-wrap items-center gap-2 rounded-full border border-forest-700 bg-forest-900/90 px-3.5 py-1.5 text-xs font-medium text-sage-200">
                        <span class="h-2 w-2 rounded-full bg-sage-400"></span>
                        <span>Flagship Product Ecosystem by</span>
                        <a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-bold text-white underline decoration-sage-400 underline-offset-4">AGRONEX</a>
                    </div>

                    <p class="mt-6 text-sm font-semibold uppercase tracking-[0.22em] text-sage-300">
                        REGEN &bull; regen.agronex.id
                    </p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-5xl lg:text-[3.35rem] lg:leading-[1.12]">
                        {{ $heroContent?->title ?? 'Grow Smarter. Manage Better. Regenerate the Future.' }}
                    </h1>
                    <p class="mt-4 text-lg font-medium text-sage-200 sm:text-xl">
                        An Integrated Smart Agriculture &amp; Farm Management Platform.
                    </p>
                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-ivory-200">
                        {{ $heroContent?->body ?? 'REGEN mengintegrasikan teknologi IoT, sensor lingkungan, analisis data, dan manajemen usaha tani untuk membantu petani mengelola pertanian secara lebih presisi, produktif, dan berkelanjutan.' }}
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3.5">
                        <a href="{{ route('solutions.index') }}" class="rounded-xl bg-sage-500 px-5 py-3 text-sm font-semibold text-forest-950 transition hover:bg-sage-400">
                            Explore Solutions
                        </a>
                        <a href="{{ route('platform') }}" class="rounded-xl border border-sage-400/50 bg-forest-900 px-5 py-3 text-sm font-semibold text-ivory-50 transition hover:border-sage-300 hover:bg-forest-800">
                            Explore Platform
                        </a>
                        <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="rounded-xl border border-forest-700 bg-forest-950/60 px-5 py-3 text-sm font-semibold text-sage-200 transition hover:border-sage-400 hover:text-white">
                            Request Enterprise Demo
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-6 border-t border-forest-800 pt-6">
                        <div>
                            <p class="text-2xl font-bold text-white tabular-nums">8 Modul</p>
                            <p class="mt-0.5 text-xs text-sage-300">Arsitektur Modular &amp; Terintegrasi</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white tabular-nums">HPP &amp; BEP</p>
                            <p class="mt-0.5 text-xs text-sage-300">Kalkulator Finansial Presisi</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white tabular-nums">Multi-Tenant</p>
                            <p class="mt-0.5 text-xs text-sage-300">Isolasi Data &amp; 7 Peran RBAC</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="overflow-hidden rounded-3xl border border-forest-800 bg-forest-900 shadow-xl">
                        <img
                            src="{{ asset('images/regen-hero-farmland.jpg') }}"
                            alt="Stasiun sensor surya REGEN di lahan pertanian terasering Indonesia"
                            class="h-72 w-full object-cover sm:h-88"
                        >
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold uppercase tracking-wider text-sage-300">Field Telemetry Snapshot</p>
                                <x-status-badge status="demo" label="Demo Data / Simulasi" />
                            </div>
                            <div class="mt-3.5 grid grid-cols-3 gap-3 text-center">
                                <div class="rounded-xl border border-forest-800 bg-forest-950/70 p-3">
                                    <p class="text-[11px] text-sage-300">Soil pH</p>
                                    <p class="mt-1 text-lg font-bold text-white tabular-nums">6.36</p>
                                    <p class="text-[10px] text-sage-400">Optimal</p>
                                </div>
                                <div class="rounded-xl border border-forest-800 bg-forest-950/70 p-3">
                                    <p class="text-[11px] text-sage-300">VWC Moisture</p>
                                    <p class="mt-1 text-lg font-bold text-white tabular-nums">60.2%</p>
                                    <p class="text-[10px] text-sage-400">Zona Akar</p>
                                </div>
                                <div class="rounded-xl border border-forest-800 bg-forest-950/70 p-3">
                                    <p class="text-[11px] text-sage-300">Mikroklimat</p>
                                    <p class="mt-1 text-lg font-bold text-white tabular-nums">23.4&deg;C</p>
                                    <p class="text-[10px] text-sage-400">RH 78.5%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. AGRICULTURAL CHALLENGES --}}
    <section class="border-b border-ivory-300 bg-ivory-50 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                eyebrow="01 &bull; Tantangan Pertanian Modern"
                title="Mengapa Banyak Usaha Tani Kehilangan Produktivitas dan Margin?"
                subtitle="Pertanian sering menghadapi keputusan berbasis perkiraan akibat terpisahnya kondisi fisik lahan, catatan aktivitas harian, dan perhitungan biaya produksi."
            />

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <p class="font-mono text-xs font-semibold text-olive-600">CHALLENGE 01</p>
                    <h3 class="mt-2 text-base font-semibold text-charcoal-900">Degradasi Tanah Tak Terukur</h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">Fluktuasi pH tanah, salinitas (EC), dan defisit kelembapan zona akar sering terlambat terdeteksi hingga tanaman mengalami stres.</p>
                </div>
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <p class="font-mono text-xs font-semibold text-olive-600">CHALLENGE 02</p>
                    <h3 class="mt-2 text-base font-semibold text-charcoal-900">Pemborosan Air &amp; Pupuk</h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">Jadwal irigasi dan dosis saprotan yang seragam tanpa data mikroklimat maupun catatan pemakaian menggerus modal kerja.</p>
                </div>
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <p class="font-mono text-xs font-semibold text-olive-600">CHALLENGE 03</p>
                    <h3 class="mt-2 text-base font-semibold text-charcoal-900">Buta HPP &amp; Titik Impas</h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">Tanpa pencatatan biaya tetap, biaya variabel, dan susut pascapanen, petani sulit mengetahui batas aman harga jual per kilogram.</p>
                </div>
                <div class="rounded-2xl border border-ivory-300 bg-white p-6">
                    <p class="font-mono text-xs font-semibold text-olive-600">CHALLENGE 04</p>
                    <h3 class="mt-2 text-base font-semibold text-charcoal-900">Fragmentasi Multi-Kebun</h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">Koperasi dan korporasi agribisnis kesulitan mengonsolidasikan laporan lintas lahan, perangkat, dan tim dengan kontrol akses aman.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. REGEN INTRODUCTION --}}
    <section class="border-b border-ivory-300 bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <x-section-header
                        eyebrow="02 &bull; Pengenalan Ekosistem REGEN"
                        title="Satu Platform Terpadu dari Sensor Akar hingga Laporan Laba-Rugi"
                        subtitle="Sebagai produk unggulan dari AGRONEX (agronex.id), REGEN berdiri sebagai platform digital mandiri yang menyatukan perangkat keras modular, manajemen operasional kebun, kalkulator finansial, dan tata kelola enterprise."
                    />
                    <dl class="mt-8 space-y-4 text-sm">
                        <div class="rounded-xl border border-ivory-200 bg-ivory-50 p-4">
                            <dt class="font-semibold text-forest-900">Transparansi Kesiapan Operasional</dt>
                            <dd class="mt-1 text-charcoal-600">Aplikasi Manajemen Kebun, Kalkulator HPP/BEP, CMS Riset, dan Multi-Tenant Enterprise telah berstatus <strong>Operational</strong>. Telemetri IoT tanpa perangkat fisik terhubung ditandai jelas sebagai <strong>Demo / Simulasi</strong>.</dd>
                        </div>
                        <div class="rounded-xl border border-ivory-200 bg-ivory-50 p-4">
                            <dt class="font-semibold text-forest-900">Dirancang untuk Seluruh Skala Literasi Digital</dt>
                            <dd class="mt-1 text-charcoal-600">Mulai dari petani mandiri dengan Pusat Pembelajaran bertingkat hingga tim agronomi korporasi multi-organisasi.</dd>
                        </div>
                    </dl>
                </div>
                <div class="lg:col-span-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <img src="{{ asset('images/regen-soilsense-probe.jpg') }}" alt="REGEN SoilSense Probe" class="h-64 w-full rounded-2xl border border-ivory-300 object-cover">
                        <img src="{{ asset('images/regen-farmer-tablet.jpg') }}" alt="Petani dan agronom menggunakan aplikasi REGEN" class="h-64 w-full rounded-2xl border border-ivory-300 object-cover sm:mt-8">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. PRODUCT ECOSYSTEM --}}
    <section class="border-b border-ivory-300 bg-ivory-100 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <x-section-header
                    eyebrow="03 &bull; Arsitektur Produk REGEN"
                    title="8 Modul Teknologi Pertanian Presisi &amp; Manajemen Agribisnis"
                    subtitle="Setiap produk dirancang untuk berjalan mandiri (modular deployment) maupun saling terhubung dalam satu ekosistem terintegrasi."
                />
                <a href="{{ route('solutions.index') }}" class="text-sm font-semibold text-forest-800 hover:text-forest-600">
                    Lihat Katalog Lengkap &rarr;
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. MODULAR VERSUS INTEGRATED SOLUTIONS --}}
    <section class="border-b border-ivory-300 bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                eyebrow="04 &bull; Fleksibilitas Implementasi"
                title="Modular Deployment vs. Integrated Ecosystem"
                subtitle="Adopsi teknologi sesuai kesiapan kebun Anda hari ini, dan perluas tanpa migrasi ulang saat skala usaha berkembang."
            />

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                <div class="rounded-3xl border border-ivory-300 bg-ivory-50 p-8">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-olive-700 border border-ivory-300">Opsi A &bull; Modular Deployment</span>
                    <h3 class="mt-4 text-2xl font-semibold text-charcoal-900">Mulai dari Modul yang Paling Dibutuhkan</h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">
                        Gunakan modul perangkat lunak atau unit pemantauan secara independen sesuai prioritas masalah lapangan.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-charcoal-700">
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-olive-600"></span><span><strong>Software-First:</strong> Gunakan REGEN Farm Management &amp; Kalkulator HPP/BEP langsung melalui browser tanpa perangkat keras IoT.</span></li>
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-olive-600"></span><span><strong>Targeted Sensing:</strong> Pasang hanya REGEN SoilSense pada blok pembibitan kritis atau EnviroSense pada greenhouse.</span></li>
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-olive-600"></span><span><strong>Biaya Awal Efisien:</strong> Cocok untuk petani mandiri, kebun percontohan, dan validasi bertahap.</span></li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-forest-800 bg-forest-950 p-8 text-ivory-50">
                    <span class="rounded-full bg-forest-800 px-3 py-1 text-xs font-semibold text-sage-200">Opsi B &bull; Integrated Deployment</span>
                    <h3 class="mt-4 text-2xl font-semibold text-white">Sinergi Penuh Sensor, Operasional &amp; Finansial</h3>
                    <p class="mt-2 text-sm leading-relaxed text-sage-200">
                        Seluruh aliran data lahan, perangkat lapangan, aktivitas pekerja, hingga laporan keuangan bermuara ke satu pusat komando.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-ivory-100">
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-sage-400"></span><span><strong>Closed-Loop Agronomy:</strong> Pembacaan SoilSense &amp; EnviroSense memicu Rule-Based Advisory untuk jadwal irigasi WaterSense dan tugas lapangan.</span></li>
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-sage-400"></span><span><strong>Traceability Siklus Penuh:</strong> Setiap pengeluaran saprotan dan aktivitas petak otomatis terhubung ke perhitungan HPP siklus tanam.</span></li>
                        <li class="flex items-start gap-2.5"><span class="mt-1.5 h-2 w-2 rounded-full bg-sage-400"></span><span><strong>Multi-Farm Governance:</strong> Konsolidasi lintas kebun di REGEN Enterprise lengkap dengan jejak audit.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. FARM MANAGEMENT PREVIEW & 7. FARM BUSINESS INTELLIGENCE --}}
    <section class="border-b border-ivory-300 bg-ivory-50 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <x-section-header
                        eyebrow="05 &bull; Farm Management Application"
                        title="Kendali Operasional Kebun Harian yang Rapi &amp; Terstruktur"
                        subtitle="Kelola seluruh aset lahan, pembagian petak, siklus tanaman, log aktivitas harian, stok pupuk/benih, panen, hingga edukasi petani."
                    />
                    <div class="mt-6 grid grid-cols-2 gap-3.5 text-xs">
                        @foreach ([
                            ['Land & Plot Mapping', 'Profil tanah, pH, foto, status kepemilikan & petak'],
                            ['Crop & Calendar', 'Varietas, kebutuhan benih, jadwal tanam & estimasi panen'],
                            ['Daily Activity Logs', 'Persiapan lahan, irigasi, pemupukan, PHT & tenaga kerja'],
                            ['Harvest & Grading', 'Kuantitas panen, Grade A/B/C, layak jual & susut pascapanen'],
                            ['Input Inventory', 'Benih, pupuk, pestisida, bahan organik & harga beli'],
                            ['Farmer Learning Center', '12 modul edukasi budidaya & bisnis tani 3 level literasi'],
                        ] as [$modTitle, $modDesc])
                            <div class="rounded-xl border border-ivory-300 bg-white p-4">
                                <p class="font-semibold text-charcoal-900">{{ $modTitle }}</p>
                                <p class="mt-1 text-charcoal-600">{{ $modDesc }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('app.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-forest-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-forest-800">
                            Buka Farm Management App (/app) &rarr;
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-3xl border border-ivory-300 bg-white p-7 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-forest-700">06 &bull; Farm Business Intelligence</span>
                            <x-status-badge status="operational" label="Operational Calculator" />
                        </div>
                        <h3 class="mt-2 text-2xl font-semibold text-charcoal-900">Kalkulator HPP, Titik Impas (BEP) &amp; Laba-Rugi</h3>
                        <p class="mt-2 text-sm text-charcoal-600">
                            Hitung biaya pokok per kilogram dengan basis kuantitas panen bruto maupun layak jual secara transparan.
                        </p>

                        <div class="mt-6 space-y-3 rounded-2xl border border-ivory-200 bg-ivory-50 p-5 text-sm">
                            <div class="flex items-center justify-between border-b border-ivory-200 pb-2.5">
                                <span class="text-charcoal-600">Total Biaya Produksi (Tetap + Variabel)</span>
                                <span class="font-semibold text-charcoal-900 tabular-nums">Rp 145.850.000</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-ivory-200 pb-2.5">
                                <span class="text-charcoal-600">Basis Kuantitas Layak Jual (Saleable)</span>
                                <span class="font-semibold text-charcoal-900 tabular-nums">17.470 kg</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-ivory-200 pb-2.5">
                                <span class="text-charcoal-600">HPP per Kilogram (Saleable Basis)</span>
                                <span class="font-bold text-forest-800 tabular-nums">Rp 8.349 / kg</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-charcoal-600">Break-Even Point (BEP Kuantitas)</span>
                                <span class="font-semibold text-olive-700 tabular-nums">1.576 kg</span>
                            </div>
                        </div>

                        <p class="mt-4 text-xs text-charcoal-500">
                            *Contoh simulasi berdasarkan data siklus cabai merah; estimasi finansial bukan jaminan keuntungan pasti.
                        </p>
                        <div class="mt-5">
                            <a href="{{ route('platform') }}#business" class="text-sm font-semibold text-forest-800 hover:text-forest-600">
                                Pelajari Metodologi Kalkulasi BI &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. ENTERPRISE SOLUTIONS --}}
    <section class="border-b border-forest-800 bg-forest-950 py-16 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <x-section-header
                        eyebrow="07 &bull; REGEN Enterprise Multi-Tenant"
                        title="Tata Kelola Multi-Organisasi &amp; Multi-Kebun dengan Isolasi Data Ketat"
                        subtitle="Dirancang untuk korporasi agribisnis, jaringan koperasi, dan program pertanian kontrak yang menuntut pemisahan data antar entitas, RBAC 7 level, dan audit trail."
                        :dark="true"
                    />
                    <div class="mt-8 grid gap-4 sm:grid-cols-3 text-xs">
                        <div class="rounded-2xl border border-forest-800 bg-forest-900/70 p-4">
                            <p class="font-semibold text-white">Hierarki Terstruktur</p>
                            <p class="mt-1 text-sage-200">Platform &rarr; Organization &rarr; Farm &rarr; Land/Plot &rarr; Device &amp; Crop Cycle.</p>
                        </div>
                        <div class="rounded-2xl border border-forest-800 bg-forest-900/70 p-4">
                            <p class="font-semibold text-white">Isolasi Antar Organisasi</p>
                            <p class="mt-1 text-sage-200">Setiap organisasi hanya dapat mengakses kebun, sensor, dan catatan keuangannya sendiri.</p>
                        </div>
                        <div class="rounded-2xl border border-forest-800 bg-forest-900/70 p-4">
                            <p class="font-semibold text-white">API &amp; Audit Ready</p>
                            <p class="mt-1 text-sage-200">Endpoint REST JSON `/api/v1`, ekspor laporan CSV, dan log aktivitas keamanan.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-forest-800 bg-forest-900 p-7">
                        <p class="font-mono text-xs uppercase tracking-wider text-sage-300">Enterprise Hierarchy</p>
                        <pre class="mt-3 overflow-x-auto rounded-2xl bg-forest-950 p-4 font-mono text-xs leading-relaxed text-sage-200">Platform (REGEN by AGRONEX)
 └── Organization (Isolated Tenant)
      └── Farm (Multi-Location)
           └── Land / Plot
                ├── IoT Devices &amp; Telemetry
                ├── Crop Cycles &amp; Activities
                └── Harvest &amp; Financial Ledger</pre>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('enterprise') }}" class="rounded-xl bg-sage-500 px-4 py-2.5 text-xs font-semibold text-forest-950 hover:bg-sage-400">
                                Arsitektur Enterprise
                            </a>
                            <a href="{{ route('enterprise.dashboard') }}" class="rounded-xl border border-forest-700 px-4 py-2.5 text-xs font-semibold text-ivory-100 hover:border-sage-400">
                                Buka Enterprise Console
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 9. PROTOTYPE AND RESEARCH HIGHLIGHTS (DYNAMIC CMS) --}}
    <section class="border-b border-ivory-300 bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <x-section-header
                    eyebrow="08 &bull; Prototype &amp; Field Testing CMS"
                    title="Dokumentasi Uji Lapangan, Prototipe &amp; Riset"
                    subtitle="Terhubung langsung dengan sistem CMS administrator untuk menyajikan perkembangan pengujian perangkat keras dan sistem terintegrasi secara kronologis."
                />
                <a href="{{ route('prototype.index') }}" class="text-sm font-semibold text-forest-800 hover:text-forest-600">
                    Buka Galeri Prototype &rarr;
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @forelse ($prototypes as $prototype)
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-ivory-300 bg-ivory-50 transition hover:border-sage-400">
                        <img
                            src="{{ $prototype->resolvedThumbnailUrl() }}"
                            alt="{{ $prototype->title }}"
                            class="h-48 w-full object-cover"
                        >
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <span class="font-semibold text-forest-700">{{ $prototype->category->label() }}</span>
                                    <span class="text-charcoal-500">{{ $prototype->testing_date?->format('d M Y') }}</span>
                                </div>
                                <h3 class="mt-2 text-base font-semibold text-charcoal-900">
                                    <a href="{{ route('prototype.show', $prototype) }}" class="hover:text-forest-800">{{ $prototype->title }}</a>
                                </h3>
                                <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-charcoal-600">
                                    {{ $prototype->description }}
                                </p>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-ivory-200 pt-3 text-xs">
                                <span class="text-charcoal-500">{{ $prototype->location ?? 'Field Lab AGRONEX' }}</span>
                                <a href="{{ route('prototype.show', $prototype) }}" class="font-semibold text-forest-800">Detail &rarr;</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-charcoal-500">Belum ada dokumentasi prototipe yang dipublikasikan.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 10. SUPPORTING DOCUMENTS (DYNAMIC CMS) --}}
    <section class="border-b border-ivory-300 bg-ivory-100 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <x-section-header
                    eyebrow="09 &bull; Dokumen Pendukung &amp; HAKI"
                    title="Repositori HAKI, Spesifikasi Teknis &amp; Sertifikasi"
                    subtitle="Hanya menampilkan dokumen berstatus publik yang diverifikasi oleh administrator dengan kontrol unduhan terproteksi."
                />
                <a href="{{ route('pendukung.index') }}" class="text-sm font-semibold text-forest-800 hover:text-forest-600">
                    Lihat Semua Dokumen &rarr;
                </a>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2">
                @forelse ($documents as $doc)
                    <div class="flex flex-col justify-between rounded-2xl border border-ivory-300 bg-white p-5 sm:flex-row sm:items-center gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded bg-sage-50 px-2 py-0.5 text-xs font-semibold text-forest-800">{{ $doc->category->label() }}</span>
                                <x-status-badge :status="$doc->registration_status->value" :label="$doc->registration_status->label()" />
                            </div>
                            <h3 class="mt-2 text-base font-semibold text-charcoal-900">{{ $doc->title }}</h3>
                            <p class="mt-1 text-xs text-charcoal-500">
                                No: {{ $doc->document_number ?? '-' }} &bull; {{ $doc->issuing_institution ?? 'AGRONEX' }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            @if ($doc->isPubliclyDownloadable())
                                <a href="{{ route('pendukung.download', $doc) }}" class="inline-flex items-center rounded-xl border border-forest-800 px-3.5 py-2 text-xs font-semibold text-forest-800 hover:bg-forest-900 hover:text-white">
                                    Unduh PDF
                                </a>
                            @else
                                <span class="rounded-xl bg-ivory-100 px-3 py-2 text-xs font-medium text-charcoal-500">Arsip Tercatat</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-charcoal-500">Belum ada dokumen pendukung publik.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 11. CONTACT AND PARTNERSHIP CTA --}}
    <section class="bg-ivory-50 py-16 lg:py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-forest-800 bg-forest-900 px-6 py-12 text-center text-ivory-50 sm:px-12">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">10 &bull; Kemitraan &amp; Implementasi</p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-4xl">
                    Siap Membangun Pertanian Presisi Bersama REGEN?
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-sage-200 sm:text-base">
                    Diskusikan kebutuhan implementasi modular, uji coba platform manajemen usaha tani, atau kemitraan multi-kebun Enterprise bersama tim produk REGEN dari AGRONEX.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="rounded-xl bg-sage-500 px-6 py-3 text-sm font-semibold text-forest-950 hover:bg-sage-400">
                        Jadwalkan Demo &amp; Konsultasi
                    </a>
                    <a href="{{ route('login') }}" class="rounded-xl border border-sage-400/50 px-6 py-3 text-sm font-semibold text-white hover:bg-forest-800">
                        Masuk ke Portal Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

