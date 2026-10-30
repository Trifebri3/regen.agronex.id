<x-layouts.public :title="$product->name.' — REGEN Solutions'">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('solutions.index') }}" class="text-xs font-medium text-sage-300 hover:text-white">&larr; Semua Solusi</a>
                        <span class="text-forest-700">/</span>
                        <span class="rounded bg-forest-800 px-2 py-0.5 font-mono text-xs text-sage-200">{{ $product->code }}</span>
                        <x-status-badge :status="$product->readiness_status" />
                    </div>
                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-5xl">{{ $product->name }}</h1>
                    <p class="mt-3 text-lg font-medium text-sage-200">{{ $product->tagline }}</p>
                    <p class="mt-4 max-w-2xl text-sm leading-relaxed text-ivory-200 sm:text-base">
                        {{ $product->description }}
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('contact', ['type' => 'product_inquiry', 'module' => $product->slug]) }}" class="rounded-xl bg-sage-500 px-5 py-2.5 text-sm font-semibold text-forest-950 hover:bg-sage-400">
                            Konsultasi Modul {{ $product->name }}
                        </a>
                        <a href="{{ route('app.devices.index') }}" class="rounded-xl border border-forest-700 bg-forest-900 px-5 py-2.5 text-sm font-semibold text-ivory-100 hover:border-sage-400">
                            Lihat Simulasi di Portal Aplikasi
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <div class="overflow-hidden rounded-3xl border border-forest-800 bg-forest-900">
                        <img
                            src="{{ asset($product->hero_image ?: 'images/regen-soilsense-probe.jpg') }}"
                            alt="{{ $product->name }}"
                            class="h-72 w-full object-cover"
                        >
                        @if (! empty($product->metrics))
                            <div class="grid grid-cols-3 gap-2 border-t border-forest-800 p-4 text-center">
                                @foreach ($product->metrics as $mKey => $mVal)
                                    <div class="rounded-xl bg-forest-950/70 p-2.5">
                                        <p class="text-[11px] text-sage-300">{{ $mKey }}</p>
                                        <p class="mt-0.5 text-xs font-semibold text-white">{{ $mVal }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <h2 class="text-2xl font-semibold text-charcoal-900">Kapabilitas &amp; Fitur Utama</h2>
                    <ul class="mt-6 space-y-3.5">
                        @foreach ($product->features ?? [] as $feature)
                            <li class="flex items-start gap-3 rounded-2xl border border-ivory-300 bg-white p-4 text-sm text-charcoal-800">
                                <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-forest-600"></span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 rounded-2xl border border-ivory-300 bg-ivory-100 p-6">
                        <h3 class="text-base font-semibold text-charcoal-900">Mode Implementasi Modular &amp; Terintegrasi</h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal-600">
                            <strong>{{ $product->name }}</strong> dapat dipasang sebagai unit mandiri (standalone) pada titik pengamatan prioritas, atau dihubungkan langsung dengan <strong>REGEN Farm Management</strong> dan <strong>REGEN Enterprise</strong> untuk analitik lintas kebun.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-ivory-300 bg-white p-6">
                        <h2 class="text-lg font-semibold text-charcoal-900">Spesifikasi Teknis &amp; Arsitektur</h2>
                        <dl class="mt-4 divide-y divide-ivory-200 text-sm">
                            @foreach ($product->specifications ?? [] as $specKey => $specVal)
                                <div class="flex flex-col justify-between gap-1 py-3 sm:flex-row">
                                    <dt class="font-medium text-charcoal-500">{{ $specKey }}</dt>
                                    <dd class="font-semibold text-charcoal-900 sm:text-right">{{ $specVal }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>

                    @if ($relatedPrototypes->isNotEmpty())
                        <div class="mt-6 rounded-3xl border border-ivory-300 bg-white p-6">
                            <h3 class="text-base font-semibold text-charcoal-900">Dokumentasi Uji Lapangan Terkait</h3>
                            <div class="mt-4 space-y-3">
                                @foreach ($relatedPrototypes as $proto)
                                    <a href="{{ route('prototype.show', $proto) }}" class="block rounded-xl border border-ivory-200 bg-ivory-50 p-3.5 transition hover:border-sage-400">
                                        <p class="text-xs font-semibold text-forest-700">{{ $proto->testing_date?->format('d M Y') }} &bull; {{ $proto->location }}</p>
                                        <p class="mt-1 text-sm font-semibold text-charcoal-900">{{ $proto->title }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($siblingProducts->isNotEmpty())
                <div class="mt-16 border-t border-ivory-300 pt-12">
                    <h2 class="text-xl font-semibold text-charcoal-900">Eksplorasi Modul Sensor &amp; Spasial Lainnya</h2>
                    <div class="mt-6 grid gap-6 md:grid-cols-3">
                        @foreach ($siblingProducts as $sibling)
                            <x-product-card :product="$sibling" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>

