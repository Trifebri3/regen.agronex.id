# REGEN — Integrated Smart Agriculture & Farm Management Platform

**Grow Smarter. Manage Better. Regenerate the Future.**

**REGEN** (`https://regen.agronex.id`) adalah platform digital teknologi pertanian terpadu dan website produk unggulan dari **AGRONEX** (`https://agronex.id`). Sistem berdiri sebagai ekosistem produk mandiri yang mengintegrasikan website pemasaran produk modular/terintegrasi, katalog solusi IoT & spasial, CMS dokumentasi prototipe, repositori dokumen pendukung (HAKI, Paten, Sertifikasi) terproteksi, aplikasi manajemen usaha tani (`/app`), kalkulator Business Intelligence HPP & BEP, dashboard administrator terpusat (`/admin`), serta konsol enterprise multi-tenant (`/enterprise/dashboard`).

---

## 1. Arsitektur Ekosistem Produk REGEN

1. **Product Website & Katalog Solusi Modular/Terintegrasi**
   - Rute publik: `/`, `/solutions`, `/solutions/soilsense`, `/solutions/watersense`, `/solutions/envirosense`, `/solutions/terra`, `/platform`, `/enterprise`, `/about`, `/contact`, `/login`.
   - 8 lini produk: **SoilSense**, **WaterSense**, **EnviroSense**, **Terra**, **Intelligence**, **Farm Management**, **Business**, dan **Enterprise**.
   - Kebijakan transparansi fitur: penandaan tegas antara fitur **Operational**, **Demo / Data Simulasi** (`is_demo = true`), dan **Roadmap Feature**.
2. **Prototype Documentation CMS (`/prototype`)**
   - Galeri responsif dengan filter 8 kategori, entri unggulan, *image lightbox*, pemutar video (berkas unggahan atau URL resmi), lampiran PDF teknis, dan halaman detail kronologis.
3. **Supporting Document CMS (`/pendukung`)**
   - Repositori dokumen HAKI, Paten, Desain Industri, Publikasi Riset, Spesifikasi Teknis, Laporan Uji, dan Sertifikasi.
   - **Keamanan Penyimpanan Privat**: seluruh berkas disimpan di `storage/app/private/documents` (`local` disk, tidak pernah diekspos melalui URL `public/storage`). Pengunduhan selalu divalidasi di sisi server berdasarkan status `visibility = public` dan `allow_download = true` (atau hak akses peran administrator).
4. **Centralized Administrator Dashboard (`/admin`)**
   - 13 menu terpusat (*Overview, Website Content, Product Management, Prototype Management, Document Management, Media Library, User Management, Farm Management, Device Management, Enterprise Management, Contact Requests, Reports, Settings & Audit*) dengan penegakan RBAC 7 peran di sisi server (`EnsurePortalAccess`).
5. **Farm Management Application & Learning Center (`/app`)**
   - Modul Dashboard KPI, Manajemen Lahan & Petak (*Plots*), Siklus Tanaman, Log Aktivitas Harian, Manajemen Input Saprotan, Pencatatan Panen & Susut Pascapanen, Penjualan & Invoice, Buku Besar Keuangan, Perangkat IoT & Rekomendasi Agronomi Berbasis Aturan (*Rule-Based Advisory*), serta Pusat Edukasi Petani 12 topik dalam 3 tingkat literasi digital (*Pemula, Menengah, Lanjutan*).
6. **Business Intelligence Financial Calculator (`/app/intelligence`)**
   - Perhitungan otomatis & aman terhadap nilai nol/input tidak valid: Total Biaya Produksi (Tetap + Variabel), Pendapatan, Estimasi Laba/Rugi (disertai *disclaimer* non-jaminan keuntungan), HPP per kilogram dengan basis pilihan (*Saleable Quantity* vs *Harvested Quantity*), Titik Impas (*Break-Even Point* kuantitas & rupiah), grafik komparasi visual, dan ekspor laporan CSV.
7. **Multi-Tenant Enterprise Suite & REST API (`/enterprise/dashboard` & `/api/v1/enterprise/summary`)**
   - Hierarki isolasi data ketat: `Platform -> Organization -> Farm -> Land / Plot -> Device / Crop Cycle / Activities / Financial Records`.
   - Satu organisasi tidak dapat membaca maupun memodifikasi data organisasi lain (`TenantContext`).

---

## 2. Persyaratan Sistem

- **PHP** `^8.4` (ekstensi: `pdo_sqlite` atau `pdo_mysql`/`pdo_pgsql`, `mbstring`, `openssl`, `fileinfo`, `gd`)
- **Composer** `^2.7`
- **Node.js** `^20.19` atau `^22.x` & **NPM**
- **Database**: SQLite (default pengembangan lokal) atau MySQL 8.0+ / PostgreSQL 15+ untuk produksi.

---

## 3. Panduan Instalasi & Deployment

### A. Instalasi Lokal (Development)

```bash
# 1. Instal dependensi PHP & JavaScript
composer install
npm install

# 2. Siapkan konfigurasi environment & kunci enkripsi
cp .env.example .env
php artisan key:generate

# 3. Buat berkas database SQLite (jika menggunakan SQLite default)
touch database/database.sqlite

# 4. Jalankan migrasi skema & seeder ekosistem REGEN
php artisan migrate --seed

# 5. Buat symbolic link untuk media publik (foto lahan & galeri prototipe)
php artisan storage:link

# 6. Bangun aset frontend (Tailwind CSS v4 + Instrument Sans + Vite)
npm run build

# 7. Jalankan server aplikasi
php artisan serve
```

### B. Deployment Produksi (`https://regen.agronex.id`)

1. **Konfigurasi `.env` Produksi**:
   ```dotenv
   APP_NAME="REGEN by AGRONEX"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://regen.agronex.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=regen_production
   DB_USERNAME=regen_app
   DB_PASSWORD=strong_secret_password

   SESSION_SECURE_COOKIE=true
   SESSION_HTTP_ONLY=true
   SESSION_SAME_SITE=lax
   FILESYSTEM_DISK=local
   ```
2. **Build & Optimasi Produksi**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **Konfigurasi Web Server & Hak Akses Direktori**:
   - Arahkan *Document Root* Nginx/Apache (atau gunakan [Laravel Cloud](https://cloud.laravel.com/)) ke direktori `public/`.
   - Pastikan `storage/` dan `bootstrap/cache/` dapat ditulis oleh proses PHP-FPM, dan direktori `storage/app/private/` **tidak pernah** dapat diakses langsung melalui HTTP publik.
   - Aktifkan TLS/HTTPS penuh pada `regen.agronex.id`.

---

## 4. Akun Demo Seeded (7 Peran RBAC & 2 Organisasi Multi-Tenant)

Seluruh akun demo yang dibuat oleh `DatabaseSeeder` menggunakan kata sandi: `password`

| Peran (Role) | Email Login | Organisasi (Tenant) | Portal Utama |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@regen.agronex.id` | Global Platform | `/admin`, `/enterprise/dashboard`, `/app` |
| **Content Administrator** | `content@regen.agronex.id` | Global CMS | `/admin` (Content, Prototype, Document, Media) |
| **Product Administrator** | `product@regen.agronex.id` | Global Catalog | `/admin` (Products, Prototypes, Contacts) |
| **Technical Administrator** | `tech@regen.agronex.id` | Global IoT & Tech | `/admin` (Devices, Prototypes, Farms), `/app` |
| **Organization Admin (Tenant 1)** | `orgadmin@agronex-nusantara.id` | PT Agronex Nusantara Agro (`ORG-AGX-01`) | `/enterprise/dashboard`, `/app` |
| **Farm Manager (Tenant 1)** | `manager@agronex-nusantara.id` | PT Agronex Nusantara Agro (`ORG-AGX-01`) | `/app` (Akses penuh struktur & operasional kebun) |
| **Farmer / User (Tenant 1)** | `farmer@agronex-nusantara.id` | PT Agronex Nusantara Agro (`ORG-AGX-01`) | `/app` (Operasional harian, BI, & Learning Center) |
| **Organization Admin (Tenant 2)** | `orgadmin@priangan-tani.id` | Koperasi Tani Mandiri Priangan (`ORG-KTP-02`) | `/enterprise/dashboard`, `/app` (Terisolasi penuh) |

---

## 5. Dokumentasi Pengujian (Testing Suite)

Sistem dilengkapi dengan rangkaian *Unit Test* dan *Feature Test* berbasis PHPUnit (`:memory:` SQLite dengan `RefreshDatabase`) yang memverifikasi seluruh fase arsitektur:

```bash
# Menjalankan seluruh rangkaian pengujian secara ringkas
php artisan test --compact
```

### Cakupan Berkas Pengujian

| Berkas Uji | Cakupan Verifikasi |
| :--- | :--- |
| `tests/Unit/FinancialCalculatorServiceTest.php` | Akurasi rumus Biaya Tetap/Variabel, Pendapatan, Laba/Rugi, HPP berbasis *Saleable* vs *Harvested*, BEP kuantitas/rupiah, serta ketahanan terhadap pembagian nol & input negatif/kosong. |
| `tests/Feature/Phase1PublicWebsiteTest.php` | Seluruh 12 rute publik pemasaran & katalog produk, pemisahan tegas identitas produk REGEN dari profil korporat AGRONEX, validasi formulir permintaan demo (`/contact`), serta alur autentikasi & pengalihan berbasis peran (`/login`). |
| `tests/Feature/Phase2CmsAndRbacTest.php` | CRUD Galeri Prototipe (unggah gambar, PDF, lightbox), CRUD Dokumen Pendukung dengan proteksi penyimpanan privat (`403`/`404` untuk dokumen privat atau tidak diizinkan unduh), serta matriks hak akses 7 peran pada 13 menu `/admin`. |
| `tests/Feature/Phase3And4FarmAndDeviceTest.php` | Alur lengkap operasional kebun (Lahan, Petak, Siklus Tanam, Aktivitas, Input, Panen, Penjualan, Buku Besar Keuangan), Kalkulator BI & ekspor CSV, Pusat Edukasi Petani, registrasi perangkat IoT, ingesti telemetri sensor, penandaan *Demo Simulation*, dan pembangkitan rekomendasi agronomi berbasis aturan. |
| `tests/Feature/Phase5EnterpriseMultiTenancyTest.php` | Konsol multi-kebun `/enterprise/dashboard`, penambahan kebun dalam ruang lingkup organisasi, ekspor CSV enterprise, endpoint REST JSON `/api/v1/enterprise/summary`, pemblokiran `403` terhadap manipulasi `?organization_id=` maupun mutasi lintas organisasi, serta batas hak akses peran `Farmer` vs `OrganizationAdmin` vs `SuperAdmin`. |

### Pemeriksaan Gaya Kode (Laravel Pint)

```bash
vendor/bin/pint --format agent
```
