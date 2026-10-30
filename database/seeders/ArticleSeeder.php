<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Cara Menggunakan ChatGPT & Claude untuk Otomasi Laporan Kerja Bulanan',
                'slug' => 'cara-menggunakan-chatgpt-claude-otomasi-laporan',
                'category' => 'Artificial Intelligence',
                'excerpt' => 'Panduan menyusun prompt engineering agar dokumen kantor bebas halusinasi dan terstruktur rapi.',
                'content' => 'Pelajari cara menyusun prompt yang jelas, memeriksa keluaran AI, dan membuat alur kerja laporan bulanan yang lebih konsisten.',
                'reading_time' => 5,
                'published_at' => '2026-10-24',
                'status' => 'Publish',
            ],
            [
                'title' => 'Regulasi Ruang Udara KKOP Indonesia untuk Pilot Drone Pemetaan',
                'slug' => 'regulasi-ruang-udara-kkop-pilot-drone',
                'category' => 'Drone Technology',
                'excerpt' => 'Langkah-langkah perizinan dan SOP keselamatan terbang fotogrametri di area perkotaan.',
                'content' => 'Kenali persiapan misi, pemeriksaan area, dan dokumen keselamatan yang perlu disiapkan sebelum menerbangkan drone untuk pemetaan.',
                'reading_time' => 7,
                'published_at' => '2026-10-18',
                'status' => 'Publish',
            ],
            [
                'title' => 'Studi Kasus UMKM: Naikkan Omzet dengan Funnel WhatsApp Automation',
                'slug' => 'studi-kasus-umkm-funnel-whatsapp-automation',
                'category' => 'Digital Marketing',
                'excerpt' => 'Membangun alur interaksi otomatis tanpa kehilangan sentuhan personal kepada pelanggan.',
                'content' => 'Susun funnel dari promosi, percakapan WhatsApp, follow-up, sampai pengukuran konversi agar setiap interaksi dapat dievaluasi.',
                'reading_time' => 6,
                'published_at' => '2026-10-12',
                'status' => 'Publish',
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(['slug' => $article['slug']], $article);
        }
    }
}
