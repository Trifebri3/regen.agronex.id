<x-layouts.portal title="Media Library" portalType="admin">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-4">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Unggah Aset Media</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Judul Media *</label>
                    <input type="text" name="title" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Koleksi *</label>
                    <select name="collection" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="general">General Public Media</option>
                        <option value="prototype">Prototype Assets</option>
                        <option value="land">Land Photography</option>
                        <option value="document">Private Document Vault</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Alt Text Aksesibilitas</label>
                    <input type="text" name="alt_text" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Berkas (JPG/PNG/WEBP/PDF/MP4 &le;10MB) *</label>
                    <input type="file" name="media_file" required accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4" class="mt-1 w-full text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Unggah ke Pustaka</button>
            </form>
        </div>

        <div class="lg:col-span-8">
            <div class="grid gap-4 sm:grid-cols-2">
                @forelse ($mediaItems as $media)
                    <div class="rounded-2xl border border-ivory-300 bg-white p-4">
                        <div class="flex items-center justify-between text-xs">
                            <span class="rounded bg-ivory-100 px-2 py-0.5 font-mono">{{ $media->collection }} ({{ $media->disk }})</span>
                            <span class="text-charcoal-500">{{ number_format($media->file_size / 1024, 1) }} KB</span>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-charcoal-900">{{ $media->title }}</p>
                        <p class="truncate text-xs text-charcoal-500">{{ $media->original_filename }} &bull; {{ $media->mime_type }}</p>
                        <div class="mt-3 flex items-center justify-between border-t border-ivory-200 pt-2">
                            <span class="text-[11px] text-charcoal-500">{{ $media->uploader?->name ?? 'System' }}</span>
                            <form method="POST" action="{{ route('admin.media.destroy', $media) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-700 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">
                        Belum ada media kustom yang diunggah.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.portal>

