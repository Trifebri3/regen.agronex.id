<x-layouts.public :title="$prototype->title.' — Prototype REGEN'">
    <section class="border-b border-forest-800 bg-forest-950 py-12 text-ivory-50">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('prototype.index') }}" class="text-xs font-medium text-sage-300 hover:text-white">&larr; Kembali ke Galeri Prototype</a>
            <div class="mt-4 flex flex-wrap items-center gap-2.5 text-xs">
                <span class="rounded bg-forest-800 px-2.5 py-1 font-semibold text-sage-200">{{ $prototype->category->label() }}</span>
                <span class="text-sage-300">Tanggal Uji: {{ $prototype->testing_date?->format('d F Y') ?? '-' }}</span>
                <span class="text-forest-700">&bull;</span>
                <span class="text-sage-300">Lokasi: {{ $prototype->location ?? '-' }}</span>
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-4xl">{{ $prototype->title }}</h1>
        </div>
    </section>

    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-ivory-300 bg-white">
                <img src="{{ $prototype->resolvedThumbnailUrl() }}" alt="{{ $prototype->title }}" class="max-h-112 w-full object-cover">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-charcoal-900">Deskripsi Pengujian &amp; Dokumentasi</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-charcoal-700">{{ $prototype->description }}</p>

                    @if ($prototype->technical_notes)
                        <div class="mt-6 rounded-2xl border border-ivory-300 bg-ivory-50 p-5">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-forest-800">Catatan Teknis Rekayasa (Technical Notes)</h3>
                            <p class="mt-2 whitespace-pre-line text-sm text-charcoal-700">{{ $prototype->technical_notes }}</p>
                        </div>
                    @endif

                    @if ($prototype->pdf_path)
                        <div class="mt-6">
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($prototype->pdf_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-forest-800 px-4 py-2.5 text-xs font-semibold text-forest-800 hover:bg-forest-900 hover:text-white">
                                Buka Lampiran Dokumentasi PDF &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Video Viewer --}}
            @if ($prototype->video_path || $prototype->video_url)
                <div class="mt-8 rounded-3xl border border-ivory-300 bg-white p-6">
                    <h2 class="text-base font-semibold text-charcoal-900">Dokumentasi Video Uji Lapangan</h2>
                    <div class="mt-4 overflow-hidden rounded-2xl bg-charcoal-950">
                        @if ($prototype->video_path)
                            <video controls class="max-h-104 w-full">
                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($prototype->video_path) }}">
                            </video>
                        @elseif ($prototype->video_url)
                            <div class="aspect-video w-full">
                                <iframe
                                    src="{{ $prototype->video_url }}"
                                    title="{{ $prototype->title }}"
                                    class="h-full w-full"
                                    allowfullscreen
                                    loading="lazy"
                                ></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Multi-Image Lightbox Gallery --}}
            @if ($prototype->images->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-base font-semibold text-charcoal-900">Galeri Foto Multi-Sudut (Klik untuk Lightbox)</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        @foreach ($prototype->images as $img)
                            <button
                                type="button"
                                data-lightbox-src="{{ $img->resolvedUrl() }}"
                                data-lightbox-caption="{{ $img->caption ?? $prototype->title }}"
                                class="overflow-hidden rounded-2xl border border-ivory-300 bg-white text-left transition hover:border-sage-400"
                            >
                                <img src="{{ $img->resolvedUrl() }}" alt="{{ $img->caption ?? $prototype->title }}" class="h-44 w-full object-cover">
                                @if ($img->caption)
                                    <p class="p-3 text-xs text-charcoal-600">{{ $img->caption }}</p>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($chronologicalUpdates->isNotEmpty())
                <div class="mt-12 border-t border-ivory-300 pt-8">
                    <h2 class="text-base font-semibold text-charcoal-900">Pembaruan Riset Lainnya</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        @foreach ($chronologicalUpdates as $item)
                            <a href="{{ route('prototype.show', $item) }}" class="rounded-2xl border border-ivory-300 bg-white p-4 hover:border-sage-400">
                                <p class="text-xs text-forest-700 font-semibold">{{ $item->category->label() }} &bull; {{ $item->testing_date?->format('d M Y') }}</p>
                                <p class="mt-1 text-sm font-semibold text-charcoal-900">{{ $item->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <div id="regen-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-charcoal-950/85 p-4">
        <div class="relative max-w-4xl w-full rounded-2xl bg-forest-950 p-3 text-ivory-50 shadow-2xl">
            <div class="flex items-center justify-between px-2 pb-2">
                <p id="regen-lightbox-caption" class="text-xs font-medium text-sage-200"></p>
                <button type="button" data-lightbox-close class="rounded-lg bg-forest-800 px-3 py-1 text-xs font-semibold text-white">Tutup</button>
            </div>
            <img id="regen-lightbox-img" src="" alt="Preview" class="max-h-[75vh] w-full rounded-xl object-contain bg-black">
        </div>
    </div>
</x-layouts.public>

