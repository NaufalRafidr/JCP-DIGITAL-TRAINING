<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ArticleSeeder::class,
        ]);

        // Clear old records if re-seeding
        CourseSession::query()->delete();
        Course::query()->delete();
        Category::query()->delete();

        $categoriesData = [
            [
                'name' => 'Photography & Videography',
                'slug' => 'photo-video',
                'short_name' => 'Foto / Video',
                'description' => 'Kuasai teknik visual storytelling, produksi kamera, dan editing profesional untuk kebutuhan personal maupun korporat.',
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'short_name' => 'Digital',
                'description' => 'Strategi akuisisi pelanggan, paid ads, SEO, dan pengelolaan sosial media terukur untuk mendongkrak penjualan.',
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'short_name' => 'AI',
                'description' => 'Tingkatkan produktivitas kerja dan kreasi konten menggunakan tools AI generatif terdepan.',
            ],
            [
                'name' => 'Drone Technology',
                'slug' => 'drone-technology',
                'short_name' => 'Drone',
                'description' => 'Pelatihan penerbangan drone legal, keselamatan, aerial visual, hingga pemetaan dan survei fotogrametri.',
            ],
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'short_name' => 'IT',
                'description' => 'Pembangunan website modern, sistem informasi bisnis, dan otomasi alur kerja tanpa batas.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        $coursesData = [
            // Category: artificial-intelligence
            [
                'category_id' => $categories['artificial-intelligence']->id,
                'title' => 'AI untuk Produktivitas Kerja',
                'slug' => 'ai-produktivitas-kerja',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Hybrid (Online + Studio)',
                'is_featured' => true,
                'description' => 'Tingkatkan efisiensi kerja harian hingga 5x lipat dengan ChatGPT, Claude, Microsoft Copilot, dan tools AI summarizer.',
                'outcome' => 'Mampu menyusun prompt profesional untuk riset kilat, pembuatan draf dokumen kerja, presentasi otomatis, dan analisis data spreadsheet.',
                'syllabus' => [
                    'Fondasi Prompt Engineering: Framework RTF, CLEAR, dan Few-shot',
                    'Otomasi pembuatan laporan kerja, SOP, dan korespondensi resmi',
                    'Analisis data Excel/CSV dan visualisasi grafik dengan AI interpreter',
                    'Etika penggunaan AI, privasi data instansi, dan verifikasi halusinasi',
                ],
            ],
            [
                'category_id' => $categories['artificial-intelligence']->id,
                'title' => 'AI Content Creator',
                'slug' => 'ai-content-creator',
                'duration' => '2 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Online',
                'is_featured' => false,
                'description' => 'Riset topik tren, pembuatan skrip video, voice over AI, thumbnail generator, hingga auto-caption dalam satu alur kerja modern.',
                'outcome' => 'Memangkas waktu produksi konten harian dari berjam-jam menjadi hitungan menit dengan kualitas tetap terjaga.',
                'syllabus' => [
                    'Brainstorming ide viral dan hook formula dengan LLM',
                    'Pembuatan voice clone & AI voiceover natural',
                    'Generate aset gambar & thumbnail click-worthy',
                    'Workflow repurpose konten panjang menjadi 10 micro-content',
                ],
            ],
            [
                'category_id' => $categories['artificial-intelligence']->id,
                'title' => 'Canva & AI Graphic Design',
                'slug' => 'canva-ai-graphic-design',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Offline / Studio',
                'is_featured' => false,
                'description' => 'Desain materi promosi, presentasi pimpinan, dan poster profesional menggunakan fitur Canva Magic Studio dan generator AI terintegrasi.',
                'outcome' => 'Peserta non-desainer dapat menghasilkan karya grafis berstandar agensi dengan rapi dan cepat.',
                'syllabus' => [
                    'Prinsip hierarki visual, kontras warna, dan tipografi dasar',
                    'Memanfaatkan Magic Eraser, Magic Expand, dan Magic Switch',
                    'Penyusunan pitch deck presentasi interaktif dan infografis',
                    'Brand kit setup: konsistensi logo, font, dan palet warna instansi',
                ],
            ],
            [
                'category_id' => $categories['artificial-intelligence']->id,
                'title' => 'AI Video Generation',
                'slug' => 'ai-video-generation',
                'duration' => '2 hari',
                'level' => 'Intermediate',
                'format' => 'Hybrid',
                'is_featured' => false,
                'description' => 'Eksplorasi pembuatan video generasi baru dengan Sora, Runway, Kling AI, dan Pika untuk iklan serta visual kampanye kreatif.',
                'outcome' => 'Menghasilkan adegan sinematik AI sintetis dengan kontrol kamera, pencahayaan, dan konsistensi karakter.',
                'syllabus' => [
                    'Konsep text-to-video dan image-to-video prompting',
                    'Kamera motion control: pan, tilt, zoom, and seed continuity',
                    'AI lipsync dan avatar presenter virtual untuk tutorial',
                    'Kombinasi AI video dengan background scoring & audio mixing',
                ],
            ],

            // Category: drone-technology
            [
                'category_id' => $categories['drone-technology']->id,
                'title' => 'Basic Drone Training',
                'slug' => 'basic-drone-training',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Praktik Lapangan',
                'is_featured' => true,
                'description' => 'Pengenalan komponen drone, regulasi ruang udara KKOP Indonesia, prosedur pre-flight check, dan praktik manuver terbang aman.',
                'outcome' => 'Mendapatkan keahlian dasar pilot drone dengan pemahaman regulasi penerbangan resmi dan keselamatan kerja.',
                'syllabus' => [
                    'Regulasi ruang udara Casr 107 dan izin terbang DNP/Kemenhub',
                    'Anatomi drone, kalibrasi IMU, kompas, dan return-to-home (RTH)',
                    'Praktik manuver dasar: hover, 8-shape, box flight, dan obstacle avoidance',
                    'Emergency landing dan penanganan cuaca buruk di lapangan',
                ],
            ],
            [
                'category_id' => $categories['drone-technology']->id,
                'title' => 'Drone Mapping Training',
                'slug' => 'drone-mapping-training',
                'duration' => '3 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Praktik Lapangan',
                'is_featured' => true,
                'description' => 'Pelatihan pemetaan menggunakan drone, dari persiapan misi, pemasangan GCP, hingga pengolahan data ortofoto dan model 3D.',
                'outcome' => 'Mampu menghasilkan peta foto resolusi tinggi, Digital Elevation Model (DEM), dan analisis luasan area akurat.',
                'syllabus' => [
                    'Prinsip dasar fotogrametri dan penentuan Ground Control Point (GCP)',
                    'Perencanaan jalur terbang otomatis dengan software mission planner',
                    'Akuisisi data foto udara dengan overlap longitudinal & lateral yang presisi',
                    'Pemrosesan data di software fotogrametri (orthomosaic, DSM, kontur)',
                ],
            ],
            [
                'category_id' => $categories['drone-technology']->id,
                'title' => 'Drone Photography & Videography',
                'slug' => 'drone-photography-videography',
                'duration' => '2 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Praktik Lapangan',
                'is_featured' => false,
                'description' => 'Teknik pengambilan foto aerial lanskap, arsitektur, dan pergerakan kamera sinematik untuk liputan acara dan promosi pariwisata.',
                'outcome' => 'Mampu mengeksekusi aerial shot spektakuler dengan komposisi halus, stabil, dan dinamis.',
                'syllabus' => [
                    'Setting exposure kamera drone, ND filter, dan shutter rule 180°',
                    'Teknik pergerakan gimbal: reveal shot, orbit, bird eye, cable cam',
                    'Fotografi panorama 360° dan HDR aerial',
                    'Post-processing foto & video aerial (stabilisasi dan color grade)',
                ],
            ],

            // Category: information-technology
            [
                'category_id' => $categories['information-technology']->id,
                'title' => 'Website Development Training',
                'slug' => 'website-development-training',
                'duration' => '3 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Hybrid',
                'is_featured' => true,
                'description' => 'Membangun website profil instansi atau portal katalog modern yang responsif, cepat, aman, dan mudah diperbarui oleh admin.',
                'outcome' => 'Mampu merilis website siap pakai dengan arsitektur modern, SEO teroptimasi, dan form kontak fungsional.',
                'syllabus' => [
                    'Struktur halaman web modern & prinsip desain responsif',
                    'Pembuatan katalog interaktif dan integrasi form pendaftaran',
                    'Optimasi Core Web Vitals, metadata SEO, dan keamanan web dasar',
                    'Deploy ke server cloud, manajemen domain, dan pengoperasian dashboard CMS',
                ],
            ],
            [
                'category_id' => $categories['information-technology']->id,
                'title' => 'AI Automation (n8n)',
                'slug' => 'ai-automation-n8n',
                'duration' => '2 hari',
                'level' => 'Intermediate',
                'format' => 'Online / Hybrid',
                'is_featured' => false,
                'description' => 'Integrasikan WhatsApp, CRM, Google Sheets, dan LLM untuk mengotomatiskan alur kerja bisnis tanpa kode rumit dengan n8n self-hosted.',
                'outcome' => 'Memiliki sistem otomasi cerdas yang menangani lead pelanggan, notifikasi tim, dan pengolahan data secara mandiri 24/7.',
                'syllabus' => [
                    'Arsitektur workflow engine n8n, node trigger, dan webhook',
                    'Integrasi WhatsApp API untuk chatbot layanan pelanggan cerdas',
                    'Pipeline data sinkronisasi otomatis ke Google Sheets & database',
                    'Error handling, retry logic, dan pemantauan server produksi',
                ],
            ],

            // Category: photo-video
            [
                'category_id' => $categories['photo-video']->id,
                'title' => 'Smartphone Photography & Videography',
                'slug' => 'smartphone-photography-videography',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Offline / Studio',
                'is_featured' => true,
                'description' => 'Maksimalkan smartphone untuk menghasilkan foto produk dan video sinematik berkualitas tinggi tanpa alat mahal.',
                'outcome' => 'Mampu memproduksi foto katalog komersial dan video Reels/TikTok dengan visual tajam, pencahayaan tepat, dan komposisi menarik.',
                'syllabus' => [
                    'Pengenalan sensor, exposure, dan setting kamera smartphone',
                    'Teknik pencahayaan alami vs artificial lighting sederhana',
                    'Komposisi visual dan angle pengambilan gambar produk & human interest',
                    'Praktik studio & color grading langsung di ponsel',
                ],
            ],
            [
                'category_id' => $categories['photo-video']->id,
                'title' => 'CapCut Video Editing',
                'slug' => 'capcut-video-editing',
                'duration' => '2 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Hybrid',
                'is_featured' => false,
                'description' => 'Teknik editing cepat, transisi dinamis, penataan ritme audio, dan motion text viral menggunakan CapCut mobile & desktop.',
                'outcome' => 'Menghasilkan video storytelling vertikal maupun horizontal yang engaging dan siap tayang di multiplatform.',
                'syllabus' => [
                    'Prinsip video cutting & pacing ritme musik',
                    'Keyframe animation, masking, dan speed ramping',
                    'Sound design: Foley, SFX, dan balancing voice over',
                    'Ekspor optimal untuk algoritma TikTok, Instagram Reels, dan YouTube Shorts',
                ],
            ],
            [
                'category_id' => $categories['photo-video']->id,
                'title' => 'Corporate Photo & Video Production',
                'slug' => 'corporate-photo-video-production',
                'duration' => '3 hari',
                'level' => 'Intermediate',
                'format' => 'Onsite / Studio',
                'is_featured' => false,
                'description' => 'Pelatihan standar industri untuk tim humas & komunikasi instansi dalam mendokumentasikan kegiatan protokoler dan company profile.',
                'outcome' => 'Standarisasi SOP dokumentasi visual perusahaan yang elegan, kredibel, dan profesional.',
                'syllabus' => [
                    'Manajemen alat kamera mirrorless & audio wireless standar broadcast',
                    'SOP liputan acara resmi dan wawancara pimpinan instansi',
                    'Tata lampu 3-point lighting setup untuk interview',
                    'Workflow backup data, color grading log profile, dan review penayangan',
                ],
            ],

            // Category: digital-marketing
            [
                'category_id' => $categories['digital-marketing']->id,
                'title' => 'Digital Marketing untuk UMKM',
                'slug' => 'digital-marketing-umkm',
                'duration' => '2 hari',
                'level' => 'Basic',
                'format' => 'Hybrid',
                'is_featured' => true,
                'description' => 'Strategi praktis pemasaran digital berbiaya rendah dengan hasil terukur untuk pelaku UMKM dan bisnis lokal.',
                'outcome' => 'Memiliki channel promosi digital aktif, Google Business Profile terverifikasi, dan funnel penjualan via WhatsApp.',
                'syllabus' => [
                    'Menentukan Buyer Persona dan Positioning Produk',
                    'Optimasi Google My Business & Local SEO',
                    'Content strategy yang menjual di media sosial',
                    'Menutup penjualan cepat dengan WhatsApp Business Automation',
                ],
            ],
            [
                'category_id' => $categories['digital-marketing']->id,
                'title' => 'Meta Ads Masterclass',
                'slug' => 'meta-ads-masterclass',
                'duration' => '3 hari',
                'level' => 'Intermediate',
                'format' => 'Online / Offline',
                'is_featured' => false,
                'description' => 'Kuasai Facebook & Instagram Ads secara mendalam: riset audiens, A/B testing kreatif, tracking Pixel, dan optimasi ROAS.',
                'outcome' => 'Mampu menjalankan kampanye iklan berbayar dengan biaya akuisisi efektif dan menghasilkan konversi tinggi.',
                'syllabus' => [
                    'Struktur Campaign, Ad Set, dan Ads Manager',
                    'Teknik riset Custom Audience dan Lookalike Audience',
                    'Copywriting penawaran & hook kreatif iklan visual',
                    'Analisis metrik CTR, CPM, CPC, dan scaling budget aman',
                ],
            ],
            [
                'category_id' => $categories['digital-marketing']->id,
                'title' => 'Social Media Management',
                'slug' => 'social-media-management',
                'duration' => '2 hari',
                'level' => 'Basic–Intermediate',
                'format' => 'Hybrid',
                'is_featured' => false,
                'description' => 'Perencanaan editorial content calendar, analitik performa, community engagement, dan manajemen krisis komunikasi media sosial.',
                'outcome' => 'Mampu mengelola akun brand secara konsisten, membangun followers loyal, dan menjaga sentimen positif audiens.',
                'syllabus' => [
                    'Penyusunan content pillar & kalender publikasi bulanan',
                    'Strategi visual brand consistency & caption writing',
                    'Membaca analitik engagement rate & reach',
                    'Studi kasus mitigasi komentar negatif dan krisis humas online',
                ],
            ],
        ];

        $courses = [];
        foreach ($coursesData as $cData) {
            $courses[$cData['slug']] = Course::create($cData);
        }

        // ========================================================
        // 09 / JADWAL & COHORT (Tepat sesuai Slide 10 Blueprint)
        // 1. AI untuk Produktivitas -> Status: Buka
        // 2. Basic Drone Training   -> Status: Buka
        // 3. Website Development    -> Status: Segera
        // 4. Drone Mapping          -> Status: Penuh
        // + Tambahan batch pendukung
        // ========================================================

        $scheduledCohorts = [
            [
                'course_slug' => 'ai-produktivitas-kerja',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-14'),
                'end_date' => Carbon::parse('2026-11-15'),
                'location' => 'JCP Learning Studio & Online Hybrid',
                'quota' => 15,
                'price' => 1750000,
                'status' => 'Buka',
            ],
            [
                'course_slug' => 'basic-drone-training',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-21'),
                'end_date' => Carbon::parse('2026-11-22'),
                'location' => 'Lapangan Terbang Gading & Studio JCP',
                'quota' => 12,
                'price' => 2500000,
                'status' => 'Buka',
            ],
            [
                'course_slug' => 'website-development-training',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-28'),
                'end_date' => Carbon::parse('2026-11-30'),
                'location' => 'JCP Tech Hub Yogyakarta',
                'quota' => 15,
                'price' => 2250000,
                'status' => 'Segera',
            ],
            [
                'course_slug' => 'drone-mapping-training',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-08'),
                'end_date' => Carbon::parse('2026-11-10'),
                'location' => 'Area Survei Sleman & Lab Komputasi',
                'quota' => 10,
                'price' => 3500000,
                'status' => 'Penuh',
            ],
            [
                'course_slug' => 'smartphone-photography-videography',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-17'),
                'end_date' => Carbon::parse('2026-11-18'),
                'location' => 'Studio Foto Komersial JCP',
                'quota' => 15,
                'price' => 1500000,
                'status' => 'Buka',
            ],
            [
                'course_slug' => 'digital-marketing-umkm',
                'batch_name' => 'Batch 01 - November 2026',
                'start_date' => Carbon::parse('2026-11-24'),
                'end_date' => Carbon::parse('2026-11-25'),
                'location' => 'JCP Learning Studio & Zoom',
                'quota' => 20,
                'price' => 1250000,
                'status' => 'Buka',
            ],
        ];

        foreach ($scheduledCohorts as $cohort) {
            if (isset($courses[$cohort['course_slug']])) {
                CourseSession::create([
                    'course_id' => $courses[$cohort['course_slug']]->id,
                    'batch_name' => $cohort['batch_name'],
                    'start_date' => $cohort['start_date'],
                    'end_date' => $cohort['end_date'],
                    'location' => $cohort['location'],
                    'quota' => $cohort['quota'],
                    'price' => $cohort['price'],
                    'status' => $cohort['status'],
                ]);
            }
        }
    }
}
