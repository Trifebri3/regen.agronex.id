<x-layouts.portal title="Supporting Document & HAKI CMS" portalType="admin">
    <div class="mb-6 rounded-2xl border border-sage-300 bg-sage-50 p-4 text-xs text-forest-900">
        <strong>Keamanan Penyimpanan Dokumen:</strong> Seluruh berkas dokumen disimpan di disk privat terisolasi (<code>storage/app/private</code>) dan tidak pernah diekspos melalui URL storage publik. Pengunjung publik hanya dapat mengunduh dokumen yang berstatus <strong>Public + Allow Download</strong>.
    </div>

    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Unggah Dokumen Pendukung / HAKI</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Judul Dokumen *</label>
                    <input type="text" name="title" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Kategori *</label>
                        <select name="category" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tipe Dokumen *</label>
                        <input type="text" name="document_type" required placeholder="Hak Cipta / Paten / Datasheet" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Nomor Dokumen</label>
                        <input type="text" name="document_number" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Institusi Penerbit</label>
                        <input type="text" name="issuing_institution" placeholder="DJKI Kemenkum RI" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal Terbit</label>
                        <input type="date" name="issue_date" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Tanggal Kedaluwarsa</label>
                        <input type="date" name="expiration_date" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Status Registrasi *</label>
                        <select name="registration_status" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            @foreach ($statuses as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-charcoal-600">Visibilitas *</label>
                        <select name="visibility" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                            <option value="private">Private (Internal Admin Only)</option>
                            <option value="public">Public (Tampil di /pendukung)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Deskripsi</label>
                    <textarea name="description" rows="2" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Berkas PDF atau Gambar (&le;10MB)</label>
                    <input type="file" name="document_file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="mt-1 w-full text-xs">
                </div>
                <label class="flex items-center gap-2 text-xs">
                    <input type="checkbox" name="allow_download" value="1">
                    <span>Izinkan pengunjung mengunduh berkas (bila Public)</span>
                </label>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">
                    Simpan Dokumen
                </button>
            </form>
        </div>

        <div class="space-y-4 lg:col-span-7">
            @foreach ($documents as $doc)
                <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                    <form method="POST" action="{{ route('admin.documents.update', $doc) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                            <span class="font-semibold text-forest-800">{{ $doc->category->label() }} &bull; {{ $doc->document_type }}</span>
                            <span class="rounded px-2 py-0.5 font-semibold {{ $doc->visibility === 'public' ? 'bg-sage-100 text-forest-900' : 'bg-charcoal-900 text-white' }}">
                                {{ strtoupper($doc->visibility) }}
                            </span>
                        </div>
                        <input type="text" name="title" value="{{ $doc->title }}" required class="w-full rounded-xl border border-ivory-300 px-3 py-1.5 text-xs font-semibold">
                        <div class="grid gap-2 sm:grid-cols-3">
                            <input type="text" name="document_number" value="{{ $doc->document_number }}" placeholder="Nomor Dokumen" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                            <select name="registration_status" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                                @foreach ($statuses as $st)
                                    <option value="{{ $st->value }}" @selected($doc->registration_status === $st)>{{ $st->label() }}</option>
                                @endforeach
                            </select>
                            <select name="visibility" class="rounded-xl border border-ivory-300 px-2.5 py-1.5 text-xs">
                                <option value="public" @selected($doc->visibility === 'public')>Public</option>
                                <option value="private" @selected($doc->visibility === 'private')>Private</option>
                            </select>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <label class="flex items-center gap-2 text-xs">
                                <input type="checkbox" name="allow_download" value="1" @checked($doc->allow_download)>
                                <span>Allow Public Download</span>
                            </label>
                            <div class="flex items-center gap-2">
                                @if ($doc->file_path)
                                    <a href="{{ route('admin.documents.download', $doc) }}" class="text-xs font-semibold text-forest-800 underline">Admin Download</a>
                                @endif
                                <button type="submit" class="rounded-lg bg-forest-900 px-3 py-1.5 text-xs font-semibold text-white">Update</button>
                            </div>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.documents.destroy', $doc) }}" class="mt-2 border-t border-ivory-200 pt-2 text-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[11px] font-semibold text-red-700 hover:underline">Hapus Dokumen</button>
                    </form>
                </div>
            @endforeach

            {{ $documents->links() }}
        </div>
    </div>
</x-layouts.portal>

