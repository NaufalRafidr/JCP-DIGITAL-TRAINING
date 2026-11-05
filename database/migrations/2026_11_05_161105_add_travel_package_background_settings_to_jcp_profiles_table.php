<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->json('travel_package_background_settings')->nullable()->after('travel_package_background_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->dropColumn('travel_package_background_settings');
        });
    }
};
