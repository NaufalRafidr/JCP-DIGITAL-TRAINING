<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;
use LogicException;

class TrainingProgramSeeder extends Seeder
{
    /**
     * Add the remaining programs needed for ten programs in every training field.
     */
    public function run(): void
    {
        $this->seedPrograms($this->programsByCategory());
    }

    /**
     * @return array<string, list<array{title: string, slug: string, duration: string, level: string, format: string, description: string, outcome: string, syllabus: list<string>}>>
     */
    public function programsByCategory(): array
    {
        return [
            'artificial-intelligence' => [
                $this->course(
                    title: 'AI untuk Analisis Data & Dashboard',
                    slug: 'ai-analisis-data-dashboard',
                    duration: '3 hari', level: 'Intermediate', format: 'Hybrid',
                    description: 'Mengolah data spreadsheet, menemukan pola, dan menyusun dashboard interaktif dengan bantuan AI untuk pengambilan keputusan yang lebih cepat.',
                    outcome: 'Mampu membersihkan data, membuat analisis terarah, dan mempresentasikan insight bisnis dalam dashboard yang mudah dipahami.',
                    syllabus: ['Persiapan dan pembersihan data dengan bantuan AI', 'Analisis deskriptif, tren, dan anomali data', 'Pembuatan visualisasi dan dashboard interaktif', 'Penyusunan narasi insight dan rekomendasi bisnis'],
                ),
                $this->course(
                    title: 'Generative AI untuk Administrasi Perkantoran',
                    slug: 'generative-ai-administrasi-perkantoran',
                    duration: '2 hari', level: 'Basic', format: 'Online / Offline',
                    description: 'Pemanfaatan AI generatif untuk mempercepat penyusunan surat, notulen, laporan, agenda, dan dokumen administrasi yang konsisten.',
                    outcome: 'Mampu membangun alur kerja administrasi berbantuan AI yang cepat, rapi, dan tetap mengikuti standar organisasi.',
                    syllabus: ['Prompt untuk surat dinas, memo, dan korespondensi', 'Peringkasan rapat dan penyusunan notulen', 'Pembuatan template laporan dan agenda kerja', 'Pemeriksaan kualitas, privasi, dan akurasi dokumen'],
                ),
                $this->course(
                    title: 'AI Customer Service & Chatbot',
                    slug: 'ai-customer-service-chatbot',
                    duration: '3 hari', level: 'Intermediate', format: 'Hybrid',
                    description: 'Merancang asisten layanan pelanggan berbasis AI yang mampu menjawab FAQ, menyaring kebutuhan pengguna, dan meneruskan kasus kompleks ke petugas.',
                    outcome: 'Memiliki prototipe chatbot layanan pelanggan dengan basis pengetahuan, alur eskalasi, dan gaya komunikasi sesuai brand.',
                    syllabus: ['Pemetaan pertanyaan pelanggan dan alur percakapan', 'Penyusunan knowledge base dan system prompt', 'Integrasi chatbot dengan kanal layanan digital', 'Evaluasi jawaban, eskalasi, dan pemantauan kualitas'],
                ),
                $this->course(
                    title: 'AI untuk Riset & Penyusunan Proposal',
                    slug: 'ai-riset-penyusunan-proposal',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Online',
                    description: 'Teknik riset terstruktur, sintesis sumber, dan penyusunan proposal program atau bisnis menggunakan AI tanpa mengabaikan validasi informasi.',
                    outcome: 'Mampu menghasilkan kerangka riset dan proposal yang argumentatif, berbasis sumber, serta siap ditinjau oleh pemangku kepentingan.',
                    syllabus: ['Perumusan masalah dan pertanyaan riset', 'Pencarian, perbandingan, dan verifikasi sumber', 'Penyusunan latar belakang, tujuan, dan metodologi', 'Review sitasi, konsistensi, dan risiko halusinasi'],
                ),
                $this->course(
                    title: 'AI Image Generation untuk Brand',
                    slug: 'ai-image-generation-brand',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Offline / Studio',
                    description: 'Membuat aset visual kampanye yang konsisten menggunakan generator gambar AI, reference image, inpainting, dan teknik prompt visual.',
                    outcome: 'Mampu memproduksi rangkaian visual brand yang konsisten untuk iklan, media sosial, dan materi presentasi.',
                    syllabus: ['Anatomi prompt visual, gaya, komposisi, dan cahaya', 'Reference image dan konsistensi karakter atau produk', 'Inpainting, outpainting, dan perbaikan detail', 'Kurasi aset, hak penggunaan, dan quality control'],
                ),
                $this->course(
                    title: 'AI Governance & Responsible AI',
                    slug: 'ai-governance-responsible-ai',
                    duration: '2 hari', level: 'Intermediate', format: 'Onsite / Hybrid',
                    description: 'Menyusun tata kelola penggunaan AI yang aman, etis, transparan, dan sesuai kebutuhan organisasi maupun instansi.',
                    outcome: 'Mampu mengidentifikasi risiko AI dan menyusun pedoman operasional, proses review, serta kontrol penggunaan data.',
                    syllabus: ['Pemetaan use case dan tingkat risiko AI', 'Privasi data, keamanan, bias, dan transparansi', 'Human oversight dan proses persetujuan hasil AI', 'Penyusunan kebijakan serta checklist audit internal'],
                ),
            ],
            'drone-technology' => [
                $this->course(
                    title: 'Advanced Drone Piloting',
                    slug: 'advanced-drone-piloting',
                    duration: '3 hari', level: 'Advanced', format: 'Praktik Lapangan',
                    description: 'Peningkatan keterampilan pilot untuk operasi presisi, manuver kompleks, mitigasi risiko, dan pengambilan keputusan di kondisi lapangan yang menantang.',
                    outcome: 'Mampu menjalankan misi terbang kompleks secara presisi dengan prosedur keselamatan dan manajemen kru yang disiplin.',
                    syllabus: ['Flight planning untuk area dan kondisi kompleks', 'Manuver presisi dan operasi dengan obstacle', 'Crew resource management dan komunikasi lapangan', 'Simulasi kegagalan sistem serta emergency response'],
                ),
                $this->course(
                    title: 'Drone Inspection for Infrastructure',
                    slug: 'drone-inspection-infrastructure',
                    duration: '3 hari', level: 'Intermediate', format: 'Praktik Lapangan',
                    description: 'Inspeksi visual gedung, jembatan, menara, dan aset industri menggunakan drone dengan metode akuisisi data yang aman dan terdokumentasi.',
                    outcome: 'Mampu merencanakan inspeksi, mengambil bukti visual, menandai temuan, dan menyusun laporan kondisi aset.',
                    syllabus: ['Perencanaan misi dan identifikasi bahaya lokasi', 'Teknik pengambilan gambar detail aset', 'Klasifikasi temuan dan pencatatan koordinat', 'Penyusunan laporan inspeksi berbasis bukti visual'],
                ),
                $this->course(
                    title: 'Drone untuk Pertanian & Perkebunan',
                    slug: 'drone-pertanian-perkebunan',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Praktik Lapangan',
                    description: 'Pemetaan lahan, pemantauan kesehatan tanaman, dan perencanaan operasi pertanian presisi dengan data drone multispektral maupun RGB.',
                    outcome: 'Mampu menghasilkan peta kondisi lahan dan rekomendasi tindak lanjut untuk pemantauan tanaman yang lebih efisien.',
                    syllabus: ['Perencanaan survei lahan dan kebutuhan resolusi data', 'Akuisisi citra RGB dan multispektral', 'Interpretasi indeks vegetasi dan area bermasalah', 'Pelaporan hasil untuk tindakan agronomi'],
                ),
                $this->course(
                    title: 'Thermal Drone Inspection',
                    slug: 'thermal-drone-inspection',
                    duration: '3 hari', level: 'Intermediate', format: 'Praktik Lapangan',
                    description: 'Pemanfaatan kamera termal pada drone untuk pemeriksaan panel surya, kelistrikan, bangunan, dan anomali panas pada aset industri.',
                    outcome: 'Mampu melakukan pengambilan citra termal yang benar, membaca anomali, dan menyusun laporan inspeksi awal.',
                    syllabus: ['Dasar termografi, emisivitas, dan refleksi panas', 'Pengaturan kamera dan prosedur akuisisi termal', 'Identifikasi hotspot serta pola anomali', 'Analisis data dan dokumentasi hasil inspeksi'],
                ),
                $this->course(
                    title: 'Drone Search & Rescue',
                    slug: 'drone-search-rescue',
                    duration: '3 hari', level: 'Intermediate', format: 'Simulasi Lapangan',
                    description: 'Penggunaan drone untuk mendukung pencarian dan penyelamatan melalui perencanaan sektor, koordinasi tim, serta pemanfaatan kamera visual dan termal.',
                    outcome: 'Mampu menjadi bagian dari operasi pencarian berbasis drone dengan alur komando, pencatatan, dan keselamatan yang jelas.',
                    syllabus: ['Pembagian sektor dan pola pencarian udara', 'Koordinasi pilot, observer, dan tim darat', 'Penggunaan kamera termal untuk deteksi target', 'Simulasi penemuan, pelaporan, dan serah terima lokasi'],
                ),
                $this->course(
                    title: 'Drone Surveying dengan RTK/PPK',
                    slug: 'drone-surveying-rtk-ppk',
                    duration: '4 hari', level: 'Advanced', format: 'Praktik Lapangan',
                    description: 'Survei udara berpresisi tinggi menggunakan workflow RTK dan PPK, kontrol kualitas koordinat, serta validasi hasil pemetaan.',
                    outcome: 'Mampu menghasilkan data survei drone berakurasi tinggi dan mendokumentasikan standar kualitas hasil pengukuran.',
                    syllabus: ['Konsep GNSS, RTK, PPK, base, dan rover', 'Perencanaan jalur terbang dan titik kontrol', 'Koreksi data serta geotagging presisi', 'Uji akurasi, checkpoint, dan laporan kualitas'],
                ),
                $this->course(
                    title: 'Drone Data Processing & GIS',
                    slug: 'drone-data-processing-gis',
                    duration: '3 hari', level: 'Intermediate', format: 'Hybrid',
                    description: 'Mengolah hasil pemotretan drone menjadi orthomosaic, model elevasi, kontur, dan layer GIS yang siap dianalisis maupun dipresentasikan.',
                    outcome: 'Mampu mengubah data mentah drone menjadi produk geospasial yang terstruktur, akurat, dan siap digunakan.',
                    syllabus: ['Manajemen dataset dan quality check foto udara', 'Pemrosesan orthomosaic, DSM, DTM, dan kontur', 'Analisis spasial dan digitasi di perangkat GIS', 'Layout peta, ekspor data, dan penyajian hasil'],
                ),
            ],
            'information-technology' => [
                $this->course(
                    title: 'Web Programming Fundamentals',
                    slug: 'web-programming-fundamentals',
                    duration: '3 hari', level: 'Basic', format: 'Offline / Studio',
                    description: 'Fondasi pengembangan web melalui HTML, CSS, JavaScript, prinsip responsif, dan penggunaan alat kerja developer modern.',
                    outcome: 'Mampu membangun dan mempublikasikan website responsif sederhana dengan struktur kode yang rapi.',
                    syllabus: ['Struktur HTML semantik dan aksesibilitas dasar', 'Styling responsif dengan CSS', 'Interaksi halaman menggunakan JavaScript', 'Version control dan publikasi website'],
                ),
                $this->course(
                    title: 'Laravel Web Application Development',
                    slug: 'laravel-web-application-development',
                    duration: '4 hari', level: 'Intermediate', format: 'Hybrid',
                    description: 'Membangun aplikasi web berbasis Laravel mulai dari routing, database, autentikasi, validasi, hingga deployment.',
                    outcome: 'Mampu membuat aplikasi CRUD yang aman, teruji, dan siap dijalankan pada lingkungan produksi.',
                    syllabus: ['Routing, controller, Blade, dan validasi', 'Eloquent ORM, relasi, migration, dan seeding', 'Autentikasi, otorisasi, dan keamanan aplikasi', 'Testing, optimasi, dan deployment aplikasi'],
                ),
                $this->course(
                    title: 'Data Analytics dengan Excel & Power BI',
                    slug: 'data-analytics-excel-power-bi',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Hybrid',
                    description: 'Mengolah data operasional menggunakan Excel dan Power BI untuk menghasilkan indikator kinerja serta dashboard manajemen.',
                    outcome: 'Mampu menyiapkan model data, menghitung KPI, dan membuat dashboard interaktif yang siap dipresentasikan.',
                    syllabus: ['Pembersihan data dengan Excel dan Power Query', 'Pemodelan relasi dan pengenalan DAX', 'Visualisasi KPI dan desain dashboard', 'Publikasi, filter, dan storytelling data'],
                ),
                $this->course(
                    title: 'Cybersecurity Awareness & Practice',
                    slug: 'cybersecurity-awareness-practice',
                    duration: '2 hari', level: 'Basic', format: 'Onsite / Hybrid',
                    description: 'Praktik keamanan digital untuk mengenali phishing, menjaga akun, melindungi perangkat, dan merespons insiden dasar.',
                    outcome: 'Mampu menerapkan kebiasaan kerja digital yang aman dan mengikuti prosedur respons awal ketika insiden terjadi.',
                    syllabus: ['Ancaman siber, social engineering, dan phishing', 'Password manager dan multi-factor authentication', 'Keamanan perangkat, jaringan, dan berbagi data', 'Simulasi insiden serta prosedur pelaporan'],
                ),
                $this->course(
                    title: 'Network Administration Fundamentals',
                    slug: 'network-administration-fundamentals',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Praktik Lab',
                    description: 'Dasar perancangan, konfigurasi, pemantauan, dan troubleshooting jaringan komputer untuk kantor maupun laboratorium.',
                    outcome: 'Mampu membangun jaringan lokal sederhana, mengatur layanan dasar, dan mendiagnosis gangguan konektivitas.',
                    syllabus: ['Model jaringan, IP addressing, dan subnetting', 'Konfigurasi switch, router, DHCP, dan DNS', 'Wireless networking dan segmentasi dasar', 'Monitoring serta troubleshooting koneksi'],
                ),
                $this->course(
                    title: 'Cloud Computing & Deployment',
                    slug: 'cloud-computing-deployment',
                    duration: '3 hari', level: 'Intermediate', format: 'Online / Hybrid',
                    description: 'Mengenal layanan komputasi awan dan mempraktikkan deployment aplikasi dengan konfigurasi server, domain, SSL, serta monitoring.',
                    outcome: 'Mampu men-deploy aplikasi ke cloud dengan konfigurasi dasar yang aman, terukur, dan mudah dipantau.',
                    syllabus: ['Konsep IaaS, PaaS, container, dan arsitektur cloud', 'Provisioning server dan konfigurasi environment', 'Domain, DNS, SSL, serta deployment aplikasi', 'Backup, monitoring, logging, dan optimasi biaya'],
                ),
                $this->course(
                    title: 'UI/UX Design dengan Figma',
                    slug: 'ui-ux-design-figma',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Offline / Studio',
                    description: 'Merancang pengalaman pengguna dan antarmuka produk digital melalui riset, wireframe, design system, dan prototyping di Figma.',
                    outcome: 'Mampu menghasilkan prototipe interaktif yang konsisten, mudah digunakan, dan siap diserahkan kepada developer.',
                    syllabus: ['Riset pengguna, persona, dan user flow', 'Wireframe dan arsitektur informasi', 'Design system, komponen, dan auto layout', 'Prototyping, usability testing, dan handoff'],
                ),
                $this->course(
                    title: 'Database Management dengan SQL',
                    slug: 'database-management-sql',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Praktik Lab',
                    description: 'Perancangan database relasional dan penggunaan SQL untuk menyimpan, mengambil, menggabungkan, serta merangkum data secara efisien.',
                    outcome: 'Mampu merancang skema database dan menulis query SQL untuk kebutuhan aplikasi maupun pelaporan.',
                    syllabus: ['Pemodelan relasional, tabel, dan normalisasi', 'Query SELECT, filter, agregasi, dan subquery', 'JOIN, transaksi, index, dan integritas data', 'Backup, akses pengguna, dan optimasi query dasar'],
                ),
            ],
            'photo-video' => [
                $this->course(
                    title: 'Basic Photography DSLR & Mirrorless',
                    slug: 'basic-photography-dslr-mirrorless',
                    duration: '2 hari', level: 'Basic', format: 'Offline / Studio',
                    description: 'Menguasai exposure, fokus, lensa, komposisi, dan pengoperasian kamera DSLR maupun mirrorless melalui praktik terarah.',
                    outcome: 'Mampu mengoperasikan kamera secara manual dan menghasilkan foto tajam dengan pencahayaan serta komposisi yang tepat.',
                    syllabus: ['Anatomi kamera, lensa, dan mode pemotretan', 'Exposure triangle dan metering', 'Fokus, depth of field, dan pilihan focal length', 'Praktik komposisi serta evaluasi hasil foto'],
                ),
                $this->course(
                    title: 'Product Photography',
                    slug: 'product-photography',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Offline / Studio',
                    description: 'Teknik fotografi produk untuk katalog dan marketplace menggunakan pencahayaan studio, styling, dan retouching yang efisien.',
                    outcome: 'Mampu menghasilkan foto produk yang konsisten, informatif, dan menarik untuk kebutuhan penjualan digital.',
                    syllabus: ['Konsep visual produk dan shot list', 'Lighting setup untuk produk reflektif dan nonreflektif', 'Styling, komposisi, dan pengaturan kamera', 'Seleksi, retouching, dan ekspor katalog'],
                ),
                $this->course(
                    title: 'Portrait & Studio Lighting',
                    slug: 'portrait-studio-lighting',
                    duration: '2 hari', level: 'Intermediate', format: 'Offline / Studio',
                    description: 'Membangun pencahayaan portrait yang profesional menggunakan flash, modifier, posing, dan kontrol suasana studio.',
                    outcome: 'Mampu merancang beberapa skema pencahayaan portrait dan mengarahkan subjek untuk hasil yang konsisten.',
                    syllabus: ['Karakter cahaya dan penggunaan modifier', 'One-light, two-light, dan three-point setup', 'Posing, directing, dan komunikasi dengan subjek', 'Retouching warna kulit dan finalisasi foto'],
                ),
                $this->course(
                    title: 'Cinematic Videography',
                    slug: 'cinematic-videography',
                    duration: '3 hari', level: 'Intermediate', format: 'Offline / Studio',
                    description: 'Produksi video sinematik melalui perencanaan visual, tata kamera, pencahayaan, pergerakan, dan perekaman audio.',
                    outcome: 'Mampu mengeksekusi video pendek dengan bahasa visual, kontinuitas, dan kualitas teknis yang kuat.',
                    syllabus: ['Visual storytelling, storyboard, dan shot list', 'Frame rate, shutter, lensa, dan camera movement', 'Lighting scene dan perekaman audio lapangan', 'Produksi adegan serta review hasil pengambilan'],
                ),
                $this->course(
                    title: 'Adobe Premiere Pro Video Editing',
                    slug: 'adobe-premiere-pro-video-editing',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Praktik Lab',
                    description: 'Editing video profesional di Adobe Premiere Pro dari pengelolaan media, penyusunan cerita, audio, grafis, hingga ekspor.',
                    outcome: 'Mampu menyelesaikan proyek video yang rapi dan siap tayang melalui workflow editing yang terstruktur.',
                    syllabus: ['Project setup, ingest, proxy, dan organisasi media', 'Cutting, continuity, pacing, dan multicam', 'Audio mixing, caption, dan motion graphics', 'Color correction serta export multiplatform'],
                ),
                $this->course(
                    title: 'DaVinci Resolve Color Grading',
                    slug: 'davinci-resolve-color-grading',
                    duration: '3 hari', level: 'Intermediate', format: 'Praktik Lab',
                    description: 'Koreksi warna dan color grading menggunakan DaVinci Resolve untuk menciptakan tampilan visual yang konsisten dan sinematik.',
                    outcome: 'Mampu menyeimbangkan warna antar-shot dan membangun look kreatif dengan workflow node yang terkontrol.',
                    syllabus: ['Color management, scopes, dan primary correction', 'Secondary correction, qualifier, dan power window', 'Node workflow, matching shot, dan skin tone', 'Look development, noise reduction, dan delivery'],
                ),
                $this->course(
                    title: 'Podcast & Live Streaming Production',
                    slug: 'podcast-live-streaming-production',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Offline / Studio',
                    description: 'Produksi podcast video dan live streaming dengan tata kamera, audio, pencahayaan, switching, serta distribusi multiplatform.',
                    outcome: 'Mampu menyiapkan dan menjalankan produksi siaran langsung atau podcast dengan kualitas audio visual yang stabil.',
                    syllabus: ['Perencanaan format acara dan rundown teknis', 'Setup kamera, lighting, mikrofon, dan mixer', 'OBS, scene, switching, grafis, dan monitoring', 'Rekaman, streaming, troubleshooting, dan distribusi'],
                ),
            ],
            'digital-marketing' => [
                $this->course(
                    title: 'SEO & Content Marketing',
                    slug: 'seo-content-marketing',
                    duration: '3 hari', level: 'Basic–Intermediate', format: 'Hybrid',
                    description: 'Menyusun strategi konten berbasis kebutuhan pencarian untuk meningkatkan visibilitas organik, trafik berkualitas, dan konversi website.',
                    outcome: 'Mampu melakukan riset keyword, merancang content plan, dan mengoptimalkan halaman berdasarkan prinsip SEO.',
                    syllabus: ['Search intent, riset keyword, dan analisis kompetitor', 'On-page SEO dan struktur konten', 'Technical SEO dasar dan internal linking', 'Pengukuran ranking, trafik, dan konversi'],
                ),
                $this->course(
                    title: 'Google Ads Masterclass',
                    slug: 'google-ads-masterclass',
                    duration: '3 hari', level: 'Intermediate', format: 'Online / Offline',
                    description: 'Mengelola kampanye Google Ads dari riset keyword dan struktur akun hingga conversion tracking serta optimasi biaya.',
                    outcome: 'Mampu membangun kampanye pencarian yang terukur dan mengoptimalkannya berdasarkan data konversi.',
                    syllabus: ['Riset keyword dan struktur campaign', 'Penulisan iklan serta optimasi landing page', 'Bidding, audience, extension, dan negative keyword', 'Conversion tracking serta optimasi performa'],
                ),
                $this->course(
                    title: 'TikTok Marketing & Ads',
                    slug: 'tiktok-marketing-ads',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Hybrid',
                    description: 'Strategi pertumbuhan organik dan iklan TikTok melalui riset tren, creative testing, kolaborasi kreator, dan evaluasi performa.',
                    outcome: 'Mampu merancang konten TikTok dan kampanye iklan yang relevan dengan audiens serta tujuan bisnis.',
                    syllabus: ['Perilaku audiens, tren, dan content angle', 'Hook, script, produksi, dan optimasi konten', 'TikTok Ads Manager dan struktur campaign', 'Creative testing, analitik, dan scaling'],
                ),
                $this->course(
                    title: 'Marketplace Optimization',
                    slug: 'marketplace-optimization',
                    duration: '2 hari', level: 'Basic–Intermediate', format: 'Online / Offline',
                    description: 'Optimasi toko dan produk di marketplace melalui riset kata kunci, kualitas listing, promosi, iklan, dan layanan pelanggan.',
                    outcome: 'Mampu meningkatkan kualitas katalog, visibilitas produk, dan performa penjualan toko marketplace.',
                    syllabus: ['Audit toko, kategori, dan riset kompetitor', 'Optimasi judul, foto, deskripsi, serta keyword', 'Voucher, campaign, iklan, dan strategi harga', 'Chat, ulasan, fulfillment, dan analitik toko'],
                ),
                $this->course(
                    title: 'Copywriting untuk Penjualan Digital',
                    slug: 'copywriting-penjualan-digital',
                    duration: '2 hari', level: 'Basic', format: 'Online / Offline',
                    description: 'Menulis pesan pemasaran yang persuasif untuk iklan, landing page, media sosial, dan WhatsApp dengan struktur yang berorientasi konversi.',
                    outcome: 'Mampu menghasilkan copy yang jelas, relevan, dan mendorong audiens mengambil tindakan.',
                    syllabus: ['Riset audiens, pain point, dan value proposition', 'Headline, hook, benefit, proof, dan call to action', 'Copy untuk iklan, landing page, dan WhatsApp', 'Editing, A/B testing, dan evaluasi konversi'],
                ),
                $this->course(
                    title: 'Email Marketing & CRM Automation',
                    slug: 'email-marketing-crm-automation',
                    duration: '2 hari', level: 'Intermediate', format: 'Online',
                    description: 'Membangun segmentasi pelanggan dan rangkaian email otomatis untuk nurturing lead, retensi, serta aktivasi ulang pelanggan.',
                    outcome: 'Mampu menyusun lifecycle campaign yang terukur dan terintegrasi dengan data pelanggan di CRM.',
                    syllabus: ['Database pelanggan, consent, dan segmentasi', 'Email copy, template, deliverability, dan personalisasi', 'Automation flow untuk welcome, nurturing, dan win-back', 'Tracking open, click, conversion, dan eksperimen'],
                ),
                $this->course(
                    title: 'Digital Marketing Analytics',
                    slug: 'digital-marketing-analytics',
                    duration: '3 hari', level: 'Intermediate', format: 'Hybrid',
                    description: 'Mengukur customer journey dan performa pemasaran melalui tracking, dashboard, attribution, serta analisis funnel lintas kanal.',
                    outcome: 'Mampu membangun kerangka pengukuran dan menerjemahkan data pemasaran menjadi rekomendasi optimasi.',
                    syllabus: ['KPI, funnel, customer journey, dan measurement plan', 'UTM, event tracking, dan kualitas data', 'Dashboard performa lintas kanal', 'Attribution, eksperimen, dan rekomendasi optimasi'],
                ),
            ],
        ];
    }

    /**
     * @param  array<string, list<array{title: string, slug: string, duration: string, level: string, format: string, description: string, outcome: string, syllabus: list<string>}>>  $coursesByCategory
     */
    private function seedPrograms(array $coursesByCategory): void
    {

        $categories = Category::query()
            ->whereIn('slug', array_keys($coursesByCategory))
            ->get()
            ->keyBy('slug');

        foreach ($coursesByCategory as $categorySlug => $courses) {
            $category = $categories->get($categorySlug);

            if (! $category instanceof Category) {
                throw new LogicException("Training category [{$categorySlug}] does not exist.");
            }

            foreach ($courses as $course) {
                Course::query()->updateOrCreate(
                    ['slug' => $course['slug']],
                    [
                        ...$course,
                        'category_id' => $category->id,
                        'is_featured' => false,
                    ],
                );
            }
        }
    }

    /**
     * @param  list<string>  $syllabus
     * @return array{title: string, slug: string, duration: string, level: string, format: string, description: string, outcome: string, syllabus: list<string>}
     */
    private function course(
        string $title,
        string $slug,
        string $duration,
        string $level,
        string $format,
        string $description,
        string $outcome,
        array $syllabus,
    ): array {
        return compact('title', 'slug', 'duration', 'level', 'format', 'description', 'outcome', 'syllabus');
    }
}
