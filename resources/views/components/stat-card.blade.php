@props([
    'label',
    'value',
    'hint' => null,
    'status' => null,
    'statusLabel' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-ivory-300 bg-white p-5 shadow-xs']) }}>
    <div class="flex items-start justify-between gap-2">
        <p class="text-xs font-medium uppercase tracking-wider text-charcoal-500">{{ $label }}</p>
        @if ($status)
            <x-status-badge :status="$status" :label="$statusLabel" />
        @endif
    </div>
    <p class="mt-2.5 text-2xl font-semibold tracking-tight text-charcoal-900 tabular-nums sm:text-3xl">
        {{ $value }}
    </p>
    @if ($hint)
        <p class="mt-1.5 text-xs text-charcoal-500">{{ $hint }}</p>
    @endif
</div>

