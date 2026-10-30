<x-layouts.portal title="Centralized IoT Device Fleet" portalType="admin">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($devices as $dev)
            <div class="rounded-2xl border border-ivory-300 bg-white p-5">
                <div class="flex items-center justify-between gap-2">
                    <span class="font-mono text-xs font-bold text-forest-800">{{ $dev->device_uid }}</span>
                    <x-status-badge :status="$dev->is_demo ? 'demo' : 'live'" :label="$dev->is_demo ? 'Demo Simulation' : 'Live Device'" />
                </div>
                <h3 class="mt-2 text-sm font-semibold text-charcoal-900">{{ $dev->name }}</h3>
                <p class="mt-1 text-xs text-charcoal-500">{{ $dev->organization?->name }} &bull; {{ $dev->farm?->name }}</p>
                <div class="mt-3 flex items-center justify-between border-t border-ivory-200 pt-2.5 text-xs text-charcoal-600">
                    <span>Firmware: {{ $dev->firmware_version }}</span>
                    <span>Battery: {{ $dev->battery_level ?? '-' }}%</span>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.portal>

