@props([
    'title' => 'REGEN — Integrated Smart Agriculture & Farm Management Platform',
    'metaDescription' => 'REGEN oleh AGRONEX: Platform Pertanian Cerdas & Manajemen Usaha Tani Terintegrasi. Grow Smarter. Manage Better. Regenerate the Future.',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription }}">
    <title>{{ $title }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ivory-50 font-sans text-charcoal-900 antialiased selection:bg-forest-800 selection:text-ivory-50">
    {{-- Top Parent Brand Attribution Bar --}}
    <div class="border-b border-forest-800 bg-forest-950 text-ivory-100">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 px-4 py-2 text-xs sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded bg-forest-800 px-2 py-0.5 text-[11px] font-semibold tracking-wider text-sage-200 uppercase">
                    Produk Unggulan
                </span>
                <span class="text-ivory-200">
                    REGEN adalah ekosistem produk teknologi pertanian dari
                    <a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-semibold text-white underline decoration-sage-400 underline-offset-4 hover:text-sage-200">
                        AGRONEX (agronex.id)
                    </a>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden text-sage-300 sm:inline">regen.agronex.id</span>
                @auth
                    <a href="{{ auth()->user()->role->canAccessAdminPanel() ? route('admin.overview') : route('app.dashboard') }}" class="font-medium text-sage-200 hover:text-white">
                        Dashboard ({{ auth()->user()->name }}) &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-sage-200 hover:text-white">
                        Portal Login &rarr;
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Main Sticky Navigation --}}
    <header class="sticky top-0 z-40 border-b border-ivory-300 bg-ivory-50/95 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-forest-900 text-ivory-50 shadow-xs">
                    <svg class="h-5 w-5 text-sage-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.5 0-8-3.2-8-7.8C4 7.8 9.5 3.5 19 3c.5 9.5-3.8 18-7 18Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17c2.5-2.5 5.8-5.2 10-7.5" />
                    </svg>
                </div>
                <div>
                    <span class="block text-lg font-bold tracking-tight text-forest-950 leading-none">REGEN</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-olive-600 mt-0.5">by AGRONEX</span>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-charcoal-700 lg:flex" aria-label="Primary Navigation">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Beranda</a>
                <a href="{{ route('solutions.index') }}" class="{{ request()->routeIs('solutions.*') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Solutions</a>
                <a href="{{ route('platform') }}" class="{{ request()->routeIs('platform') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Platform</a>
                <a href="{{ route('enterprise') }}" class="{{ request()->routeIs('enterprise') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Enterprise</a>
                <a href="{{ route('prototype.index') }}" class="{{ request()->routeIs('prototype.*') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Prototype</a>
                <a href="{{ route('pendukung.index') }}" class="{{ request()->routeIs('pendukung.*') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Pendukung</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">About</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-forest-800 font-semibold' : 'hover:text-forest-800' }}">Contact</a>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('app.dashboard') }}" class="rounded-xl border border-ivory-300 bg-white px-3.5 py-2 text-xs font-semibold text-charcoal-800 transition hover:border-sage-400">
                    Farm App
                </a>
                <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="rounded-xl bg-forest-900 px-4 py-2 text-xs font-semibold text-ivory-50 transition hover:bg-forest-800">
                    Request Demo
                </a>
            </div>

            <button
                type="button"
                data-menu-toggle="mobile-public-nav"
                aria-expanded="false"
                aria-label="Toggle Menu"
                class="inline-flex items-center justify-center rounded-xl border border-ivory-300 bg-white p-2 text-charcoal-800 lg:hidden"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- Sub-bar for Quick Modular Solution Links --}}
        <div class="border-t border-ivory-200 bg-ivory-100/70">
            <div class="mx-auto flex max-w-7xl items-center gap-5 overflow-x-auto px-4 py-2 text-xs text-charcoal-600 sm:px-6 lg:px-8">
                <span class="shrink-0 font-semibold uppercase tracking-wider text-olive-700">Modular Tech:</span>
                <a href="{{ route('solutions.soilsense') }}" class="shrink-0 hover:text-forest-800 {{ request()->routeIs('solutions.soilsense') ? 'font-semibold text-forest-800' : '' }}">SoilSense</a>
                <a href="{{ route('solutions.watersense') }}" class="shrink-0 hover:text-forest-800 {{ request()->routeIs('solutions.watersense') ? 'font-semibold text-forest-800' : '' }}">WaterSense</a>
                <a href="{{ route('solutions.envirosense') }}" class="shrink-0 hover:text-forest-800 {{ request()->routeIs('solutions.envirosense') ? 'font-semibold text-forest-800' : '' }}">EnviroSense</a>
                <a href="{{ route('solutions.terra') }}" class="shrink-0 hover:text-forest-800 {{ request()->routeIs('solutions.terra') ? 'font-semibold text-forest-800' : '' }}">Terra</a>
                <span class="text-ivory-300">|</span>
                <a href="{{ route('platform') }}" class="shrink-0 hover:text-forest-800">Farm Management &amp; BI</a>
                <a href="{{ route('enterprise.dashboard') }}" class="shrink-0 hover:text-forest-800">Enterprise Console</a>
            </div>
        </div>

        {{-- Mobile Drawer --}}
        <div id="mobile-public-nav" class="hidden border-t border-ivory-300 bg-white px-4 py-4 lg:hidden">
            <div class="flex flex-col gap-2.5 text-sm font-medium text-charcoal-800">
                <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Beranda</a>
                <a href="{{ route('solutions.index') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Product Solutions</a>
                <a href="{{ route('solutions.soilsense') }}" class="rounded-lg pl-6 pr-3 py-1.5 text-xs text-charcoal-600 hover:bg-ivory-100">&bull; REGEN SoilSense</a>
                <a href="{{ route('solutions.watersense') }}" class="rounded-lg pl-6 pr-3 py-1.5 text-xs text-charcoal-600 hover:bg-ivory-100">&bull; REGEN WaterSense</a>
                <a href="{{ route('solutions.envirosense') }}" class="rounded-lg pl-6 pr-3 py-1.5 text-xs text-charcoal-600 hover:bg-ivory-100">&bull; REGEN EnviroSense</a>
                <a href="{{ route('solutions.terra') }}" class="rounded-lg pl-6 pr-3 py-1.5 text-xs text-charcoal-600 hover:bg-ivory-100">&bull; REGEN Terra</a>
                <a href="{{ route('platform') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Farm Management Platform</a>
                <a href="{{ route('enterprise') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Enterprise Solutions</a>
                <a href="{{ route('prototype.index') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Prototype Gallery</a>
                <a href="{{ route('pendukung.index') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Supporting Documents</a>
                <a href="{{ route('about') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">About REGEN</a>
                <a href="{{ route('contact') }}" class="rounded-lg px-3 py-2 hover:bg-ivory-100">Contact &amp; Demo</a>
                <div class="mt-2 grid grid-cols-2 gap-2 border-t border-ivory-200 pt-3">
                    <a href="{{ route('login') }}" class="rounded-xl border border-ivory-300 py-2 text-center text-xs font-semibold">Login Portal</a>
                    <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="rounded-xl bg-forest-900 py-2 text-center text-xs font-semibold text-white">Request Demo</a>
                </div>
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="border-b border-sage-300 bg-sage-100 px-4 py-3 text-center text-sm font-medium text-forest-900">
            {{ session('status') }}
        </div>
    @endif

    <main>
        {{ $slot }}
    </main>

    {{-- Editorial AgriTech Footer --}}
    <footer class="border-t border-forest-800 bg-forest-950 text-ivory-100">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-forest-800 text-sage-200 font-bold">
                            R
                        </div>
                        <div>
                            <p class="text-base font-bold tracking-tight text-white">REGEN</p>
                            <p class="text-[11px] uppercase tracking-widest text-sage-300">Flagship Product of AGRONEX</p>
                        </div>
                    </div>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-sage-200">
                        Grow Smarter. Manage Better. Regenerate the Future. Platform pertanian presisi dan manajemen usaha tani terintegrasi untuk ekosistem agribisnis modern.
                    </p>
                    <p class="mt-4 text-xs text-sage-300">
                        Produk unggulan dari <a href="https://agronex.id" target="_blank" rel="noopener noreferrer" class="font-semibold text-white underline underline-offset-4">https://agronex.id</a> &bull; Domain Produk: <span class="text-white">regen.agronex.id</span>
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-sage-300">Modular Solutions</h3>
                    <ul class="mt-3.5 space-y-2.5 text-sm text-ivory-200">
                        <li><a href="{{ route('solutions.soilsense') }}" class="hover:text-white">REGEN SoilSense</a></li>
                        <li><a href="{{ route('solutions.watersense') }}" class="hover:text-white">REGEN WaterSense</a></li>
                        <li><a href="{{ route('solutions.envirosense') }}" class="hover:text-white">REGEN EnviroSense</a></li>
                        <li><a href="{{ route('solutions.terra') }}" class="hover:text-white">REGEN Terra</a></li>
                        <li><a href="{{ route('solutions.index') }}" class="hover:text-white">Semua Katalog Produk</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-sage-300">Platform &amp; Riset</h3>
                    <ul class="mt-3.5 space-y-2.5 text-sm text-ivory-200">
                        <li><a href="{{ route('platform') }}" class="hover:text-white">Farm Management &amp; BI</a></li>
                        <li><a href="{{ route('enterprise') }}" class="hover:text-white">REGEN Enterprise</a></li>
                        <li><a href="{{ route('prototype.index') }}" class="hover:text-white">Galeri Prototype &amp; R&amp;D</a></li>
                        <li><a href="{{ route('pendukung.index') }}" class="hover:text-white">Dokumen Pendukung &amp; HAKI</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white">Tentang REGEN</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-sage-300">Akses Ekosistem</h3>
                    <ul class="mt-3.5 space-y-2.5 text-sm text-ivory-200">
                        <li><a href="{{ route('app.dashboard') }}" class="hover:text-white">Aplikasi Manajemen Kebun (/app)</a></li>
                        <li><a href="{{ route('enterprise.dashboard') }}" class="hover:text-white">Enterprise Dashboard</a></li>
                        <li><a href="{{ route('admin.overview') }}" class="hover:text-white">Administrator CMS (/admin)</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Permintaan Demo &amp; Kemitraan</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col justify-between gap-4 border-t border-forest-800 pt-6 text-xs text-sage-300 sm:flex-row sm:items-center">
                <p>&copy; {{ date('Y') }} REGEN by AGRONEX. Seluruh hak cipta dilindungi.</p>
                <p>Transparansi Sistem: Modul telemetri tanpa perangkat fisik aktif ditandai jelas sebagai <span class="font-semibold text-ivory-100">Demo / Simulasi</span> atau <span class="font-semibold text-ivory-100">Roadmap</span>.</p>
            </div>
        </div>
    </footer>
</body>
</html>

