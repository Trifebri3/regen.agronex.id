<x-layouts.public title="Portal Login — REGEN by AGRONEX">
    <section class="py-14 lg:py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <div class="rounded-3xl border border-ivory-300 bg-white p-7 shadow-xs sm:p-8">
                        <p class="text-xs font-semibold uppercase tracking-widest text-forest-700">Autentikasi Terpusat</p>
                        <h1 class="mt-1.5 text-2xl font-bold text-charcoal-900">Masuk ke Ekosistem REGEN</h1>
                        <p class="mt-1.5 text-xs text-charcoal-500">Akses otomatis diarahkan sesuai peran RBAC Anda (Admin CMS, Enterprise, atau Farm App).</p>

                        @if ($errors->any())
                            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3.5 text-xs text-red-800">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Alamat Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>
                            <div>
                                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-600">Kata Sandi</label>
                                <input id="password" type="password" name="password" required class="mt-1.5 w-full rounded-xl border border-ivory-300 bg-white px-3.5 py-2.5 text-sm focus:border-forest-700 focus:outline-none">
                            </div>
                            <label class="flex items-center gap-2 text-xs text-charcoal-600">
                                <input type="checkbox" name="remember" value="1" class="rounded border-ivory-300 text-forest-800">
                                <span>Ingat sesi perangkat ini</span>
                            </label>
                            <button type="submit" class="w-full rounded-xl bg-forest-900 py-3 text-sm font-semibold text-white transition hover:bg-forest-800">
                                Masuk ke Portal
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-3xl border border-ivory-300 bg-ivory-100 p-6">
                        <div class="flex items-center justify-between gap-2">
                            <h2 class="text-sm font-semibold text-charcoal-900">Direktori Akun Evaluasi RBAC</h2>
                            <x-status-badge status="demo" label="Demo Accounts" />
                        </div>
                        <p class="mt-1.5 text-xs text-charcoal-600">
                            Gunakan kata sandi standar <code class="rounded bg-white px-1.5 py-0.5 font-mono font-semibold text-forest-900">password</code> untuk menguji kontrol akses 7 peran &amp; isolasi multi-organisasi:
                        </p>
                        <div class="mt-4 divide-y divide-ivory-200 rounded-2xl border border-ivory-300 bg-white text-xs">
                            @foreach ([
                                ['Super Admin', 'superadmin@regen.agronex.id', 'Akses penuh 13 menu CMS, Farm & Enterprise'],
                                ['Content Admin', 'content@regen.agronex.id', 'CMS Konten, Prototype, Dokumen & Kontak'],
                                ['Product Admin', 'product@regen.agronex.id', 'Katalog Produk, Prototype, Media & Laporan'],
                                ['Technical Admin', 'tech@regen.agronex.id', 'Device Fleet, Prototype, Dokumen & Settings'],
                                ['Org Admin (Tenant 1)', 'orgadmin@agronex-nusantara.id', 'PT Agronex Nusantara Agro (/enterprise & /app)'],
                                ['Farm Manager (Tenant 1)', 'manager@agronex-nusantara.id', 'Operasional penuh Kebun Pangalengan & Lembang'],
                                ['Farmer (Tenant 1)', 'farmer@agronex-nusantara.id', 'Log aktivitas harian, Kalkulator BI & Learning'],
                                ['Org Admin (Tenant 2)', 'orgadmin@priangan-tani.id', 'Koperasi Priangan (Terisolasi dari Tenant 1)'],
                            ] as [$rLabel, $rEmail, $rDesc])
                                <div class="p-3">
                                    <div class="flex flex-wrap items-center justify-between gap-1">
                                        <span class="font-semibold text-forest-900">{{ $rLabel }}</span>
                                        <code class="font-mono text-[11px] text-charcoal-700">{{ $rEmail }}</code>
                                    </div>
                                    <p class="mt-0.5 text-[11px] text-charcoal-500">{{ $rDesc }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

