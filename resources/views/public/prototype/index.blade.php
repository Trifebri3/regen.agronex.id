<x-layouts.public title="Prototype & Field Research Gallery — REGEN by AGRONEX">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Dynamic Prototype Documentation CMS</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                Galeri Prototipe, Uji Lapangan &amp; R&amp;D
            </h1>
            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-sage-200 sm:text-base">
                Dokumentasi kronologis pengujian sensor tanah, aktuator irigasi, stasiun mikroklimat, dan sistem terintegrasi REGEN yang dikelola langsung melalui Administrator CMS.
            </p>
        </div>
    </section>

    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Filter & Search Bar --}}
            <form method="GET" action="{{ route('prototype.index') }}" class="mb-10 flex flex-col gap-4 rounded-2xl border border-ivory-300 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('prototype.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $selectedCategory === '' ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700 hover:bg-ivory-200' }}">
                        Semua Kategori
                    </a>
                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('prototype.index', ['category' => $cat->value, 'q' => $search]) }}"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $selectedCategory === $cat->value ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700 hover:bg-ivory-200' }}"
                        >
                            {{ $cat->label() }}
                        </a>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    @if ($selectedCategory !== '')
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari prototipe atau lokasi..." class="w-full rounded-xl border border-ivory-300 bg-ivory-50 px-3.5 py-2 text-xs focus:border-forest-700 focus:outline-none sm:w-56">
                    <button type="submit" class="rounded-xl bg-forest-900 px-4 py-2 text-xs font-semibold text-white hover:bg-forest-800">Cari</button>
                </div>
            </form>

            {{-- Featured Documentation --}}
            @if ($featuredPrototypes->isNotEmpty() && $selectedCategory === '' && $search === '')
                <div class="mb-12">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-forest-700">Featured Documentation</h2>
                    <div class="mt-4 grid gap-6 lg:grid-cols-2">
                        @foreach ($featuredPrototypes as $feat)
                            <article class="overflow-hidden rounded-3xl border border-ivory-300 bg-white shadow-xs">
                                <img src="{{ $feat->resolvedThumbnailUrl() }}" alt="{{ $feat->title }}" class="h-60 w-full object-cover">
                                <div class="p-6">
                                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                        <span class="rounded bg-sage-100 px-2.5 py-0.5 font-semibold text-forest-800">{{ $feat->category->label() }}</span>
                                        <span class="text-charcoal-500">{{ $feat->testing_date?->format('d M Y') }} &bull; {{ $feat->location }}</span>
                                    </div>
                                    <h3 class="mt-3 text-xl font-semibold text-charcoal-900">
                                        <a href="{{ route('prototype.show', $feat) }}" class="hover:text-forest-800">{{ $feat->title }}</a>
                                    </h3>
                                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">{{ $feat->description }}</p>
                                    <div class="mt-4 flex items-center justify-between border-t border-ivory-200 pt-4 text-xs">
                                        <span class="text-charcoal-500">{{ $feat->images->count() }} Foto Dokumentasi {{ $feat->video_url || $feat->video_path ? '• Video Tersedia' : '' }}</span>
                                        <a href="{{ route('prototype.show', $feat) }}" class="font-semibold text-forest-800 hover:text-forest-600">Buka Dokumentasi Lengkap &rarr;</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Chronological Responsive Gallery --}}
            <h2 class="text-xs font-semibold uppercase tracking-wider text-charcoal-500">Pembaruan Kronologis</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($prototypes as $proto)
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-ivory-300 bg-white">
                        <button
                            type="button"
                            data-lightbox-src="{{ $proto->resolvedThumbnailUrl() }}"
                            data-lightbox-caption="{{ $proto->title }} ({{ $proto->location }})"
                            class="group relative block text-left"
                        >
                            <img src="{{ $proto->resolvedThumbnailUrl() }}" alt="{{ $proto->title }}" class="h-48 w-full object-cover">
                            <span class="absolute right-3 bottom-3 rounded-lg bg-forest-950/80 px-2.5 py-1 text-[11px] font-medium text-white opacity-90 group-hover:opacity-100">
                                Perbesar Foto
                            </span>
                        </button>
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-forest-700">{{ $proto->category->label() }}</span>
                                    <span class="text-charcoal-500">{{ $proto->testing_date?->format('d M Y') }}</span>
                                </div>
                                <h3 class="mt-2 text-base font-semibold text-charcoal-900">
                                    <a href="{{ route('prototype.show', $proto) }}" class="hover:text-forest-800">{{ $proto->title }}</a>
                                </h3>
                                <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-charcoal-600">{{ $proto->description }}</p>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-ivory-200 pt-3 text-xs">
                                <span class="truncate text-charcoal-500">{{ $proto->location ?? 'Indonesia' }}</span>
                                <a href="{{ route('prototype.show', $proto) }}" class="shrink-0 font-semibold text-forest-800">Detail &rarr;</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-ivory-300 bg-white p-10 text-center text-sm text-charcoal-500">
                        Tidak ditemukan dokumentasi prototipe yang sesuai dengan filter pencarian.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $prototypes->links() }}
            </div>
        </div>
    </section>

    {{-- Image Lightbox Modal --}}
    <div id="regen-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-charcoal-950/85 p-4">
        <div class="relative max-w-4xl w-full rounded-2xl bg-forest-950 p-3 text-ivory-50 shadow-2xl">
            <div class="flex items-center justify-between px-2 pb-2">
                <p id="regen-lightbox-caption" class="text-xs font-medium text-sage-200"></p>
                <button type="button" data-lightbox-close class="rounded-lg bg-forest-800 px-3 py-1 text-xs font-semibold text-white hover:bg-forest-700">
                    Tutup (ESC)
                </button>
            </div>
            <img id="regen-lightbox-img" src="" alt="Lightbox preview" class="max-h-[75vh] w-full rounded-xl object-contain bg-black">
        </div>
    </div>
</x-layouts.public>

