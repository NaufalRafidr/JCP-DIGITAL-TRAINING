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
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->string('travel_package_badge')->nullable()->after('hero_image_url');
            $table->string('travel_package_title')->nullable()->after('travel_package_badge');
            $table->text('travel_package_description')->nullable()->after('travel_package_title');
            $table->json('travel_package_features')->nullable()->after('travel_package_description');
            $table->string('travel_package_button_text')->nullable()->after('travel_package_features');
            $table->text('travel_package_button_url')->nullable()->after('travel_package_button_text');
            $table->json('travel_package_background_images')->nullable()->after('travel_package_button_url');
        });

        DB::table('jcp_profiles')->update([
            'travel_package_badge' => 'Paket pelatihan + wisata',
            'travel_package_title' => 'Bikin liburanmu lebih meriah dengan pelatihan bersama JCP Digital Training',
            'travel_package_description' => 'Gabungkan pengalaman belajar, team building, dan eksplorasi destinasi dalam satu perjalanan yang berkesan.',
            'travel_package_features' => json_encode([
                'Kelas praktis',
                'Custom itinerary',
                'Untuk tim & komunitas',
            ], JSON_THROW_ON_ERROR),
            'travel_package_button_text' => 'Konsultasikan paket',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'travel_package_badge',
                'travel_package_title',
                'travel_package_description',
                'travel_package_features',
                'travel_package_button_text',
                'travel_package_button_url',
                'travel_package_background_images',
            ]);
        });
    }
};
