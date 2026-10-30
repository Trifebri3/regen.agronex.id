<x-layouts.public title="Enterprise Multi-Tenant Agriculture Platform — REGEN">
    <section class="border-b border-forest-800 bg-forest-950 py-14 text-ivory-50 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-sage-300">Modul H &bull; Multi-Organization Governance</span>
                <x-status-badge status="operational" label="Operational Multi-Tenant" />
            </div>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-5xl">
                REGEN Enterprise Agriculture Platform
            </h1>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-sage-200">
                Infrastruktur agribisnis multi-tenant untuk korporasi perkebunan, koperasi induk, offtaker rantai pasok, dan institusi riset dengan isolasi data antar-organisasi, RBAC 7 peran, audit trail, serta kesiapan API.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('contact', ['type' => 'enterprise_demo']) }}" class="rounded-xl bg-sage-500 px-5 py-3 text-sm font-semibold text-forest-950 hover:bg-sage-400">
                    Request Enterprise Demo
                </a>
                <a href="{{ route('enterprise.dashboard') }}" class="rounded-xl border border-forest-700 bg-forest-900 px-5 py-3 text-sm font-semibold text-white hover:border-sage-400">
                    Buka Enterprise Console (/enterprise/dashboard)
                </a>
            </div>
        </div>
    </section>

    <section class="border-b border-ivory-300 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                eyebrow="Arsitektur Multi-Tenant"
                title="10 Kapabilitas Inti REGEN Enterprise"
                subtitle="Memastikan keamanan, skalabilitas, dan pemisahan data mutlak di antara setiap entitas organisasi."
            />

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['Multi-Organization Isolation', 'Setiap organisasi memiliki ruang data privat; satu organisasi tidak pernah dapat mengakses lahan, sensor, atau keuangan organisasi lain.'],
                    ['Multi-Farm & Multi-Field', 'Konsolidasi performa lintas kebun, blok lahan, dan petak produksi dalam satu tampilan komando.'],
                    ['7-Role Access Control (RBAC)', 'Super Admin, Content Admin, Product Admin, Technical Admin, Organization Admin, Farm Manager, dan Farmer/User.'],
                    ['Centralized Device Fleet', 'Registrasi perangkat, status baterai & sinyal, pemisahan jelas unit Live vs Demo Simulation.'],
                    ['Organization-Level Reporting', 'Agregasi biaya produksi, panen, pendapatan bersih, dan ekspor data CSV tingkat korporasi.'],
                    ['Audit Trail & REST API v1', 'Pencatatan jejak audit keamanan otomatis dan endpoint JSON `/api/v1/enterprise/summary` terproteksi.'],
                ] as [$eTitle, $eDesc])
                    <div class="rounded-2xl border border-ivory-300 bg-ivory-50 p-6">
                        <h3 class="text-base font-semibold text-charcoal-900">{{ $eTitle }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-charcoal-600">{{ $eDesc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>

