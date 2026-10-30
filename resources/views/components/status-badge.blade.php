@props([
    'status' => 'operational',
    'label' => null,
])

@php
    $normalized = strtolower((string) $status);
    [$classes, $defaultLabel, $dotClass] = match ($normalized) {
        'operational', 'live', 'live_mqtt', 'live_http' => [
            'bg-sage-100 text-forest-800 border-sage-300',
            $normalized === 'operational' ? 'Operational' : 'Live Device',
            'bg-forest-600',
        ],
        'pilot_demo', 'demo', 'demo_simulation', 'simulasi' => [
            'bg-olive-100 text-olive-800 border-olive-200',
            'Demo / Data Simulasi',
            'bg-olive-600',
        ],
        'roadmap', 'planned' => [
            'bg-ivory-200 text-charcoal-700 border-ivory-300',
            'Roadmap Feature',
            'bg-charcoal-500',
        ],
        'critical', 'expired' => [
            'bg-red-50 text-red-800 border-red-200',
            ucfirst($normalized),
            'bg-red-600',
        ],
        'attention', 'warning', 'in_process' => [
            'bg-amber-50 text-amber-900 border-amber-200',
            str_replace('_', ' ', ucfirst($normalized)),
            'bg-amber-600',
        ],
        default => [
            'bg-ivory-100 text-charcoal-700 border-ivory-300',
            $label ?? ucfirst(str_replace('_', ' ', $normalized)),
            'bg-sage-500',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-medium tracking-wide {$classes}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
    {{ $label ?? $defaultLabel }}
</span>

