<x-layouts.portal title="Website Content CMS" portalType="admin">
    <div class="space-y-6">
        @foreach ($contents as $item)
            <form method="POST" action="{{ route('admin.website-content.update', $item) }}" class="rounded-2xl border border-ivory-300 bg-white p-6">
                @csrf
                @method('PUT')
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-ivory-200 pb-3">
                    <div>
                        <span class="rounded bg-sage-100 px-2 py-0.5 font-mono text-xs font-semibold text-forest-800">{{ $item->section_key }}</span>
                        <span class="ml-2 text-xs text-charcoal-500">Halaman: {{ ucfirst($item->page) }}</span>
                    </div>
                    @if ($item->editor)
                        <span class="text-xs text-charcoal-500">Terakhir diubah oleh {{ $item->editor->name }}</span>
                    @endif
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Judul Bagian *</label>
                        <input type="text" name="title" value="{{ $item->title }}" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Subjudul</label>
                        <input type="text" name="subtitle" value="{{ $item->subtitle }}" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-charcoal-600">Isi Narasi</label>
                        <textarea name="body" rows="3" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-sm">{{ $item->body }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="rounded-xl bg-forest-900 px-4 py-2 text-xs font-semibold text-white hover:bg-forest-800">Simpan Perubahan Konten</button>
                </div>
            </form>
        @endforeach
    </div>
</x-layouts.portal>

