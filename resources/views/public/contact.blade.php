<x-layouts.public title="Contact & Request Demo — REGEN by AGRONEX">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Konsultasi Implementasi &amp; Demo</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                {{ $contactContent?->title ?? 'Hubungi Tim Produk & Solusi REGEN' }}
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-sage-200 sm:text-base">
                {{ $contactContent?->subtitle ?? 'Jadwalkan sesi demo aplikasi manajemen kebun, diskusi pilot sensor modular, atau arsitektur REGEN Enterprise.' }}
            </p>
        </div>
    </section>

    <section class="py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <form method="POST" action="{{ route('contact.submit') }}" class="rounded-3xl border border-ivory-300 bg-white p-6 shadow-xs sm:p-8">
                        @csrf
                        <h2 class="text-xl font-semibold text-charcoal-900">Formulir Permintaan Demo &amp; Kemitraan</h2>

                        @if ($errors->any())
                            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-800">
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="request_type" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Kategori Permintaan</label>
                                <select id="request_type" name="request_type" class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-ivory-50 px-3.5 py-2.5 text-sm text-charcoal-900 focus:border-forest-700 focus:outline-none">
                                    @foreach ([
                                        'enterprise_demo' => 'Request Enterprise Multi-Farm Demo',
                                        'platform_demo' => 'Demo Aplikasi Farm Management & BI',
                                        'product_inquiry' => 'Konsultasi Modul IoT / Spasial (SoilSense, WaterSense, dll.)',
                                        'partnership' => 'Kemitraan Riset, Koperasi & Distribusi',
                                    ] as $val => $lbl)
                                        <option value="{{ $val }}" @selected(old('request_type', $preselectedType) === $val)>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="full_name" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Nama Lengkap *</label>
                                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Email Resmi *</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Nomor Telepon / WhatsApp</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>

                            <div>
                                <label for="organization_name" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Organisasi / Kebun / Koperasi</label>
                                <input id="organization_name" type="text" name="organization_name" value="{{ old('organization_name') }}" class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>

                            <div>
                                <label for="role_title" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Jabatan / Peran</label>
                                <input id="role_title" type="text" name="role_title" value="{{ old('role_title') }}" placeholder="Pemilik Kebun, Agronom, Pengurus..." class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>

                            <div>
                                <label for="land_scale" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Estimasi Skala Lahan</label>
                                <select id="land_scale" name="land_scale" class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                                    <option value="< 5 Ha">&lt; 5 Hektar (Mandiri / Pilot)</option>
                                    <option value="5 - 50 Ha">5 – 50 Hektar (Komersial)</option>
                                    <option value="50 - 250 Ha">50 – 250 Hektar (Koperasi / Multi-Blok)</option>
                                    <option value="> 250 Ha">&gt; 250 Hektar (Enterprise Multi-Kebun)</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <span class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Modul REGEN yang Diminati</span>
                                <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4 text-xs">
                                    @foreach ($products as $prod)
                                        <label class="flex items-center gap-2 rounded-lg border border-ivory-200 bg-ivory-50 px-2.5 py-2">
                                            <input
                                                type="checkbox"
                                                name="interested_modules[]"
                                                value="{{ $prod->slug }}"
                                                @checked(in_array($prod->slug, old('interested_modules', $preselectedModule ? [$preselectedModule] : []), true))
                                                class="rounded border-ivory-300 text-forest-800"
                                            >
                                            <span>{{ str_replace('REGEN ', '', $prod->name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="message" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Kebutuhan &amp; Catatan Lapangan *</label>
                                <textarea id="message" name="message" rows="4" required placeholder="Ceritakan komoditas utama, lokasi lahan, dan tantangan operasional yang ingin diselesaikan..." class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full rounded-xl bg-forest-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-forest-800 sm:w-auto">
                                Kirim Permintaan Resmi
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-6 lg:col-span-5">
                    <div class="rounded-3xl border border-ivory-300 bg-white p-6">
                        <h3 class="text-base font-semibold text-charcoal-900">Informasi Produk &amp; Induk Perusahaan</h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal-600">
                            {{ $contactContent?->body ?? 'Divisi Produk REGEN — AGRONEX.' }}
                        </p>
                        <dl class="mt-4 space-y-2.5 border-t border-ivory-200 pt-4 text-xs">
                            <div class="flex justify-between">
                                <dt class="text-charcoal-500">Product Domain</dt>
                                <dd class="font-semibold text-charcoal-900">https://regen.agronex.id</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-charcoal-500">Parent Brand (Produk Utama)</dt>
                                <dd><a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-semibold text-forest-800 underline">https://agronex.id</a></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-forest-800 bg-forest-950 p-6 text-ivory-100">
                        <h3 class="text-base font-semibold text-white">Ingin Langsung Mengeksplorasi Aplikasi?</h3>
                        <p class="mt-2 text-xs leading-relaxed text-sage-200">
                            Gunakan akun evaluasi berbasis peran pada halaman Portal Login untuk menguji Farm Management App, Kalkulator HPP/BEP, Enterprise Dashboard, dan Administrator CMS.
                        </p>
                        <a href="{{ route('login') }}" class="mt-4 inline-block rounded-xl bg-sage-500 px-4 py-2 text-xs font-semibold text-forest-950 hover:bg-sage-400">
                            Masuk ke Portal Evaluasi &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

