<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jcp_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->text('tagline');
            $table->string('vision_title');
            $table->text('vision_content');
            $table->string('programs_stat', 30);
            $table->string('programs_label', 120);
            $table->string('expertise_stat', 30);
            $table->string('expertise_label', 120);
            $table->string('practice_stat', 30);
            $table->string('practice_label', 120);
            $table->timestamps();
        });

        DB::table('jcp_profiles')->insert([
            'organization_name' => 'PT Jogja Creative Production (JCP Training)',
            'tagline' => 'Pusat pengembangan keterampilan praktis, teknologi mutakhir, dan media kreatif berbasis di Yogyakarta.',
            'vision_title' => 'Visi & Komitmen Kami',
            'vision_content' => 'Menghubungkan kesenjangan keterampilan kerja dengan kebutuhan nyata industri melalui kurikulum hands-on yang dapat langsung dieksekusi. Kami percaya bahwa pelatihan terbaik adalah pelatihan yang menghasilkan portofolio nyata dan efisiensi terukur bagi peserta.',
            'programs_stat' => '15+',
            'programs_label' => 'Program Terstandar',
            'expertise_stat' => '5',
            'expertise_label' => 'Bidang Keahlian',
            'practice_stat' => '100%',
            'practice_label' => 'Praktik Lapangan & Studi Kasus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcp_profiles');
    }
};
