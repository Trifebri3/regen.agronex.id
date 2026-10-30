<x-layouts.portal title="Prototype Documentation CMS" portalType="admin">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <form method="POST" action="{{ route('admin.prototypes.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-4">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Buat Dokumentasi Prototype Baru</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Judul Dokumentasi *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kategori Produk *</label>
                        <select name="category" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Status Publikasi *</label>
                        <select name="publication_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal Pengujian</label>
                        <input type="date" name="testing_date" value="{{ old('testing_date', date('Y-m-d')) }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Lokasi Uji</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Lembang, Jawa Barat" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Deskripsi Pengujian *</label>
                    <textarea name="description" rows="3" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Catatan Teknis (Technical Notes)</label>
                    <textarea name="technical_notes" rows="2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">{{ old('technical_notes') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Thumbnail Gambar (JPG/PNG/WEBP, maks 4MB)</label>
                    <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="mt-1 w-full text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Multi-Foto Galeri (Hingga 8 foto, maks 4MB/foto)</label>
                    <input type="file" name="gallery_images[]" multiple accept=".jpg,.jpeg,.png,.webp" class="mt-1 w-full text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">URL Video Resmi (HTTPS YouTube/Vimeo)</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/embed/..." class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Atau File Video (MP4/WEBM &le;20MB)</label>
                        <input type="file" name="video_file" accept=".mp4,.webm" class="mt-1 w-full text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Lampiran PDF (&le;10MB)</label>
                        <input type="file" name="pdf_file" accept=".pdf" class="mt-1 w-full text-xs">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-xs">
                    <input type="checkbox" name="is_featured" value="1">
                    <span>Tandai sebagai Featured Documentation</span>
                </label>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">
                    Simpan Dokumentasi Prototype
                </button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-7">
            <form method="GET" action="{{ route('admin.prototypes.index') }}" class="flex flex-wrap gap-2 rounded-2xl border border-ivory-300 bg-white p-3">
                <select name="category" class="rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->value }}" @selected($selectedCategory === $cat->value)>{{ $cat->label() }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                    <option value="">Semua Status</option>
                    <option value="published" @selected($selectedStatus === 'published')>Published</option>
                    <option value="draft" @selected($selectedStatus === 'draft')>Draft</option>
                    <option value="archived" @selected($selectedStatus === 'archived')>Archived</option>
                </select>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul..." class="flex-1 rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                <button type="submit" class="rounded-xl bg-forest-900 px-3.5 py-1.5 text-xs font-semibold text-white">Filter</button>
            </form>

            @foreach ($prototypes as $proto)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <form method="POST" action="{{ route('admin.prototypes.update', $proto) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-forest-800">{{ $proto->category->label() }} &bull; {{ $proto->images->count() }} Foto</span>
                            <x-status-badge :status="$proto->publication_status" />
                        </div>
                        <input type="text" name="title" value="{{ $proto->title }}" required class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs font-semibold">
                        <div class="grid gap-2 sm:grid-cols-3">
                            <select name="category" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->value }}" @selected($proto->category === $cat)>{{ $cat->label() }}</option>
                                @endforeach
                            </select>
                            <select name="publication_status" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                                <option value="published" @selected($proto->publication_status === 'published')>Published</option>
                                <option value="draft" @selected($proto->publication_status === 'draft')>Draft</option>
                                <option value="archived" @selected($proto->publication_status === 'archived')>Archived</option>
                            </select>
                            <input type="date" name="testing_date" value="{{ $proto->testing_date?->format('Y-m-d') }}" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                        </div>
                        <input type="text" name="location" value="{{ $proto->location }}" placeholder="Lokasi" class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">
                        <textarea name="description" rows="2" required class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs">{{ $proto->description }}</textarea>
                        <input type="hidden" name="technical_notes" value="{{ $proto->technical_notes }}">
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 text-xs">
                                <input type="checkbox" name="is_featured" value="1" @checked($proto->is_featured)>
                                <span>Featured</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('prototype.show', $proto) }}" target="_blank" class="text-xs font-medium text-forest-800 underline">Preview</a>
                                <button type="submit" class="rounded-lg bg-forest-900 px-3 py-1.5 text-xs font-semibold text-white">Update</button>
                            </div>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.prototypes.destroy', $proto) }}" class="mt-2 border-t border-ivory-200 pt-2 text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[11px] font-semibold text-red-700 hover:underline">Hapus Entri</button>
                    </form>
                </div>
            @endforeach

            {{ $prototypes->links() }}
        </div>
    </div>
</x-layouts.portal>

