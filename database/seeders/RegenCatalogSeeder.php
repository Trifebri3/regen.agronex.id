<?php

namespace Database\Seeders;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use App\Enums\PrototypeCategory;
use App\Models\Product;
use App\Models\Prototype;
use App\Models\PrototypeImage;
use App\Models\SiteContent;
use App\Models\SupportingDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RegenCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'slug' => 'soilsense',
                'code' => 'RGN-SS',
                'name' => 'REGEN SoilSense',
                'tagline' => 'Pemantauan Presisi Kesehatan Tanah & Nutrisi Zona Akar',
                'category' => 'hardware_iot',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'pilot_demo',
                'short_description' => 'Sistem sensor tanah multi-parameter untuk memantau pH tanah, kelembapan zona akar, konduktivitas listrik (EC), serta profil NPK sesuai spesifikasi probe.',
                'description' => 'REGEN SoilSense memberikan visibilitas kuantitatif terhadap kondisi kimia dan fisika tanah secara berkelanjutan. Dirancang untuk ketahanan lapangan tropis, modul ini membantu petani dan agronom mengoptimalkan dosis pemupukan, mencegah degradasi pH, serta menjaga kesehatan biologis tanah jangka panjang.',
                'features' => [
                    'Soil pH monitoring (pemantauan kemasaman tanah real-time)',
                    'Soil moisture monitoring (kelembapan volumetrik zona perakaran)',
                    'EC monitoring (konduktivitas listrik & indikasi salinitas larutan tanah)',
                    'NPK monitoring sesuai konfigurasi sensor industri RS485',
                    'Soil health data longitudinal & histori tren kesuburan lahan',
                ],
                'specifications' => [
                    'Antarmuka Sensor' => 'Industrial RS485 Modbus RTU',
                    'Rentang pH' => '3.0 – 9.0 pH (±0.15 pH)',
                    'Rentang Kelembapan' => '0 – 100% VWC (±2–3%)',
                    'Rentang EC' => '0 – 10.0 dS/m',
                    'Proteksi Enclosure' => 'IP68 Probe / IP66 Node Telemetry',
                    'Daya & Transmisi' => 'Solar + LiFePO4 / LoRaWAN & 4G Fallback',
                ],
                'metrics' => [
                    'Parameter Inti' => '5 Metrik Tanah',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                    'Status Kesiapan' => 'Pilot & Demo Simulasi',
                ],
                'hero_image' => 'images/regen-soilsense-probe.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'watersense',
                'code' => 'RGN-WS',
                'name' => 'REGEN WaterSense',
                'tagline' => 'Manajemen Irigasi Cerdas & Efisiensi Tata Kelola Air Pertanian',
                'category' => 'hardware_iot',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'pilot_demo',
                'short_description' => 'Pemantauan debit air, pencatatan konsumsi irigasi, dan integrasi aktuator irigasi presisi berbasis kebutuhan tanaman.',
                'description' => 'REGEN WaterSense menghubungkan ketersediaan air dengan kebutuhan aktual tanaman di setiap petak lahan. Dengan pencatatan volume irigasi terukur dan integrasi katup irigasi pintar, WaterSense menekan pemborosan air sekaligus mencegah cekaman kekeringan maupun genangan.',
                'features' => [
                    'Water monitoring (debit aliran, tekanan pipa, dan level tandon)',
                    'Irrigation management (penjadwalan & zonasi blok irigasi)',
                    'Water usage recording (audit pemakaian air per siklus tanam)',
                    'Smart irrigation integration (siap diintegrasikan dengan SoilSense & katup solenoid)',
                ],
                'specifications' => [
                    'Sensor Aliran' => 'Pulse / Ultrasonic Flow Meter Support',
                    'Kontrol Katup' => '2–4 Zona Latching Solenoid Relay (12V/24V)',
                    'Pencatatan Volume' => 'Liter per menit (LPM) & akumulasi m³',
                    'Konektivitas' => 'LoRaWAN / Wi-Fi / 4G Cellular Gateway',
                    'Proteksi Lapangan' => 'IP66 Weatherproof Enclosure',
                ],
                'metrics' => [
                    'Zonasi Kontrol' => 'Hingga 4 Blok/Node',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                    'Status Kesiapan' => 'Pilot & Demo Simulasi',
                ],
                'hero_image' => 'images/regen-farmer-tablet.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'envirosense',
                'code' => 'RGN-ES',
                'name' => 'REGEN EnviroSense',
                'tagline' => 'Stasiun Iklim Mikro Lapangan & Mitigasi Risiko Lingkungan',
                'category' => 'hardware_iot',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'pilot_demo',
                'short_description' => 'Pemantauan suhu udara, kelembapan relatif, curah hujan lokal, dan dinamika mikroklimat kebun maupun greenhouse.',
                'description' => 'Iklim mikro di tingkat tajuk tanaman sering kali berbeda signifikan dengan prakiraan cuaca regional. REGEN EnviroSense merekam parameter atmosfer lokal secara presisi untuk mengantisipasi risiko ledakan patogen jamur, stres panas, dan jendela waktu penyemprotan yang aman.',
                'features' => [
                    'Temperature monitoring (suhu udara tajuk & fluktuasi harian)',
                    'Humidity monitoring (kelembapan relatif & indikasi titik embun/VPD)',
                    'Environmental monitoring (curah hujan lokal & intensitas cahaya opsional)',
                    'Microclimate data untuk peringatan dini risiko penyakit tanaman',
                ],
                'specifications' => [
                    'Rentang Suhu' => '-20 °C hingga +70 °C (±0.2 °C)',
                    'Rentang Kelembapan' => '0 – 100% RH (±1.8% RH)',
                    'Pelindung Radiasi' => 'Multi-plate UV-stabilized Radiation Shield',
                    'Catu Daya' => 'Panel Surya 10W + Baterai Cadangan 14 Hari',
                ],
                'metrics' => [
                    'Akurasi Suhu' => '±0.2 °C',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                    'Status Kesiapan' => 'Pilot & Demo Simulasi',
                ],
                'hero_image' => 'images/regen-hero-farmland.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'terra',
                'code' => 'RGN-TR',
                'name' => 'REGEN Terra',
                'tagline' => 'Tata Kelola Lahan Spasial, Pemetaan Blok & Monitoring Petak',
                'category' => 'spatial',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'operational',
                'short_description' => 'Manajemen aset lahan, pembagian blok/petak (plot), pencatatan profil tanah spasial, dan pemantauan status penggunaan lahan.',
                'description' => 'REGEN Terra mengubah pencatatan lahan yang terfragmentasi menjadi peta struktur agronomi yang terorganisasi. Setiap kebun dapat dibagi menjadi lahan dan petak produksi lengkap dengan status kepemilikan, riwayat pH tanah, dokumentasi foto lapangan, serta keterkaitan langsung dengan siklus tanam dan perangkat sensor.',
                'features' => [
                    'Land management (registrasi kebun, status kepemilikan/sewa, dan luasan)',
                    'Field mapping (struktur blok lahan & pembagian plot produksi)',
                    'Spatial agricultural data (koordinat lokasi, elevasi, dan karakter tanah)',
                    'Land monitoring (pelacakan status aktif, bera/fallow, atau persiapan lahan)',
                ],
                'specifications' => [
                    'Hierarki Spasial' => 'Organization → Farm → Land → Plot',
                    'Atribut Tanah' => 'Tipe tanah, pH dasar, status kepemilikan, foto lahan',
                    'Format Koordinat' => 'WGS84 Latitude/Longitude & GeoJSON-ready',
                    'Integrasi Modul' => 'Terhubung langsung ke Farm Management & SoilSense',
                ],
                'metrics' => [
                    'Hierarki Lahan' => '4 Tingkat',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                    'Status Kesiapan' => 'Operational',
                ],
                'hero_image' => 'images/regen-hero-farmland.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'intelligence',
                'code' => 'RGN-IN',
                'name' => 'REGEN Intelligence',
                'tagline' => 'Analitik Agronomi, Rule-Based Advisory & Dukungan Keputusan',
                'category' => 'intelligence',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'pilot_demo',
                'short_description' => 'Mesin integrasi data pertanian yang menggabungkan rekomendasi berbasis aturan agronomi (rule-based) dan roadmap AI-assisted advisory.',
                'description' => 'REGEN Intelligence menyatukan telemetri sensor dengan catatan budidaya aktual menjadi rekomendasi tindakan yang jelas. Saat ini mesin Rule-Based Advisory aktif mengevaluasi ambang batas pH, kelembapan, dan mikroklimat, sementara kapabilitas AI-Assisted Predictive Advisory dikembangkan secara bertahap pada peta jalan (Roadmap).',
                'features' => [
                    'Data integration lintas sensor lapangan dan log aktivitas budidaya',
                    'Agricultural analytics untuk evaluasi performa per siklus tanam',
                    'Rule-based recommendation (Operational pada data aktual maupun simulasi)',
                    'AI-assisted advisory (Roadmap — pelabelan transparan tanpa klaim berlebihan)',
                    'Historical data analysis & decision support terstruktur',
                ],
                'specifications' => [
                    'Engine Aktif' => 'Deterministic Rule-Based Agronomic Threshold Engine',
                    'Roadmap Engine' => 'AI-Assisted Yield & Microclimate Advisory (Planned)',
                    'Transparansi Data' => 'Pemisahan tegas antara Live Device Data vs Demo Simulation',
                ],
                'metrics' => [
                    'Rule Engine' => 'Operational',
                    'AI Advisory' => 'Roadmap',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                ],
                'hero_image' => 'images/regen-farmer-tablet.jpg',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'farm-management',
                'code' => 'RGN-FM',
                'name' => 'REGEN Farm Management',
                'tagline' => 'Aplikasi Operasional Budidaya Harian dari Persiapan Lahan hingga Panen',
                'category' => 'software',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'operational',
                'short_description' => 'Platform manajemen operasional kebun end-to-end: perencanaan tanam, log aktivitas harian, input saprotan, tenaga kerja, panen, dan penjualan.',
                'description' => 'REGEN Farm Management dirancang agar dapat langsung digunakan oleh petani mandiri, kelompok tani, maupun manajer kebun tanpa harus menunggu pemasangan perangkat keras IoT. Seluruh catatan lahan, kalender tanam, pemakaian pupuk/benih, panen, hingga literasi agribisnis tersedia dalam satu antarmuka responsif.',
                'features' => [
                    'Land management & crop planning beserta kalender tanam',
                    'Farming activity logs, labor management & irrigation records',
                    'Input management (benih, pupuk, pestisida, bahan organik)',
                    'Harvest records (grade kualitas, kuantitas jual, susut pascapanen)',
                    'Sales records, financial management & Learning Center ramah petani',
                ],
                'specifications' => [
                    'Akses Perangkat' => 'Responsive Web App (Mobile, Tablet, Desktop)',
                    'Basis Data' => 'Pencatatan relasional terstruktur dengan histori siklus',
                    'Pusat Edukasi' => '12 Modul Literasi Budidaya & Bisnis Tani (3 Tingkat)',
                ],
                'metrics' => [
                    'Modul Aplikasi' => '10 Modul Inti',
                    'Status Kesiapan' => 'Operational',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                ],
                'hero_image' => 'images/regen-farmer-tablet.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 6,
            ],
            [
                'slug' => 'business',
                'code' => 'RGN-BZ',
                'name' => 'REGEN Business',
                'tagline' => 'Kalkulasi HPP, Titik Impas (BEP), dan Intelijen Finansial Usaha Tani',
                'category' => 'software',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'operational',
                'short_description' => 'Sistem perhitungan biaya produksi, pelacakan pendapatan, kalkulator HPP per kilogram, analisis BEP, dan laporan laba-rugi usaha tani.',
                'description' => 'Banyak usaha tani merugi bukan karena gagal panen, melainkan karena biaya produksi tersembunyi dan harga jual di bawah Harga Pokok Produksi (HPP). REGEN Business menyediakan kalkulator finansial presisi dengan pilihan basis kuantitas panen bruto maupun layak jual, lengkap dengan ekspor laporan.',
                'features' => [
                    'Production cost calculation (biaya tetap & biaya variabel terperinci)',
                    'Revenue tracking & sales management berbasis transaksi aktual',
                    'HPP calculation dengan indikator jelas basis panen vs layak jual',
                    'Break-even point (BEP kuantitas & BEP nilai penjualan)',
                    'Profit & loss overview serta unduh laporan CSV',
                ],
                'specifications' => [
                    'Basis HPP' => 'Harvested Quantity atau Saleable Quantity',
                    'Validasi Input' => 'Penanganan aman nilai nol, kosong, dan pembagian nol',
                    'Ekspor Laporan' => 'CSV Financial Summary Report',
                ],
                'metrics' => [
                    'Kalkulator BI' => 'Operational',
                    'Ekspor Data' => 'CSV Ready',
                    'Mode Operasi' => 'Modular / Terintegrasi',
                ],
                'hero_image' => 'images/regen-farmer-tablet.jpg',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 7,
            ],
            [
                'slug' => 'enterprise',
                'code' => 'RGN-EN',
                'name' => 'REGEN Enterprise',
                'tagline' => 'Arsitektur Multi-Tenant untuk Korporasi Agribisnis, Koperasi & Kemitraan',
                'category' => 'enterprise',
                'deployment_modes' => ['modular', 'integrated'],
                'readiness_status' => 'operational',
                'short_description' => 'Tata kelola multi-organisasi, multi-kebun, isolasi data ketat antar entitas, RBAC 7 peran, audit trail, dan kesiapan integrasi API.',
                'description' => 'REGEN Enterprise dirancang bagi perusahaan agribisnis, koperasi modern, offtaker, dan program pertanian kontrak yang mengelola banyak kebun serta tim lapangan. Setiap organisasi bekerja di ruang data yang terisolasi sepenuhnya dengan kontrol akses berbasis peran dan jejak audit.',
                'features' => [
                    'Multi-organization, multi-farm, multi-field, multi-device & multi-user',
                    'Role-based access control (7 level hak akses terverifikasi server-side)',
                    'Centralized analytics & organization-level reporting',
                    'API-ready JSON endpoints (`/api/v1/...`) & CSV data export',
                    'Audit trail keamanan untuk setiap operasi kritikal',
                ],
                'specifications' => [
                    'Isolasi Data' => 'Strict Organization-ID Tenant Boundary Enforcement',
                    'Kontrol Akses' => '7 Peran RBAC + Middleware & Server-side Checks',
                    'Audit & API' => 'Audit Logs Tabel Terdedikasi + REST JSON Resource',
                ],
                'metrics' => [
                    'Multi-Tenancy' => 'Operational',
                    'RBAC' => '7 Peran',
                    'API' => 'REST v1 Ready',
                ],
                'hero_image' => 'images/regen-hero-farmland.jpg',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }

        $siteContents = [
            [
                'section_key' => 'home.hero',
                'page' => 'home',
                'title' => 'Grow Smarter. Manage Better. Regenerate the Future.',
                'subtitle' => 'An Integrated Smart Agriculture & Farm Management Platform — Produk Unggulan dari AGRONEX.',
                'body' => 'REGEN mengintegrasikan teknologi IoT, sensor lingkungan, analisis data, dan manajemen usaha tani untuk membantu petani mengelola pertanian secara lebih presisi, produktif, dan berkelanjutan.',
                'metadata' => [
                    'parent_brand' => 'AGRONEX',
                    'parent_url' => 'https://agronex.id',
                    'product_domain' => 'https://regen.agronex.id',
                ],
            ],
            [
                'section_key' => 'about.overview',
                'page' => 'about',
                'title' => 'Tentang REGEN oleh AGRONEX',
                'subtitle' => 'Ekosistem Teknologi Pertanian Modular & Terintegrasi untuk Pertanian Presisi Indonesia',
                'body' => 'REGEN dikembangkan sebagai produk unggulan (flagship product) dari AGRONEX (https://agronex.id) dengan misi menjembatani kesenjangan antara pemantauan agronomi berbasis sains dan tata kelola bisnis usaha tani sehari-hari.',
                'metadata' => ['established' => '2026'],
            ],
            [
                'section_key' => 'contact.office',
                'page' => 'contact',
                'title' => 'Hubungi Tim Produk & Solusi REGEN',
                'subtitle' => 'Konsultasi implementasi modular, uji coba platform manajemen kebun, atau kemitraan enterprise.',
                'body' => 'Divisi Produk REGEN — PT Agronex Teknologi Pertanian. Email: regen@agronex.id | Website Utama Korporat: https://agronex.id',
                'metadata' => ['email' => 'regen@agronex.id', 'parent_site' => 'https://agronex.id'],
            ],
        ];

        foreach ($siteContents as $content) {
            SiteContent::updateOrCreate(['section_key' => $content['section_key']], $content);
        }

        $prototypes = [
            [
                'slug' => 'soilsense-v2-andosol-field-calibration',
                'title' => 'Uji Kalibrasi Lapangan REGEN SoilSense Node v1.4 pada Tanah Andosol',
                'category' => PrototypeCategory::SoilSense,
                'description' => 'Pengujian akurasi pembacaan pH tanah, kelembapan volumetrik (VWC), dan EC secara kontinu selama 30 hari pada lahan cabai dataran tinggi dengan komparasi instrumen laboratorium standar.',
                'technical_notes' => 'Probe 5-pin stainless steel 316L RS485 Modbus RTU, konsumsi daya tidur 180 µA, interval transmisi LoRaWAN 15 menit, deviasi rata-rata pH ±0.12 terhadap uji sampel basah.',
                'testing_date' => '2026-09-18',
                'location' => 'Pangalengan, Kabupaten Bandung, Jawa Barat',
                'thumbnail_path' => 'images/regen-soilsense-probe.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'is_featured' => true,
                'publication_status' => 'published',
                'gallery' => [
                    ['image_path' => 'images/regen-soilsense-probe.jpg', 'caption' => 'Pemasangan probe SoilSense pada zona perakaran 15 cm.'],
                    ['image_path' => 'images/regen-farmer-tablet.jpg', 'caption' => 'Verifikasi telemetri pH dan kelembapan melalui tablet lapangan.'],
                ],
            ],
            [
                'slug' => 'envirosense-solar-microclimate-station-trial',
                'title' => 'Instalasi Stasiun Mikroklimat Surya REGEN EnviroSense di Lahan Terasering',
                'category' => PrototypeCategory::EnviroSense,
                'description' => 'Evaluasi ketahanan catu daya panel surya 10W dan keandalan telemetri suhu, kelembapan udara, serta curah hujan pada kondisi kabut tebal pegunungan.',
                'technical_notes' => 'Baterai LiFePO4 6.4V 6000mAh bertahan stabil di atas 84% selama 7 hari cuaca mendung beruntun. Sensor terlindung radiation shield 7-plate.',
                'testing_date' => '2026-10-04',
                'location' => 'Lembang, Bandung Barat, Jawa Barat',
                'thumbnail_path' => 'images/regen-hero-farmland.jpg',
                'video_url' => null,
                'is_featured' => true,
                'publication_status' => 'published',
                'gallery' => [
                    ['image_path' => 'images/regen-hero-farmland.jpg', 'caption' => 'Unit EnviroSense bertenaga surya di petak terasering.'],
                ],
            ],
            [
                'slug' => 'integrated-drip-irrigation-watersense-pilot',
                'title' => 'Integrasi REGEN WaterSense & SoilSense untuk Otomasi Irigasi Tetes',
                'category' => PrototypeCategory::IntegratedSystem,
                'description' => 'Uji sistem terintegrasi di mana pembacaan kelembapan zona akar SoilSense memicu rekomendasi dan pencatatan debit aliran air melalui modul WaterSense.',
                'technical_notes' => 'Penghematan konsumsi air irigasi tercatat sebesar 24.5% pada petak uji dibandingkan jadwal penyiraman manual harian tetap.',
                'testing_date' => '2026-10-19',
                'location' => 'Garut, Jawa Barat',
                'thumbnail_path' => 'images/regen-farmer-tablet.jpg',
                'video_url' => null,
                'is_featured' => false,
                'publication_status' => 'published',
                'gallery' => [
                    ['image_path' => 'images/regen-farmer-tablet.jpg', 'caption' => 'Evaluasi grafik debit irigasi bersama petani mitra.'],
                    ['image_path' => 'images/regen-soilsense-probe.jpg', 'caption' => 'Sensor kelembapan tanah pasca siklus irigasi tetes.'],
                ],
            ],
        ];

        foreach ($prototypes as $item) {
            $gallery = $item['gallery'];
            unset($item['gallery']);

            $prototype = Prototype::updateOrCreate(['slug' => $item['slug']], $item);
            $prototype->images()->delete();
            foreach ($gallery as $index => $img) {
                PrototypeImage::create([
                    'prototype_id' => $prototype->id,
                    'image_path' => $img['image_path'],
                    'caption' => $img['caption'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Seed sample private-disk files for Supporting Documents
        Storage::disk('local')->put(
            'documents/haki-regen-platform-2026.pdf',
            "%PDF-1.4\n% REGEN Official Supporting Document Sample - Pencatatan Hak Cipta Program Komputer REGEN Platform\n1 0 obj << /Type /Catalog >> endobj\n%%EOF"
        );
        Storage::disk('local')->put(
            'documents/spec-regen-soilsense-v14.pdf',
            "%PDF-1.4\n% REGEN SoilSense v1.4 Technical Specification Sheet - AGRONEX Engineering\n1 0 obj << /Type /Catalog >> endobj\n%%EOF"
        );
        Storage::disk('local')->put(
            'documents/internal-nda-partnership-2026.pdf',
            "%PDF-1.4\n% CONFIDENTIAL INTERNAL PARTNERSHIP DOCUMENT - PRIVATE ACCESS ONLY\n1 0 obj << /Type /Catalog >> endobj\n%%EOF"
        );

        $documents = [
            [
                'slug' => 'hak-cipta-perangkat-lunak-regen-platform',
                'title' => 'Surat Pencatatan Ciptaan Perangkat Lunak REGEN Farm & Enterprise Platform',
                'category' => DocumentCategory::Haki,
                'document_type' => 'Hak Cipta Program Komputer',
                'description' => 'Dokumen pencatatan resmi Hak Kekayaan Intelektual (HAKI) arsitektur perangkat lunak manajemen pertanian terintegrasi REGEN karya AGRONEX.',
                'document_number' => 'EC00202688412',
                'issuing_institution' => 'Direktorat Jenderal Kekayaan Intelektual (DJKI) Kemenkum RI',
                'issue_date' => '2026-08-12',
                'expiration_date' => null,
                'registration_status' => DocumentStatus::Registered,
                'disk' => 'local',
                'file_path' => 'documents/haki-regen-platform-2026.pdf',
                'original_filename' => 'HAKI-REGEN-Platform-EC00202688412.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 148,
                'visibility' => 'public',
                'allow_download' => true,
            ],
            [
                'slug' => 'spesifikasi-teknis-regen-soilsense-node-v14',
                'title' => 'Lembar Spesifikasi Teknis & Arsitektur Telemetri REGEN SoilSense v1.4',
                'category' => DocumentCategory::TechnicalSpecifications,
                'document_type' => 'Technical Datasheet',
                'description' => 'Dokumen spesifikasi elektrikal, rentang akurasi sensor RS485, protokol komunikasi LoRaWAN, dan standar proteksi enclosure IP66/IP68.',
                'document_number' => 'AGX-RGN-SPEC-2026-01',
                'issuing_institution' => 'Divisi Riset & Rekayasa Produk AGRONEX',
                'issue_date' => '2026-09-01',
                'expiration_date' => null,
                'registration_status' => DocumentStatus::Issued,
                'disk' => 'local',
                'file_path' => 'documents/spec-regen-soilsense-v14.pdf',
                'original_filename' => 'Datasheet-REGEN-SoilSense-v1.4.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 136,
                'visibility' => 'public',
                'allow_download' => true,
            ],
            [
                'slug' => 'desain-industri-enclosure-stasiun-lapangan-regen',
                'title' => 'Permohonan Desain Industri Enclosure Modular Stasiun Sensor Lapangan REGEN',
                'category' => DocumentCategory::IndustrialDesign,
                'document_type' => 'Desain Industri',
                'description' => 'Berkas permohonan perlindungan desain industri rumah perangkat (enclosure) tahan cuaca untuk node sensor modular REGEN.',
                'document_number' => 'A00202603915',
                'issuing_institution' => 'DJKI Kementerian Hukum RI',
                'issue_date' => '2026-09-25',
                'expiration_date' => null,
                'registration_status' => DocumentStatus::InProcess,
                'disk' => 'local',
                'file_path' => null,
                'original_filename' => null,
                'mime_type' => null,
                'file_size' => null,
                'visibility' => 'public',
                'allow_download' => false,
            ],
            [
                'slug' => 'dokumen-kemitraan-internal-rahasia-offtaker',
                'title' => 'Perjanjian Kerahasiaan Kemitraan Strategis Offtaker Enterprise (Internal)',
                'category' => DocumentCategory::PartnershipDocuments,
                'document_type' => 'Non-Disclosure Agreement',
                'description' => 'Dokumen legal internal khusus administrator; tidak boleh terekspos ke publik.',
                'document_number' => 'AGX-LGL-NDA-2026-09',
                'issuing_institution' => 'Legal & Compliance AGRONEX',
                'issue_date' => '2026-10-01',
                'expiration_date' => '2029-10-01',
                'registration_status' => DocumentStatus::Issued,
                'disk' => 'local',
                'file_path' => 'documents/internal-nda-partnership-2026.pdf',
                'original_filename' => 'Internal-NDA-2026.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 128,
                'visibility' => 'private',
                'allow_download' => false,
            ],
        ];

        foreach ($documents as $doc) {
            SupportingDocument::updateOrCreate(['slug' => $doc['slug']], $doc);
        }
    }
}
