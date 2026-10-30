@props([
    'title' => 'REGEN Portal',
    'portalType' => 'farm',
    'organization' => null,
])

@php
    $user = auth()->user();

    $farmNav = [
        ['label' => 'Dashboard', 'route' => 'app.dashboard', 'active' => 'app.dashboard'],
        ['label' => 'Land Management', 'route' => 'app.lands.index', 'active' => 'app.lands.*'],
        ['label' => 'Crop Management', 'route' => 'app.crops.index', 'active' => 'app.crops.*'],
        ['label' => 'Activity Logs', 'route' => 'app.activities.index', 'active' => 'app.activities.*'],
        ['label' => 'Input Management', 'route' => 'app.inputs.index', 'active' => 'app.inputs.*'],
        ['label' => 'Harvest Management', 'route' => 'app.harvests.index', 'active' => 'app.harvests.*'],
        ['label' => 'Sales Management', 'route' => 'app.sales.index', 'active' => 'app.sales.*'],
        ['label' => 'Financial Management', 'route' => 'app.finance.index', 'active' => 'app.finance.*'],
        ['label' => 'Business Intelligence', 'route' => 'app.intelligence', 'active' => 'app.intelligence*'],
        ['label' => 'Device & Advisory', 'route' => 'app.devices.index', 'active' => 'app.devices.*'],
        ['label' => 'Learning Center', 'route' => 'app.learning.index', 'active' => 'app.learning.*'],
    ];

    $adminNav = [
        ['label' => 'Overview', 'route' => 'admin.overview', 'active' => 'admin.overview', 'section' => 'overview'],
        ['label' => 'Website Content', 'route' => 'admin.website-content', 'active' => 'admin.website-content*', 'section' => 'website_content'],
        ['label' => 'Product Management', 'route' => 'admin.products.index', 'active' => 'admin.products.*', 'section' => 'products'],
        ['label' => 'Prototype Management', 'route' => 'admin.prototypes.index', 'active' => 'admin.prototypes.*', 'section' => 'prototypes'],
        ['label' => 'Document Management', 'route' => 'admin.documents.index', 'active' => 'admin.documents.*', 'section' => 'documents'],
        ['label' => 'Media Library', 'route' => 'admin.media.index', 'active' => 'admin.media.*', 'section' => 'media'],
        ['label' => 'User Management', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'section' => 'users'],
        ['label' => 'Farm Management', 'route' => 'admin.farms.index', 'active' => 'admin.farms.*', 'section' => 'farms'],
        ['label' => 'Device Management', 'route' => 'admin.devices.index', 'active' => 'admin.devices.*', 'section' => 'devices'],
        ['label' => 'Enterprise Management', 'route' => 'admin.enterprise.index', 'active' => 'admin.enterprise.*', 'section' => 'enterprise'],
        ['label' => 'Contact Requests', 'route' => 'admin.contacts.index', 'active' => 'admin.contacts.*', 'section' => 'contacts'],
        ['label' => 'Reports', 'route' => 'admin.reports.index', 'active' => 'admin.reports.*', 'section' => 'reports'],
        ['label' => 'Settings & Audit', 'route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'section' => 'settings'],
    ];

    $enterpriseNav = [
        ['label' => 'Organization Command Center', 'route' => 'enterprise.dashboard', 'active' => 'enterprise.dashboard'],
        ['label' => 'Export Laporan CSV', 'route' => 'enterprise.export', 'active' => 'enterprise.export'],
        ['label' => 'Operasional Kebun (/app)', 'route' => 'app.dashboard', 'active' => 'app.dashboard'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — REGEN by AGRONEX</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ivory-100 font-sans text-charcoal-900 antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        {{-- Sidebar --}}
        <aside class="w-full shrink-0 border-b border-forest-800 bg-forest-950 text-ivory-100 lg:w-68 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between px-5 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-forest-800 font-bold text-sage-200">
                        R
                    </div>
                    <div>
                        <span class="block text-base font-bold tracking-tight text-white leading-none">REGEN</span>
                        <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-widest text-sage-300">
                            {{ match($portalType) { 'admin' => 'Admin CMS', 'enterprise' => 'Enterprise Suite', default => 'Farm Application' } }}
                        </span>
                    </div>
                </a>
                <button
                    type="button"
                    data-menu-toggle="portal-sidebar-nav"
                    aria-expanded="false"
                    class="rounded-lg border border-forest-800 p-2 text-sage-200 lg:hidden"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div id="portal-sidebar-nav" class="hidden px-3 pb-6 lg:block">
                @if ($organization)
                    <div class="mx-2 mb-4 rounded-xl border border-forest-800 bg-forest-900/80 px-3.5 py-2.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-sage-300">Active Tenant Scope</p>
                        <p class="mt-0.5 truncate text-xs font-semibold text-white">{{ $organization->name }}</p>
                        <p class="text-[11px] text-sage-300">{{ $organization->code }} &bull; {{ ucfirst($organization->subscription_tier) }}</p>
                    </div>
                @endif

                <nav class="space-y-1 text-sm" aria-label="Portal Navigation">
                    @if ($portalType === 'admin')
                        @foreach ($adminNav as $item)
                            @php
                                $allowed = $user?->role->canAccessAdminSection($item['section']) ?? false;
                                $isActive = request()->routeIs($item['active']);
                            @endphp
                            @if ($allowed)
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-medium transition {{ $isActive ? 'bg-forest-800 text-white font-semibold' : 'text-ivory-200 hover:bg-forest-900 hover:text-white' }}"
                                >
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @else
                                <span class="flex cursor-not-allowed items-center justify-between rounded-xl px-3.5 py-2 text-xs text-forest-600" title="Role {{ $user?->role->label() }} tidak memiliki izin menu ini">
                                    <span>{{ $item['label'] }}</span>
                                    <span class="text-[10px] uppercase">Locked</span>
                                </span>
                            @endif
                        @endforeach
                    @elseif ($portalType === 'enterprise')
                        @foreach ($enterpriseNav as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-medium transition {{ request()->routeIs($item['active']) ? 'bg-forest-800 text-white font-semibold' : 'text-ivory-200 hover:bg-forest-900 hover:text-white' }}"
                            >
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @else
                        @foreach ($farmNav as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-medium transition {{ request()->routeIs($item['active']) ? 'bg-forest-800 text-white font-semibold' : 'text-ivory-200 hover:bg-forest-900 hover:text-white' }}"
                            >
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @endif
                </nav>

                {{-- Cross-Portal Switcher --}}
                <div class="mt-6 border-t border-forest-800 pt-4 px-2 space-y-1.5 text-xs">
                    <p class="px-1.5 text-[10px] font-semibold uppercase tracking-wider text-sage-400">Ekosistem REGEN</p>
                    <a href="{{ route('home') }}" class="block rounded-lg px-2 py-1.5 text-sage-200 hover:bg-forest-900 hover:text-white">&larr; Website Produk Publik</a>
                    @if ($user?->role->canAccessFarmApp() && $portalType !== 'farm')
                        <a href="{{ route('app.dashboard') }}" class="block rounded-lg px-2 py-1.5 text-sage-200 hover:bg-forest-900 hover:text-white">Farm Management (/app)</a>
                    @endif
                    @if ($user?->role->canAccessEnterpriseDashboard() && $portalType !== 'enterprise')
                        <a href="{{ route('enterprise.dashboard') }}" class="block rounded-lg px-2 py-1.5 text-sage-200 hover:bg-forest-900 hover:text-white">Enterprise Dashboard</a>
                    @endif
                    @if ($user?->role->canAccessAdminPanel() && $portalType !== 'admin')
                        <a href="{{ route('admin.overview') }}" class="block rounded-lg px-2 py-1.5 text-sage-200 hover:bg-forest-900 hover:text-white">Administrator CMS (/admin)</a>
                    @endif
                </div>
            </div>
        </aside>

        {{-- Main Workspace --}}
        <div class="flex flex-1 flex-col min-w-0">
            <header class="border-b border-ivory-300 bg-white px-4 py-3.5 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold tracking-tight text-charcoal-900 sm:text-xl">{{ $title }}</h1>
                        <p class="text-xs text-charcoal-500">
                            REGEN Integrated Smart Agriculture Platform &bull; Flagship Product of <a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-medium text-forest-800 underline">AGRONEX</a>
                        </p>
                    </div>

                    @if ($user)
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xs font-semibold text-charcoal-900">{{ $user->name }}</p>
                                <p class="text-[11px] text-forest-700">{{ $user->role->label() }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-xl border border-ivory-300 bg-ivory-50 px-3 py-1.5 text-xs font-semibold text-charcoal-700 transition hover:border-sage-400 hover:text-charcoal-950">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </header>

            @if (session('status'))
                <div class="border-b border-sage-300 bg-sage-100 px-6 py-3 text-sm font-medium text-forest-900">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="border-b border-red-200 bg-red-50 px-6 py-3 text-xs text-red-800">
                    <p class="font-semibold">Mohon periksa kembali input Anda:</p>
                    <ul class="mt-1 list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

