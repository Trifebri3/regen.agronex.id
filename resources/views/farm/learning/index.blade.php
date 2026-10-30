<x-layouts.portal title="Farmer Learning Center — Pusat Edukasi Usaha Tani" portalType="farm" :organization="$organization">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-ivory-300 bg-white p-5">
        <div>
            <h2 class="text-base font-semibold text-charcoal-900">12 Panduan Praktis Budidaya &amp; Keuangan Usaha Tani</h2>
            <p class="mt-0.5 text-xs text-charcoal-600">Disusun dengan bahasa sederhana dan langkah bertahap untuk petani di berbagai tingkat literasi digital.</p>
        </div>
        <div class="flex flex-wrap gap-2 text-xs">
            <a href="{{ route('app.learning.index') }}" class="rounded-xl px-3.5 py-2 font-semibold {{ $selectedLevel === '' ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700' }}">Semua Tingkat</a>
            <a href="{{ route('app.learning.index', ['level' => 'pemula']) }}" class="rounded-xl px-3.5 py-2 font-semibold {{ $selectedLevel === 'pemula' ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700' }}">Pemula</a>
            <a href="{{ route('app.learning.index', ['level' => 'menengah']) }}" class="rounded-xl px-3.5 py-2 font-semibold {{ $selectedLevel === 'menengah' ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700' }}">Menengah</a>
            <a href="{{ route('app.learning.index', ['level' => 'lanjutan']) }}" class="rounded-xl px-3.5 py-2 font-semibold {{ $selectedLevel === 'lanjutan' ? 'bg-forest-900 text-white' : 'bg-ivory-100 text-charcoal-700' }}">Lanjutan</a>
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($articles as $article)
            <article class="flex flex-col justify-between rounded-2xl border border-ivory-300 bg-white p-6">
                <div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="rounded bg-sage-100 px-2.5 py-0.5 font-semibold uppercase text-forest-800">{{ $article->difficulty_level }}</span>
                        <span class="text-charcoal-500">{{ $article->reading_time_minutes }} menit baca</span>
                    </div>
                    <h3 class="mt-3 text-base font-semibold text-charcoal-900">
                        <a href="{{ route('app.learning.show', $article) }}" class="hover:text-forest-800">{{ $article->title }}</a>
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-charcoal-600">{{ $article->summary }}</p>
                </div>
                <div class="mt-5 border-t border-ivory-200 pt-3">
                    <a href="{{ route('app.learning.show', $article) }}" class="text-xs font-semibold text-forest-800 hover:underline">
                        Baca Panduan Langkah-demi-Langkah &rarr;
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</x-layouts.portal>

