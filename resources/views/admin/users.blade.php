<x-layouts.portal title="User & RBAC Role Management" portalType="admin">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <form method="POST" action="{{ route('admin.users.store') }}" class="rounded-2xl border border-ivory-300 bg-white p-6 space-y-3.5">
                @csrf
                <h2 class="text-base font-semibold text-charcoal-900">Buat Pengguna Baru</h2>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Nama Lengkap *</label>
                    <input type="text" name="name" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Email *</label>
                    <input type="email" name="email" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Peran (RBAC) *</label>
                    <select name="role" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Organisasi (Tenant)</label>
                    <select name="organization_id" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                        <option value="">-- Platform Global (Tanpa Tenant) --</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Jabatan</label>
                    <input type="text" name="job_title" class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal-600">Kata Sandi Awal (&ge;8 karakter) *</label>
                    <input type="password" name="password" required class="mt-1 w-full rounded-xl border border-ivory-300 px-3 py-2 text-xs">
                </div>
                <button type="submit" class="w-full rounded-xl bg-forest-900 py-2.5 text-xs font-semibold text-white hover:bg-forest-800">Tambah Pengguna</button>
            </form>
        </div>

        <div class="space-y-3 lg:col-span-8">
            @foreach ($users as $u)
                <form method="POST" action="{{ route('admin.users.update', $u) }}" class="flex flex-col justify-between gap-3 rounded-2xl border border-ivory-300 bg-white p-4 sm:flex-row sm:items-center">
                    @csrf
                    @method('PUT')
                    <div>
                        <p class="text-sm font-semibold text-charcoal-900">{{ $u->name }}</p>
                        <p class="text-xs text-charcoal-500">{{ $u->email }} &bull; {{ $u->organization?->name ?? 'Platform Global' }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <select name="role" class="rounded-lg border border-ivory-300 px-2.5 py-1.5">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}" @selected($u->role === $role)>{{ $role->label() }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="organization_id" value="{{ $u->organization_id }}">
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="is_active" value="1" @checked($u->is_active)>
                            <span>Aktif</span>
                        </label>
                        <button type="submit" class="rounded-lg bg-forest-900 px-3 py-1.5 font-semibold text-white">Simpan</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</x-layouts.portal>

