<x-layouts.portal :title="$article->title" portalType="farm" :organization="$organization">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <article class="rounded-3xl border border-ivory-300 bg-white p-6 sm:p-8">
                <a href="{{ route('app.learning.index') }}" class="text-xs font-semibold text-forest-800 hover:underline">&larr; Kembali ke Pusat Edukasi</a>
                <div class="mt-4 flex items-center gap-3 text-xs">
                    <span class="rounded bg-sage-100 px-2.5 py-1 font-semibold uppercase text-forest-900">Tingkat: {{ $article->difficulty_level }}</span>
                    <span class="text-charcoal-500">Estimasi Baca: {{ $article->reading_time_minutes }} menit</span>
                </div>
                <h2 class="mt-3 text-2xl font-bold text-charcoal-900">{{ $article->title }}</h2>
                <p class="mt-3 text-base leading-relaxed text-charcoal-700">{{ $article->summary }}</p>

                <div class="mt-6 rounded-2xl border border-sage-200 bg-sage-50 p-5">
                    <h3 class="text-sm font-semibold text-forest-900">Poin Penting yang Perlu Diingat</h3>
                    <ul class="mt-3 space-y-2 text-sm text-charcoal-800">
                        @foreach ($article->key_takeaways ?? [] as $point)
                            <li class="flex items-start gap-2.5">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-forest-700"></span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6">
                    <h3 class="text-base font-semibold text-charcoal-900">Langkah Praktis di Lapangan</h3>
                    <ol class="mt-3 space-y-3">
                        @foreach ($article->step_by_step ?? [] as $idx => $step)
                            <li class="flex items-start gap-3 rounded-2xl border border-ivory-200 bg-ivory-50 p-4 text-sm text-charcoal-800">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-forest-900 text-xs font-bold text-white">{{ $idx + 1 }}</span>
                                <span>{{ $step }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @if ($article->practical_example)
                    <div class="mt-6 rounded-2xl border border-olive-200 bg-olive-100/70 p-5">
                        <h3 class="text-sm font-semibold text-olive-800">Contoh Hitungan / Penerapan Nyata</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-charcoal-800">{{ $article->practical_example }}</p>
                    </div>
                @endif
            </article>
        </div>

        <div class="lg:col-span-4">
            <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                <h3 class="text-sm font-semibold text-charcoal-900">12 Modul Kurikulum Petani</h3>
                <ul class="mt-3 space-y-1.5 text-xs">
                    @foreach ($allArticles as $other)
                        <li>
                            <a
                                href="{{ route('app.learning.show', $other) }}"
                                class="block rounded-lg px-3 py-2 {{ $other->id === $article->id ? 'bg-forest-900 font-semibold text-white' : 'text-charcoal-700 hover:bg-ivory-100' }}"
                            >
                                {{ $other->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-layouts.portal>

