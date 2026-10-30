<x-layouts.public title="Dokumen Pendukung, HAKI & Spesifikasi Teknis — REGEN">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Supporting Document Repository CMS</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                Dokumen Pendukung, HAKI &amp; Sertifikasi Teknis
            </h1>
            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-sage-200 sm:text-base">
                Repositori resmi pencatatan Hak Kekayaan Intelektual (HAKI), paten, desain industri, publikasi riset, lembar spesifikasi teknis, dan laporan uji REGEN oleh AGRONEX.
            </p>
        </div>
    </section>

    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('pendukung.index') }}" class="mb-8 grid gap-3 rounded-2xl border border-ivory-300 bg-white p-4 sm:grid-cols-4">
                <div>
                    <label for="cat-filter" class="block text-[11px] font-semibold uppercase text-charcoal-500">Kategori Dokumen</label>
                    <select id="cat-filter" name="category" class="mt-1 w-full rounded-xl border border-ivory-300 bg-ivory-50 px-3 py-2 text-xs">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->value }}" @selected($selectedCategory === $cat->value)>{{ $cat->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-[11px] font-semibold uppercase text-charcoal-500">Status Registrasi</label>
                    <select id="status-filter" name="status" class="mt-1 w-full rounded-xl border border-ivory-300 bg-ivory-50 px-3 py-2 text-xs">
                        <option value="">Semua Status Publik</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st->value }}" @selected($selectedStatus === $st->value)>{{ $st->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="q-doc" class="block text-[11px] font-semibold uppercase text-charcoal-500">Pencarian</label>
                    <input id="q-doc" type="search" name="q" value="{{ $search }}" placeholder="Judul, nomor registrasi, institusi..." class="mt-1 w-full rounded-xl border border-ivory-300 bg-ivory-50 px-3 py-2 text-xs">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-xl bg-forest-900 px-4 py-2 text-xs font-semibold text-white hover:bg-forest-800">Terapkan Filter</button>
                    <a href="{{ route('pendukung.index') }}" class="rounded-xl border border-ivory-300 px-3 py-2 text-xs font-medium text-charcoal-600 hover:bg-ivory-100">Reset</a>
                </div>
            </form>

            <div class="space-y-4">
                @forelse ($documents as $doc)
                    <article class="rounded-2xl border border-ivory-300 bg-white p-6">
                        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded bg-sage-100 px-2.5 py-0.5 text-xs font-semibold text-forest-900">{{ $doc->category->label() }}</span>
                                    <span class="rounded bg-ivory-100 px-2 py-0.5 text-xs text-charcoal-600">{{ $doc->document_type }}</span>
                                    <x-status-badge :status="$doc->registration_status->value" :label="$doc->registration_status->label()" />
                                </div>
                                <h2 class="text-lg font-semibold text-charcoal-900">{{ $doc->title }}</h2>
                                @if ($doc->description)
                                    <p class="max-w-3xl text-sm text-charcoal-600">{{ $doc->description }}</p>
                                @endif
                                <div class="flex flex-wrap gap-x-6 gap-y-1 pt-1 text-xs text-charcoal-500">
                                    <span><strong>Nomor Dokumen:</strong> {{ $doc->document_number ?: '-' }}</span>
                                    <span><strong>Institusi Penerbit:</strong> {{ $doc->issuing_institution ?: '-' }}</span>
                                    <span><strong>Tanggal Terbit:</strong> {{ $doc->issue_date?->format('d M Y') ?: '-' }}</span>
                                    @if ($doc->expiration_date)
                                        <span><strong>Berlaku Hingga:</strong> {{ $doc->expiration_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0">
                                @if ($doc->isPubliclyDownloadable())
                                    <a href="{{ route('pendukung.download', $doc) }}" class="inline-flex items-center gap-2 rounded-xl bg-forest-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-forest-800">
                                        Unduh Dokumen Resmi
                                    </a>
                                @else
                                    <span class="inline-block rounded-xl border border-ivory-300 bg-ivory-50 px-3.5 py-2 text-xs font-medium text-charcoal-500">
                                        Metadata Publik (Unduhan Dibatasi)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-ivory-300 bg-white p-10 text-center text-sm text-charcoal-500">
                        Tidak ada dokumen publik yang sesuai dengan filter.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $documents->links() }}
            </div>
        </div>
    </section>
</x-layouts.public>

