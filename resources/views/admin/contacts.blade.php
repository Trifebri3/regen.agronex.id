<x-layouts.portal title="Contact & Demo Requests" portalType="admin">
    <div class="space-y-4">
        @forelse ($contacts as $contact)
            <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <span class="rounded bg-sage-100 px-2 py-0.5 text-xs font-semibold text-forest-800">{{ $contact->request_type }}</span>
                        <h3 class="mt-1 text-base font-semibold text-charcoal-900">{{ $contact->full_name }} &mdash; {{ $contact->organization_name ?: 'Mandiri' }}</h3>
                        <p class="text-xs text-charcoal-500">{{ $contact->email }} &bull; {{ $contact->phone }} &bull; Skala: {{ $contact->land_scale ?: '-' }}</p>
                    </div>
                    <x-status-badge :status="$contact->status" />
                </div>
                <p class="mt-3 rounded-xl bg-ivory-50 p-3 text-xs text-charcoal-700">{{ $contact->message }}</p>

                <form method="POST" action="{{ route('admin.contacts.update', $contact) }}" class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-xl border border-ivory-300 px-3 py-1.5">
                        @foreach (['new' => 'New', 'in_review' => 'In Review', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $k => $v)
                            <option value="{{ $k }}" @selected($contact->status === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="admin_notes" value="{{ $contact->admin_notes }}" placeholder="Catatan tindak lanjut tim..." class="flex-1 rounded-xl border border-ivory-300 px-3 py-1.5">
                    <button type="submit" class="rounded-xl bg-forest-900 px-4 py-1.5 font-semibold text-white">Simpan Status</button>
                </form>
            </div>
        @empty
            <div class="rounded-2xl border border-ivory-300 bg-white p-8 text-center text-sm text-charcoal-500">Belum ada permintaan kontak.</div>
        @endforelse
    </div>
</x-layouts.portal>

