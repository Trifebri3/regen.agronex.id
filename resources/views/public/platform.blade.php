<x-layouts.public title="Farm Management & Business Intelligence Platform — REGEN">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Digital Farm Operating System</span>
                <x-status-badge status="operational" label="Operational Web Platform" />
            </div>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                REGEN Farm Management &amp; Business Platform
            </h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-sage-200">
                Aplikasi manajemen operasional kebun, kalkulator finansial HPP/BEP, rekomendasi agronomi berbasis aturan, dan pusat edukasi petani dalam satu platform responsif.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('app.dashboard') }}" class="rounded-xl bg-sage-500 px-5 py-3 text-sm font-semibold text-forest-950 hover:bg-sage-400">
                    Buka Aplikasi Farm Management (/app)
                </a>
                <a href="{{ route('app.intelligence') }}" class="rounded-xl border border-forest-700 bg-forest-900 px-5 py-3 text-sm font-semibold text-white hover:border-sage-400">
                    Coba Kalkulator HPP &amp; BEP
                </a>
            </div>
        </div>
    </section>

    <section id="farm-management" class="border-b border-ivory-300 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                eyebrow="Modul F &bull; REGEN Farm Management"
                title="10 Modul Operasional dari Pemetaan Petak hingga Penjualan"
                subtitle="Mendukung input dan pengambilan data aktual secara penuh untuk kebun tunggal maupun multi-kebun."
            />

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['A. Dashboard Eksekutif Kebun', 'Ringkasan jumlah kebun, total luas lahan, siklus aktif, pengeluaran tercatat, panen, pendapatan, laba/rugi, dan peringatan aktivitas.'],
                    ['B. Land & Plot Management', 'Registrasi lahan, lokasi, luasan hektar, profil tanah & pH, unggah foto lahan, status kepemilikan/sewa, dan pembagian petak (plot).'],
                    ['C. Crop Management', 'Perencanaan komoditas, varietas, tanggal tanam, estimasi panen, luasan tanam, kebutuhan benih, dan pelacakan status budidaya.'],
                    ['D. Activity Management', 'Log persiapan lahan, penanaman, irigasi (volume air), pemupukan, pengendalian hama, perawatan, panen, dan catatan harian.'],
                    ['E. Input Management', 'Inventaris benih, pupuk, pestisida, bahan organik, kuantitas beli vs terpakai, serta harga satuan pembelian.'],
                    ['F. Harvest & Sales Management', 'Pencatatan panen per grade kualitas, kuantitas layak jual vs susut pascapanen, data pembeli, status bayar, dan biaya distribusi.'],
                ] as [$mTitle, $mDesc])
                    <div class="rounded-2xl border border-ivory-300 bg-ivory-50 p-6">
                        <h3 class="text-base font-semibold text-charcoal-900">{{ $mTitle }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal-600">{{ $mDesc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="business" class="border-b border-ivory-300 bg-ivory-100 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <x-section-header
                        eyebrow="Modul G &bull; REGEN Business"
                        title="Kalkulasi Finansial, HPP &amp; Titik Impas (BEP) yang Akurat"
                        subtitle="Menghilangkan perkiraan kasar dalam menentukan kelayakan usaha tani dan harga jual komoditas."
                    />
                    <ul class="mt-6 space-y-3 text-sm text-charcoal-700">
                        <li>&bull; <strong>Total Production Cost:</strong> Akumulasi seluruh biaya tetap dan biaya variabel tercatat.</li>
                        <li>&bull; <strong>Revenue &amp; Profit/Loss:</strong> Kuantitas terjual &times; harga jual dikurangi total biaya produksi.</li>
                        <li>&bull; <strong>HPP per Unit Fleksibel:</strong> Pilihan jelas antara basis <em>Total Harvested Quantity</em> atau <em>Saleable Quantity</em>.</li>
                        <li>&bull; <strong>Break-Even Point:</strong> <code>Biaya Tetap / (Harga Jual per Unit - Biaya Variabel per Unit)</code> dengan proteksi input nol/tidak valid.</li>
                    </ul>
                </div>
                <div id="intelligence" class="lg:col-span-6">
                    <div class="rounded-3xl border border-ivory-300 bg-white p-7">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-forest-700">Modul E &bull; REGEN Intelligence</span>
                            <div class="flex gap-1.5">
                                <x-status-badge status="operational" label="Rule-Based Active" />
                                <x-status-badge status="roadmap" label="AI Advisory Roadmap" />
                            </div>
                        </div>
                        <h3 class="mt-3 text-xl font-semibold text-charcoal-900">Dukungan Keputusan Agronomi Berjenjang</h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal-600">
                            Mesin <strong>Rule-Based Recommendation</strong> secara aktif mengevaluasi ambang batas pH tanah, defisit kelembapan akar, dan suhu mikroklimat. Fitur <strong>AI-Assisted Predictive Advisory</strong> disajikan secara jujur sebagai kapabilitas <em>Roadmap</em> tanpa klaim operasional semu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                eyebrow="Inklusivitas Literasi Digital"
                title="12 Topik Farmer Learning Center"
                subtitle="Materi edukasi praktis dari persiapan lahan hingga strategi harga berbasis HPP, dikelompokkan untuk tingkat Pemula, Menengah, dan Lanjutan."
            />
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($learningTopics as $topic)
                    <div class="rounded-2xl border border-ivory-300 bg-ivory-50 p-4">
                        <span class="rounded bg-sage-100 px-2 py-0.5 text-[11px] font-semibold uppercase text-forest-800">{{ $topic->difficulty_level }}</span>
                        <h3 class="mt-2 text-sm font-semibold text-charcoal-900">{{ $topic->title }}</h3>
                        <p class="mt-1 line-clamp-2 text-xs text-charcoal-600">{{ $topic->summary }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>

