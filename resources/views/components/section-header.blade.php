@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'align' => 'left',
    'dark' => false,
])

@php
    $alignClasses = $align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl';
@endphp

<div {{ $attributes->merge(['class' => $alignClasses]) }}>
    @if ($eyebrow)
        <p class="mb-2.5 text-xs font-semibold uppercase tracking-[0.16em] {{ $dark ? 'text-sage-300' : 'text-forest-700' }}">
            {{ $eyebrow }}
        </p>
    @endif
    <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl lg:text-4xl {{ $dark ? 'text-ivory-50' : 'text-charcoal-900' }}">
        {{ $title }}
    </h2>
    @if ($subtitle)
        <p class="mt-3.5 text-base leading-relaxed {{ $dark ? 'text-sage-200' : 'text-charcoal-600' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>

