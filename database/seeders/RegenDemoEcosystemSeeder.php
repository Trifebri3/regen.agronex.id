<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Advisory;
use App\Models\AuditLog;
use App\Models\ContactRequest;
use App\Models\CropCycle;
use App\Models\Device;
use App\Models\Farm;
use App\Models\FarmActivity;
use App\Models\FarmInput;
use App\Models\FinancialRecord;
use App\Models\HarvestRecord;
use App\Models\Land;
use App\Models\LandPlot;
use App\Models\Organization;
use App\Models\SalesRecord;
use App\Models\SensorReading;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RegenDemoEcosystemSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        $orgPrimary = Organization::updateOrCreate(
            ['slug' => 'agronex-nusantara-agro'],
            [
                'name' => 'PT Agronex Nusantara Agro',
                'code' => 'ORG-AGX-01',
                'industry_type' => 'Hortikultura & Agribisnis Terpadu',
                'subscription_tier' => 'enterprise',
                'contact_email' => 'ops@agronex-nusantara.id',
                'phone' => '+62 22 8780 4410',
                'address' => 'Jl. Raya Pangalengan Km 24, Kabupaten Bandung, Jawa Barat',
                'is_active' => true,
                'settings' => ['timezone' => 'Asia/Jakarta', 'currency' => 'IDR', 'demo_environment' => true],
            ]
        );

        $orgSecondary = Organization::updateOrCreate(
            ['slug' => 'koperasi-tani-priangan'],
            [
                'name' => 'Koperasi Tani Mandiri Priangan',
                'code' => 'ORG-KTP-02',
                'industry_type' => 'Koperasi Produksi Pangan',
                'subscription_tier' => 'integrated',
                'contact_email' => 'pengurus@priangan-tani.id',
                'phone' => '+62 262 541 229',
                'address' => 'Cisurupan, Kabupaten Garut, Jawa Barat',
                'is_active' => true,
                'settings' => ['timezone' => 'Asia/Jakarta', 'currency' => 'IDR'],
            ]
        );

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@regen.agronex.id'],
            [
                'organization_id' => null,
                'name' => 'Raka Pratama (Super Admin)',
                'role' => UserRole::SuperAdmin,
                'phone' => '+62 811 2000 101',
                'job_title' => 'Platform Architecture Lead',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        User::updateOrCreate(
            ['email' => 'content@regen.agronex.id'],
            [
                'organization_id' => null,
                'name' => 'Nadia Kusuma (Content Admin)',
                'role' => UserRole::ContentAdmin,
                'phone' => '+62 811 2000 102',
                'job_title' => 'CMS & Documentation Specialist',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        User::updateOrCreate(
            ['email' => 'product@regen.agronex.id'],
            [
                'organization_id' => null,
                'name' => 'Dimas Wibowo (Product Admin)',
                'role' => UserRole::ProductAdmin,
                'phone' => '+62 811 2000 103',
                'job_title' => 'Product Line Manager',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        User::updateOrCreate(
            ['email' => 'tech@regen.agronex.id'],
            [
                'organization_id' => null,
                'name' => 'Arif Rahman (Technical Admin)',
                'role' => UserRole::TechnicalAdmin,
                'phone' => '+62 811 2000 104',
                'job_title' => 'IoT & Field Telemetry Engineer',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        $orgAdminPrimary = User::updateOrCreate(
            ['email' => 'orgadmin@agronex-nusantara.id'],
            [
                'organization_id' => $orgPrimary->id,
                'name' => 'Hendra Wijaya',
                'role' => UserRole::OrganizationAdmin,
                'phone' => '+62 812 2300 401',
                'job_title' => 'VP of Farm Operations',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        $farmManagerPrimary = User::updateOrCreate(
            ['email' => 'manager@agronex-nusantara.id'],
            [
                'organization_id' => $orgPrimary->id,
                'name' => 'Siti Rahmawati, SP.',
                'role' => UserRole::FarmManager,
                'phone' => '+62 812 2300 402',
                'job_title' => 'Kepala Kebun Pangalengan',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        $farmerPrimary = User::updateOrCreate(
            ['email' => 'farmer@agronex-nusantara.id'],
            [
                'organization_id' => $orgPrimary->id,
                'name' => 'Pak Dedi Suryadi',
                'role' => UserRole::Farmer,
                'phone' => '+62 813 2100 889',
                'job_title' => 'Koordinator Lapangan Blok Utara',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        $orgAdminSecondary = User::updateOrCreate(
            ['email' => 'orgadmin@priangan-tani.id'],
            [
                'organization_id' => $orgSecondary->id,
                'name' => 'Asep Saepudin',
                'role' => UserRole::OrganizationAdmin,
                'phone' => '+62 813 9400 112',
                'job_title' => 'Ketua Koperasi',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $defaultPassword,
            ]
        );

        // Organization 1 — Farm 1 & Farm 2
        $farm1 = Farm::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'code' => 'FRM-PGL-01'],
            [
                'manager_id' => $farmManagerPrimary->id,
                'name' => 'Kebun Pangalengan Highland',
                'location' => 'Margamekar, Pangalengan, Kab. Bandung',
                'province' => 'Jawa Barat',
                'latitude' => -7.1778000,
                'longitude' => 107.5724000,
                'total_area_ha' => 18.50,
                'elevation_m' => 1420,
                'status' => 'active',
                'notes' => 'Pusat budidaya hortikultura dataran tinggi (Cabai Merah, Kentang Granola, Tomat Beef).',
            ]
        );

        $farm2 = Farm::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'code' => 'FRM-LMB-02'],
            [
                'manager_id' => $farmManagerPrimary->id,
                'name' => 'Kebun Lembang Precision Valley',
                'location' => 'Cibodas, Lembang, Bandung Barat',
                'province' => 'Jawa Barat',
                'latitude' => -6.8184000,
                'longitude' => 107.6431000,
                'total_area_ha' => 9.20,
                'elevation_m' => 1260,
                'status' => 'active',
                'notes' => 'Unit produksi sayuran daun bernilai tinggi dan rumah kaca irigasi tetes.',
            ]
        );

        // Organization 2 — Isolated Farm
        $farmSecondary = Farm::updateOrCreate(
            ['organization_id' => $orgSecondary->id, 'code' => 'FRM-GRT-01'],
            [
                'manager_id' => $orgAdminSecondary->id,
                'name' => 'Kebun Koperasi Cisurupan Garut',
                'location' => 'Cisurupan, Kabupaten Garut',
                'province' => 'Jawa Barat',
                'latitude' => -7.3195000,
                'longitude' => 107.7852000,
                'total_area_ha' => 12.00,
                'elevation_m' => 1180,
                'status' => 'active',
                'notes' => 'Lahan produksi milik anggota Koperasi Tani Mandiri Priangan.',
            ]
        );

        Land::updateOrCreate(
            ['organization_id' => $orgSecondary->id, 'farm_id' => $farmSecondary->id, 'code' => 'LND-GRT-A1'],
            [
                'name' => 'Lahan Koperasi Blok Papandayan',
                'location' => 'Sektor Timur Cisurupan',
                'area_ha' => 6.00,
                'ownership_status' => 'partnership',
                'soil_type' => 'Andosol',
                'soil_ph' => 6.30,
                'soil_profile_notes' => 'Data eksklusif Koperasi Priangan (terisolasi dari organisasi lain).',
                'status' => 'active',
            ]
        );

        // Lands & Plots for Farm 1
        $land1 = Land::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'farm_id' => $farm1->id, 'code' => 'LND-PGL-A'],
            [
                'name' => 'Lahan Terasering Sektor Utara A',
                'location' => 'Lereng Utara Pangalengan',
                'area_ha' => 8.50,
                'ownership_status' => 'owned',
                'soil_type' => 'Andosol Vulkanik Gembur',
                'soil_ph' => 6.35,
                'soil_profile_notes' => 'C-Organik 3.4%, kapasitas tukar kation tinggi, drainase lereng berteras.',
                'photo_path' => 'images/regen-hero-farmland.jpg',
                'status' => 'active',
            ]
        );

        $land2 = Land::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'farm_id' => $farm2->id, 'code' => 'LND-LMB-B'],
            [
                'name' => 'Blok Irigasi Tetes Cibodas B',
                'location' => 'Lembah Cibodas',
                'area_ha' => 5.20,
                'ownership_status' => 'leased',
                'soil_type' => 'Latosol Cokelat Kemerahan',
                'soil_ph' => 5.95,
                'soil_profile_notes' => 'Terpasang manifold irigasi tetes 4 zona.',
                'photo_path' => 'images/regen-farmer-tablet.jpg',
                'status' => 'active',
            ]
        );

        $plotA1 = LandPlot::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'land_id' => $land1->id, 'plot_code' => 'PLT-A1'],
            [
                'name' => 'Petak A1 — Cabai Presisi',
                'area_ha' => 4.50,
                'soil_condition' => 'Optimal (pH 6.4, Mulsa Perak Hitam)',
                'irrigation_type' => 'Drip Fertigation',
                'notes' => 'Dipasang node SoilSense & WaterSense simulasi.',
            ]
        );

        $plotA2 = LandPlot::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'land_id' => $land1->id, 'plot_code' => 'PLT-A2'],
            [
                'name' => 'Petak A2 — Kentang Bibit',
                'area_ha' => 4.00,
                'soil_condition' => 'Baik (pH 6.2)',
                'irrigation_type' => 'Sprinkler Terjadwal',
                'notes' => 'Rotasi pasca panen buncis.',
            ]
        );

        $cycle1 = CropCycle::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'cycle_code' => 'CYC-2026-CAB01'],
            [
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'land_plot_id' => $plotA1->id,
                'crop_name' => 'Cabai Merah Besar',
                'variety' => 'Pilar F1',
                'planting_date' => '2026-07-20',
                'estimated_harvest_date' => '2026-10-25',
                'planting_area_ha' => 4.50,
                'seed_requirement' => 1.35,
                'seed_unit' => 'kg',
                'cultivation_status' => 'harvesting',
                'target_yield_kg' => 54000,
                'notes' => 'Memasuki petikan panen ke-3 dengan kualitas Grade A dominan.',
            ]
        );

        $cycle2 = CropCycle::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'cycle_code' => 'CYC-2026-KNT02'],
            [
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'land_plot_id' => $plotA2->id,
                'crop_name' => 'Kentang Industri & Sayur',
                'variety' => 'Granola L',
                'planting_date' => '2026-09-10',
                'estimated_harvest_date' => '2026-12-18',
                'planting_area_ha' => 4.00,
                'seed_requirement' => 4800,
                'seed_unit' => 'kg',
                'cultivation_status' => 'vegetative',
                'target_yield_kg' => 68000,
                'notes' => 'Fase pembumbunan dan pemupukan susulan kedua.',
            ]
        );

        $cycle3 = CropCycle::updateOrCreate(
            ['organization_id' => $orgPrimary->id, 'cycle_code' => 'CYC-2026-TMT03'],
            [
                'farm_id' => $farm2->id,
                'land_id' => $land2->id,
                'land_plot_id' => null,
                'crop_name' => 'Tomat Beef Greenhouse',
                'variety' => 'Momotaro Toure',
                'planting_date' => '2026-08-15',
                'estimated_harvest_date' => '2026-11-12',
                'planting_area_ha' => 2.60,
                'seed_requirement' => 0.45,
                'seed_unit' => 'kg',
                'cultivation_status' => 'generative',
                'target_yield_kg' => 42000,
                'notes' => 'Pembentukan tandan buah ke-4.',
            ]
        );

        if ($farm1->activities()->count() === 0) {
            FarmActivity::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'crop_cycle_id' => $cycle1->id,
                'recorded_by' => $farmerPrimary->id,
                'activity_type' => 'land_preparation',
                'title' => 'Pengolahan Bedengan, Aplikasi Kompos & Pemasangan Mulsa',
                'activity_date' => '2026-07-08',
                'labor_count' => 18,
                'labor_hours' => 126,
                'cost_incurred' => 14500000,
                'status' => 'completed',
                'notes' => 'Pemberian 9 ton kompos fermentasi dan dolomit.',
            ]);

            FarmActivity::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'crop_cycle_id' => $cycle1->id,
                'recorded_by' => $farmerPrimary->id,
                'activity_type' => 'irrigation',
                'title' => 'Fertigasi Tetes Zona Petak A1',
                'activity_date' => '2026-10-26',
                'labor_count' => 2,
                'labor_hours' => 6,
                'water_volume_liters' => 18500,
                'cost_incurred' => 450000,
                'status' => 'completed',
                'notes' => 'Distribusi air terukur mengikuti rekomendasi kelembapan tanah.',
            ]);

            FarmActivity::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'crop_cycle_id' => $cycle1->id,
                'recorded_by' => $farmManagerPrimary->id,
                'activity_type' => 'harvest',
                'title' => 'Panen Petikan Ke-3 Cabai Merah Pilar F1',
                'activity_date' => '2026-10-28',
                'labor_count' => 24,
                'labor_hours' => 144,
                'cost_incurred' => 4800000,
                'status' => 'completed',
                'notes' => 'Sortasi langsung di bangsal pascapanen.',
            ]);
        }

        if ($farm1->inputs()->count() === 0) {
            FarmInput::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'input_category' => 'seeds',
                'name' => 'Benih Cabai Merah Besar Pilar F1',
                'brand_or_source' => 'Panah Merah Certified',
                'unit' => 'pack',
                'quantity_purchased' => 90,
                'quantity_used' => 86,
                'unit_price' => 195000,
                'total_cost' => 17550000,
                'purchase_date' => '2026-07-02',
            ]);

            FarmInput::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'input_category' => 'fertilizers',
                'name' => 'Pupuk NPK Mutiara 16-16-16 & KNO3 Generatif',
                'brand_or_source' => 'Distributor Resmi Bandung',
                'unit' => 'kg',
                'quantity_purchased' => 1800,
                'quantity_used' => 1550,
                'unit_price' => 18500,
                'total_cost' => 33300000,
                'purchase_date' => '2026-08-05',
            ]);

            FarmInput::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'input_category' => 'organic_materials',
                'name' => 'Kompos Organik Matang & Asam Humat',
                'brand_or_source' => 'Unit Kompos Pangalengan',
                'unit' => 'kg',
                'quantity_purchased' => 9000,
                'quantity_used' => 9000,
                'unit_price' => 1400,
                'total_cost' => 12600000,
                'purchase_date' => '2026-07-05',
            ]);
        }

        if ($farm1->harvests()->count() === 0) {
            $harvest1 = HarvestRecord::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'harvest_date' => '2026-10-21',
                'total_quantity_kg' => 8400,
                'product_grade' => 'Grade A',
                'quality_notes' => 'Ukuran seragam >16 cm, warna merah cerah merata.',
                'saleable_quantity_kg' => 8120,
                'sold_quantity_kg' => 8000,
                'unsold_quantity_kg' => 120,
                'post_harvest_loss_kg' => 280,
                'recorded_by' => $farmManagerPrimary->id,
            ]);

            $harvest2 = HarvestRecord::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'harvest_date' => '2026-10-28',
                'total_quantity_kg' => 9600,
                'product_grade' => 'Grade A',
                'quality_notes' => 'Petikan puncak, kadar susut rendah 2.6%.',
                'saleable_quantity_kg' => 9350,
                'sold_quantity_kg' => 9200,
                'unsold_quantity_kg' => 150,
                'post_harvest_loss_kg' => 250,
                'recorded_by' => $farmManagerPrimary->id,
            ]);

            SalesRecord::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'harvest_record_id' => $harvest1->id,
                'invoice_number' => 'INV-AGX-2026-1021',
                'buyer_name' => 'PT Segar Pangan Nusantara (Offtaker Ritel)',
                'buyer_contact' => 'procurement@segarpangan.co.id',
                'buyer_type' => 'offtaker',
                'product_name' => 'Cabai Merah Besar Grade A',
                'quantity_sold_kg' => 8000,
                'price_per_kg' => 18500,
                'gross_revenue' => 148000000,
                'distribution_cost' => 3200000,
                'net_revenue' => 144800000,
                'transaction_date' => '2026-10-22',
                'payment_status' => 'paid',
            ]);

            SalesRecord::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'harvest_record_id' => $harvest2->id,
                'invoice_number' => 'INV-AGX-2026-1028',
                'buyer_name' => 'Pasar Induk Caringin & HoReCa Bandung',
                'buyer_contact' => '+62 812 8899 1100',
                'buyer_type' => 'wholesaler',
                'product_name' => 'Cabai Merah Besar Grade A',
                'quantity_sold_kg' => 9200,
                'price_per_kg' => 19000,
                'gross_revenue' => 174800000,
                'distribution_cost' => 3600000,
                'net_revenue' => 171200000,
                'transaction_date' => '2026-10-29',
                'payment_status' => 'paid',
            ]);
        }

        if ($farm1->financialRecords()->count() === 0) {
            $financialEntries = [
                ['capital', 'initial_capital', 'capital', 'Modal Kerja Awal Siklus Hortikultura Q3-Q4', 200000000, '2026-07-01', 'CAP-2026-01'],
                ['expense', 'land_preparation', 'variable', 'Pengolahan Lahan, Mulsa & Bedengan Petak A1', 14500000, '2026-07-08', 'EXP-LP-01'],
                ['expense', 'input_cost', 'variable', 'Pengadaan Benih, Kompos, Pupuk NPK & Proteksi Tanaman', 63450000, '2026-08-05', 'EXP-IN-01'],
                ['expense', 'labor_cost', 'variable', 'Upah Tenaga Kerja Tanam, Pemeliharaan & Panen', 34200000, '2026-10-28', 'EXP-LB-01'],
                ['expense', 'operational_cost', 'variable', 'Listrik Pompa Fertigasi & Operasional Kebun', 8900000, '2026-10-25', 'EXP-OP-01'],
                ['expense', 'equipment_cost', 'fixed', 'Alokasi Penyusutan Pipa Drip, Alat & Sewa Lahan Musiman', 18000000, '2026-07-15', 'EXP-EQ-01'],
                ['expense', 'transportation_cost', 'variable', 'Biaya Distribusi Armada Cold-Box ke Offtaker', 6800000, '2026-10-29', 'EXP-TR-01'],
                ['revenue', 'sales_revenue', 'revenue', 'Penerimaan Penjualan Cabai Merah INV-1021 & INV-1028', 322800000, '2026-10-29', 'REV-SL-01'],
            ];

            foreach ($financialEntries as [$type, $cat, $nature, $title, $amount, $date, $ref]) {
                FinancialRecord::create([
                    'organization_id' => $orgPrimary->id,
                    'farm_id' => $farm1->id,
                    'crop_cycle_id' => $cycle1->id,
                    'transaction_type' => $type,
                    'category' => $cat,
                    'cost_nature' => $nature,
                    'title' => $title,
                    'amount' => $amount,
                    'transaction_date' => $date,
                    'reference_code' => $ref,
                    'recorded_by' => $orgAdminPrimary->id,
                ]);
            }
        }

        // Seed clearly labeled DEMO Simulation Devices & Telemetry
        $deviceSoil = Device::updateOrCreate(
            ['device_uid' => 'RGN-SS-DEMO-001'],
            [
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'land_plot_id' => $plotA1->id,
                'name' => 'SoilSense Probe A1-North (Demo Simulasi)',
                'product_line' => 'soilsense',
                'firmware_version' => 'v1.4.2-sim',
                'connection_mode' => 'demo_simulation',
                'is_demo' => true,
                'status' => 'online',
                'battery_level' => 94,
                'signal_rssi' => -71,
                'last_seen_at' => now()->subMinutes(8),
                'sensor_config' => ['depth_cm' => 15, 'parameters' => ['pH', 'VWC', 'EC', 'NPK']],
            ]
        );

        $deviceWater = Device::updateOrCreate(
            ['device_uid' => 'RGN-WS-DEMO-002'],
            [
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'land_id' => $land1->id,
                'land_plot_id' => $plotA1->id,
                'name' => 'WaterSense Manifold Controller A1 (Demo Simulasi)',
                'product_line' => 'watersense',
                'firmware_version' => 'v1.2.0-sim',
                'connection_mode' => 'demo_simulation',
                'is_demo' => true,
                'status' => 'online',
                'battery_level' => 89,
                'signal_rssi' => -75,
                'last_seen_at' => now()->subMinutes(5),
                'sensor_config' => ['zones' => 4, 'flow_meter' => 'ultrasonic'],
            ]
        );

        $deviceEnviro = Device::updateOrCreate(
            ['device_uid' => 'RGN-ES-DEMO-003'],
            [
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm2->id,
                'land_id' => $land2->id,
                'land_plot_id' => null,
                'name' => 'EnviroSense Microclimate Station Lembang (Demo Simulasi)',
                'product_line' => 'envirosense',
                'firmware_version' => 'v1.3.1-sim',
                'connection_mode' => 'demo_simulation',
                'is_demo' => true,
                'status' => 'online',
                'battery_level' => 97,
                'signal_rssi' => -68,
                'last_seen_at' => now()->subMinutes(3),
                'sensor_config' => ['shield' => '7-plate UV', 'rain_gauge' => '0.2mm tipping bucket'],
            ]
        );

        if ($deviceSoil->readings()->count() === 0) {
            $hours = [24, 18, 12, 6, 1];
            $phSeries = [6.32, 6.35, 6.38, 6.40, 6.36];
            $moistSeries = [58.4, 54.1, 49.8, 62.5, 60.2];
            $ecSeries = [1.42, 1.45, 1.48, 1.39, 1.41];

            foreach ($hours as $idx => $h) {
                SensorReading::create([
                    'organization_id' => $orgPrimary->id,
                    'device_id' => $deviceSoil->id,
                    'is_demo' => true,
                    'recorded_at' => now()->subHours($h),
                    'soil_ph' => $phSeries[$idx],
                    'soil_moisture_pct' => $moistSeries[$idx],
                    'soil_ec_ds_m' => $ecSeries[$idx],
                    'nitrogen_mg_kg' => 142 + $idx,
                    'phosphorus_mg_kg' => 58 + $idx,
                    'potassium_mg_kg' => 210 + ($idx * 2),
                ]);
            }

            SensorReading::create([
                'organization_id' => $orgPrimary->id,
                'device_id' => $deviceWater->id,
                'is_demo' => true,
                'recorded_at' => now()->subMinutes(30),
                'water_flow_lpm' => 42.5,
                'water_level_cm' => 168.0,
            ]);

            SensorReading::create([
                'organization_id' => $orgPrimary->id,
                'device_id' => $deviceEnviro->id,
                'is_demo' => true,
                'recorded_at' => now()->subMinutes(15),
                'ambient_temp_c' => 23.4,
                'humidity_pct' => 78.5,
                'rainfall_mm' => 2.4,
            ]);
        }

        if ($orgPrimary->farms()->first()?->advisories()->count() === 0) {
            Advisory::create([
                'organization_id' => $orgPrimary->id,
                'farm_id' => $farm1->id,
                'crop_cycle_id' => $cycle1->id,
                'device_id' => $deviceSoil->id,
                'source_type' => 'rule_based',
                'is_demo' => true,
                'severity' => 'optimal',
                'category' => 'soil_health',
                'title' => 'Kondisi Zona Akar Petak A1 Optimal (Data Simulasi)',
                'recommendation' => 'Pertahankan jadwal fertigasi pagi hari; pH 6.36 dan kelembapan 60.2% berada dalam rentang ideal fase panen cabai.',
                'rationale' => 'Rule SoilSense: pH 6.0–6.8 & VWC 55%–70%.',
            ]);
        }

        if (ContactRequest::count() === 0) {
            ContactRequest::create([
                'request_type' => 'enterprise_demo',
                'full_name' => 'Ir. Bambang Sutrisno',
                'email' => 'b.sutrisno@agro-mitra.co.id',
                'phone' => '+62 811 9920 331',
                'organization_name' => 'PT Mitra Pangan Lestari',
                'role_title' => 'Direktur Operasional Kebun',
                'interested_modules' => ['soilsense', 'farm-management', 'enterprise'],
                'land_scale' => '> 250 Ha (Multi-Kebun)',
                'message' => 'Kami ingin menjadwalkan presentasi arsitektur multi-farm REGEN Enterprise untuk 4 kebun kemitraan kami di Jawa Barat dan Jawa Timur.',
                'status' => 'new',
            ]);
        }

        if (AuditLog::count() === 0) {
            AuditLog::create([
                'organization_id' => $orgPrimary->id,
                'user_id' => $superAdmin->id,
                'action' => 'system.ecosystem_seeded',
                'auditable_type' => Organization::class,
                'auditable_id' => $orgPrimary->id,
                'ip_address' => '127.0.0.1',
                'metadata' => ['note' => 'Inisialisasi awal ekosistem REGEN by AGRONEX'],
            ]);
        }
    }
}
