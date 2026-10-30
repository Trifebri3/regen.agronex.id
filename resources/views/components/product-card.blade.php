@props([
    'product',
])

@php
    $detailUrl = match ($product->slug) {
        'soilsense' => route('solutions.soilsense'),
        'watersense' => route('solutions.watersense'),
        'envirosense' => route('solutions.envirosense'),
        'terra' => route('solutions.terra'),
        'farm-management', 'business', 'intelligence' => route('platform').'#'.$product->slug,
        'enterprise' => route('enterprise'),
        default => route('solutions.show', $product->slug),
    };
@endphp

<article class="flex h-full flex-col justify-between rounded-2xl border border-ivory-300 bg-white p-6 transition hover:border-sage-400">
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2">
            <span class="rounded-md bg-ivory-100 px-2 py-0.5 font-mono text-xs font-medium text-forest-800">
                {{ $product->code }}
            </span>
            <x-status-badge :status="$product->readiness_status" />
        </div>

        <h3 class="mt-4 text-xl font-semibold tracking-tight text-charcoal-900">
            {{ $product->name }}
        </h3>
        <p class="mt-1 text-sm font-medium text-forest-700">
            {{ $product->tagline }}
        </p>
        <p class="mt-3 text-sm leading-relaxed text-charcoal-600">
            {{ $product->short_description }}
        </p>

        @if (! empty($product->features))
            <ul class="mt-4 space-y-2 border-t border-ivory-200 pt-4 text-xs text-charcoal-700">
                @foreach (array_slice($product->features, 0, 4) as $feature)
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-forest-600"></span>
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-6 flex items-center justify-between border-t border-ivory-200 pt-4">
        <div class="flex flex-wrap gap-1.5">
            @foreach ($product->deployment_modes ?? ['modular', 'integrated'] as $mode)
                <span class="rounded bg-sage-50 px-2 py-0.5 text-[11px] font-medium capitalize text-forest-800">
                    {{ $mode }}
                </span>
            @endforeach
        </div>
        <a href="{{ $detailUrl }}" class="inline-flex items-center gap-1 text-sm font-semibold text-forest-800 hover:text-forest-600">
            Detail
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
</article>

