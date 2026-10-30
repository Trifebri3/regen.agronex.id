<?php

namespace Database\Seeders;

use App\Models\LearningArticle;
use Illuminate\Database\Seeder;

class LearningCenterSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'panduan-persiapan-lahan-sehat',
                'title' => '1. Persiapan Lahan & Kesehatan Tanah Dasar',
                'topic' => 'land_preparation',
                'difficulty_level' => 'pemula',
                'reading_time_minutes' => 4,
                'summary' => 'Langkah praktis mengolah tanah, mengukur pH dasar, dan memberikan pupuk dasar organik sebelum tanam agar akar tumbuh kuat.',
                'key_takeaways' => [
                    'Ukur pH tanah sebelum menebar kapur dolomit agar biaya tidak terbuang.',
                    'Pastikan saluran drainase bedengan lancar untuk mencegah busuk akar.',
                    'Diamkan lahan 10–14 hari setelah pemberian kompos matang sebelum pindah tanam.',
                ],
                'step_by_step' => [
                    'Bersihkan sisa gulma dan residu tanaman sakit dari musim sebelumnya.',
                    'Gemburkan tanah sedalam 25–30 cm dan bentuk bedengan sesuai kontur.',
                    'Cek pH tanah: bila di bawah 5.5, tambahkan kapur pertanian secara merata.',
                    'Campurkan bahan organik/kompos matang sebagai pupuk dasar dan tutup mulsa bila diperlukan.',
                ],
                'practical_example' => 'Pada lahan 1.000 m² dengan pH 5.1, pemberian dolomit terukur 150–200 kg dua minggu sebelum tanam membantu menaikkan pH mendekati 6.2 sehingga pupuk NPK terserap optimal.',
                'sort_order' => 1,
            ],
            [
                'slug' => 'perencanaan-tanam-dan-kalender-budidaya',
                'title' => '2. Perencanaan Tanam & Penyusunan Kalender Budidaya',
                'topic' => 'crop_planning',
                'difficulty_level' => 'pemula',
                'reading_time_minutes' => 5,
                'summary' => 'Cara memilih varietas, menghitung kebutuhan benih, mengatur rotasi tanaman, dan menjadwalkan waktu tanam yang tepat.',
                'key_takeaways' => [
                    'Jangan menanam komoditas satu famili secara terus-menerus di petak yang sama.',
                    'Tambahkan cadangan benih 10% untuk penyulaman bibit yang tidak tumbuh.',
                    'Catat tanggal tanam dan estimasi panen di menu Crop Management REGEN.',
                ],
                'step_by_step' => [
                    'Tentukan komoditas berdasarkan kesesuaian ketinggian lahan dan permintaan pasar.',
                    'Hitung populasi tanaman dari jarak tanam (misal 50 × 60 cm) dikalikan luas efektif bedengan.',
                    'Susun jadwal semai, pindah tanam, pemupukan susulan, hingga target minggu panen.',
                ],
                'practical_example' => 'Rotasi Cabai → Buncis/Kacang-kacangan → Jagung Manis memutus siklus hama tular tanah sekaligus membantu mengembalikan nitrogen alami.',
                'sort_order' => 2,
            ],
            [
                'slug' => 'manajemen-pemupukan-berimbang',
                'title' => '3. Manajemen Pemupukan Tepat Dosis & Tepat Waktu',
                'topic' => 'fertilizer_management',
                'difficulty_level' => 'menengah',
                'reading_time_minutes' => 6,
                'summary' => 'Prinsip 5 Tepat pemupukan (Jenis, Dosis, Waktu, Cara, Sasaran) sesuai fase vegetatif dan generatif tanaman.',
                'key_takeaways' => [
                    'Fase awal butuh Nitrogen & Fosfor untuk daun dan akar; fase buah butuh Kalium lebih tinggi.',
                    'Hindari pemupukan saat tanah terlalu kering atau saat hujan deras karena pupuk mudah hilang.',
                    'Catat setiap pembelian dan pemakaian pupuk di menu Input Management.',
                ],
                'step_by_step' => [
                    'Pisahkan jadwal pupuk dasar (sebelum tanam) dan pupuk susulan (kocor/tugal).',
                    'Sesuaikan konsentrasi larutan kocor agar EC tidak melukai akar muda.',
                    'Evaluasi warna daun dan pertumbuhan ruas batang sebelum menambah dosis.',
                ],
                'practical_example' => 'Membagi pupuk susulan dalam porsi kecil setiap 10–14 hari jauh lebih efisien diserap akar dibandingkan memberikan dosis besar sekaligus.',
                'sort_order' => 3,
            ],
            [
                'slug' => 'manajemen-irigasi-dan-kelembapan-tanah',
                'title' => '4. Manajemen Irigasi & Penghematan Air Pertanian',
                'topic' => 'irrigation_management',
                'difficulty_level' => 'pemula',
                'reading_time_minutes' => 5,
                'summary' => 'Mengatur jadwal penyiraman berdasarkan kelembapan zona akar, bukan sekadar rutinitas jam.',
                'key_takeaways' => [
                    'Tanah terlalu basah sama berbahayanya dengan tanah kering karena memicu layu jamur/bakteri.',
                    'Waktu siram terbaik adalah pagi hari sebelum matahari terik.',
                    'Catat durasi atau volume air irigasi di Activity Logs.',
                ],
                'step_by_step' => [
                    'Periksa kelembapan tanah pada kedalaman 10–15 cm (genggam tanah atau baca data SoilSense).',
                    'Bila kelembapan berada di bawah 40%, jalankan irigasi hingga zona akar lembap merata.',
                    'Kurangi frekuensi siram saat curah hujan mencukupi dan pastikan parit buangan terbuka.',
                ],
                'practical_example' => 'Irigasi tetes 30 menit di pagi hari mampu menghemat hingga 30% air dibandingkan penggenangan selang terbuka.',
                'sort_order' => 4,
            ],
            [
                'slug' => 'pengendalian-hama-dan-penyakit-terpadu',
                'title' => '5. Pengendalian Hama & Penyakit Terpadu (PHT)',
                'topic' => 'pest_disease',
                'difficulty_level' => 'menengah',
                'reading_time_minutes' => 6,
                'summary' => 'Mengutamakan pencegahan, sanitasi kebun, perangkap likat, dan rotasi bahan aktif agar hama tidak kebal.',
                'key_takeaways' => [
                    'Amati kebun minimal 2–3 kali seminggu sebelum serangan menyebar luas.',
                    'Jangan mencampur banyak pestisida sekaligus tanpa mengetahui kelompok bahan aktifnya.',
                    'Kelembapan udara di atas 88% adalah sinyal waspada penyakit jamur daun dan buah.',
                ],
                'step_by_step' => [
                    'Pasang perangkap kuning/biru untuk memantau populasi awal thrips, kutu kebul, dan lalat buah.',
                    'Buang dan musnahkan bagian tanaman yang terinfeksi berat jauh dari sumber air.',
                    'Gunakan agen hayati di awal musim, dan gunakan pestisida kimia secara bijak hanya saat melewati ambang ekonomi.',
                ],
                'practical_example' => 'Rotasi kelompok bahan aktif (IRAC/FRAC) setiap dua kali aplikasi mencegah resistensi hama dan menekan pembengkakan biaya pestisida.',
                'sort_order' => 5,
            ],
            [
                'slug' => 'perencanaan-panen-dan-penanganan-pascapanen',
                'title' => '6. Perencanaan Panen, Sortasi Grade & Pascapanen',
                'topic' => 'harvest_planning',
                'difficulty_level' => 'pemula',
                'reading_time_minutes' => 5,
                'summary' => 'Menekan susut bobot dan kerusakan hasil panen melalui pemetikan tepat waktu serta pemisahan grade kualitas.',
                'key_takeaways' => [
                    'Panen di pagi hari saat suhu buah masih sejuk untuk memperpanjang daya simpan.',
                    'Pisahkan Grade A, Grade B, dan hasil rusak sejak di rumah sortasi.',
                    'Catat bobot kotor, bobot layak jual, dan susut pascapanen di Harvest Management.',
                ],
                'step_by_step' => [
                    'Siapkan keranjang panen bersih berventilasi dan hindari penumpukan berlebih.',
                    'Simpan hasil petik di tempat teduh sebelum penimbangan.',
                    'Timbang dan kelompokkan berdasarkan standar ukuran pembeli untuk mendapatkan harga terbaik.',
                ],
                'practical_example' => 'Menurunkan susut pascapanen dari 12% menjadi 4% pada panen 10.000 kg setara menyelamatkan 800 kg hasil layak jual!',
                'sort_order' => 6,
            ],
            [
                'slug' => 'cara-menghitung-biaya-produksi-usaha-tani',
                'title' => '7. Mengenal Biaya Tetap & Biaya Variabel Produksi',
                'topic' => 'production_costs',
                'difficulty_level' => 'pemula',
                'reading_time_minutes' => 5,
                'summary' => 'Membedakan biaya tetap (sewa lahan, penyusutan alat) dan biaya variabel (benih, pupuk, upah harian) agar tidak ada biaya terlewat.',
                'key_takeaways' => [
                    'Biaya Tetap tetap keluar meskipun jumlah panen sedikit maupun banyak.',
                    'Biaya Variabel berubah mengikuti luas tanam dan intensitas pemeliharaan.',
                    'Tenaga kerja keluarga sendiri tetap wajib dihitung nilainya agar perhitungan usaha jujur.',
                ],
                'step_by_step' => [
                    'Catat biaya sewa lahan per musim dan pembagian nilai pakai alat semprot/mulsa/pipa.',
                    'Catat setiap pembelian benih, pupuk, pestisida, upah harian, listrik pompa, dan ongkos angkut.',
                    'Jumlahkan seluruhnya sebagai Total Biaya Produksi di modul Keuangan REGEN.',
                ],
                'practical_example' => 'Sewa lahan Rp 15 juta/tahun untuk 3 musim tanam berarti beban biaya tetap sewa adalah Rp 5 juta per musim tanam.',
                'sort_order' => 7,
            ],
            [
                'slug' => 'cara-menghitung-hpp-harga-pokok-produksi',
                'title' => '8. Cara Menghitung Harga Pokok Produksi (HPP) per Kilogram',
                'topic' => 'hpp',
                'difficulty_level' => 'menengah',
                'reading_time_minutes' => 6,
                'summary' => 'Mengetahui modal riil untuk menghasilkan 1 kg hasil panen agar petani tahu batas aman harga jual.',
                'key_takeaways' => [
                    'Rumus HPP = Total Biaya Produksi ÷ Total Kuantitas Hasil.',
                    'Gunakan basis Kuantitas Layak Jual (Saleable) untuk gambaran HPP yang paling realistis.',
                    'Bila harga pasar di atas HPP, usaha tani mencetak laba; bila di bawah HPP, usaha merugi.',
                ],
                'step_by_step' => [
                    'Kumpulkan Total Biaya Produksi satu siklus tanam penuh.',
                    'Ambil angka Total Kuantitas Layak Jual (setelah dikurangi susut/rusak).',
                    'Bagi Total Biaya dengan Kuantitas Layak Jual menggunakan Kalkulator BI REGEN.',
                ],
                'practical_example' => 'Total biaya produksi Rp 45.000.000 dengan panen layak jual 9.000 kg menghasilkan HPP = Rp 5.000/kg. Artinya harga jual minimal agar impas biaya per kg adalah di atas Rp 5.000/kg.',
                'sort_order' => 8,
            ],
            [
                'slug' => 'memahami-titik-impas-break-even-point-bep',
                'title' => '9. Menghitung Titik Impas / Break-Even Point (BEP)',
                'topic' => 'bep',
                'difficulty_level' => 'menengah',
                'reading_time_minutes' => 6,
                'summary' => 'Menghitung berapa kilogram minimal hasil yang harus terjual agar seluruh modal kembali penuh.',
                'key_takeaways' => [
                    'BEP Kuantitas = Biaya Tetap ÷ (Harga Jual per kg − Biaya Variabel per kg).',
                    'Semakin efisien biaya variabel per kg, semakin cepat titik impas tercapai.',
                    'Jika harga jual lebih rendah dari biaya variabel per kg, titik impas tidak akan tercapai.',
                ],
                'step_by_step' => [
                    'Pisahkan total Biaya Tetap dan hitung Biaya Variabel per kilogram.',
                    'Masukkan estimasi Harga Jual per kilogram di Kalkulator Business Intelligence.',
                    'Bandingkan angka BEP Kuantitas dengan target panen lahan Anda.',
                ],
                'practical_example' => 'Biaya Tetap Rp 10.000.000, Biaya Variabel Rp 4.000/kg, Harga Jual Rp 9.000/kg → Margin kontribusi Rp 5.000/kg → BEP = 2.000 kg. Penjualan di atas 2.000 kg mulai menghasilkan keuntungan bersih.',
                'sort_order' => 9,
            ],
            [
                'slug' => 'strategi-penetapan-harga-hasil-panen',
                'title' => '10. Strategi Penetapan Harga & Negosiasi berbasis Data HPP',
                'topic' => 'pricing_strategy',
                'difficulty_level' => 'lanjutan',
                'reading_time_minutes' => 6,
                'summary' => 'Menetapkan target harga jual per grade kualitas dengan berpegang pada HPP dan biaya distribusi.',
                'key_takeaways' => [
                    'Jangan melepas seluruh hasil panen dengan harga pukul rata (borongan) bila kualitas Grade A dominan.',
                    'Perhitungkan ongkos angkut dan kemasan sebelum menyepakati harga kirim.',
                    'Gunakan data HPP tertulis saat bernegosiasi kontrak dengan pembeli tetap (offtaker).',
                ],
                'step_by_step' => [
                    'Hitung HPP dasar ditambah biaya distribusi ke gudang pembeli.',
                    'Tetapkan margin keuntungan wajar untuk Grade A (misal HPP + 35–50%) dan harga pemulihan modal untuk Grade B.',
                    'Evaluasi realisasi rata-rata harga jual di modul Sales Management.',
                ],
                'practical_example' => 'Dengan HPP Rp 5.000/kg, menjual 70% Grade A di Rp 8.500/kg dan 30% Grade B di Rp 5.500/kg menghasilkan rata-rata tertimbang Rp 7.600/kg (margin sehat).',
                'sort_order' => 10,
            ],
            [
                'slug' => 'pemasaran-dan-akses-pasar-pertanian',
                'title' => '11. Diversifikasi Kanal Pemasaran & Kemitraan Pasar',
                'topic' => 'marketing_access',
                'difficulty_level' => 'lanjutan',
                'reading_time_minutes' => 5,
                'summary' => 'Mengelola portofolio pembeli antara pasar induk, koperasi, ritel modern, dan kontrak offtaker agar risiko fluktuasi harga terkendali.',
                'key_takeaways' => [
                    'Kontrak offtaker memberikan kepastian harga; pasar bebas memberi peluang saat harga naik.',
                    'Konsistensi jadwal panen dan kejujuran sortasi adalah modal utama kepercayaan pembeli B2B.',
                    'Pantau status pembayaran piutang pembeli di Sales Management.',
                ],
                'step_by_step' => [
                    'Petakan minimal 2–3 kanal pembeli sebelum tanaman memasuki fase pembungaan.',
                    'Bagikan estimasi volume dan jadwal panen dari REGEN kepada calon pembeli 2 minggu sebelumnya.',
                    'Catat riwayat ketepatan pembayaran setiap pembeli.',
                ],
                'practical_example' => 'Mengalokasikan 60% panen ke kontrak harga tetap koperasi/offtaker sudah mengamankan 100% modal produksi, sementara 40% sisanya dapat mengoptimalkan harga pasar harian.',
                'sort_order' => 11,
            ],
            [
                'slug' => 'manajemen-usaha-tani-berkelanjutan',
                'title' => '12. Tata Kelola Usaha Tani Modern & Berkelanjutan',
                'topic' => 'farm_business',
                'difficulty_level' => 'lanjutan',
                'reading_time_minutes' => 7,
                'summary' => 'Memisahkan keuangan rumah tangga dengan keuangan kebun, mengevaluasi perbandingan antar musim, dan membangun cadangan modal.',
                'key_takeaways' => [
                    'Pisahkan kas operasional kebun dari pengeluaran pribadi rumah tangga.',
                    'Sisihkan minimal 15–20% laba bersih untuk cadangan modal musim berikutnya dan pemeliharaan tanah.',
                    'Bandingkan biaya per kilogram antar siklus tanam untuk menemukan titik penghematan terbaik.',
                ],
                'step_by_step' => [
                    'Tutup buku setiap akhir siklus panen dan tinjau ringkasan Laba/Rugi bersih.',
                    'Evaluasi komponen biaya terbesar (apakah pupuk, pestisida, atau tenaga kerja).',
                    'Rencanakan perbaikan kesehatan tanah untuk menjaga produktivitas jangka panjang.',
                ],
                'practical_example' => 'Kebun yang rutin mencatat biaya dan hasil panen selama 3 musim berturut-turut memiliki rekam jejak finansial rapi untuk mengakses pembiayaan maupun kemitraan enterprise.',
                'sort_order' => 12,
            ],
        ];

        foreach ($articles as $article) {
            LearningArticle::updateOrCreate(
                ['slug' => $article['slug']],
                array_merge($article, ['is_published' => true])
            );
        }
    }
}
