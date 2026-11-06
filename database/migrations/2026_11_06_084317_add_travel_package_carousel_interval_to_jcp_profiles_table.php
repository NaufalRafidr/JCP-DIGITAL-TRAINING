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
            $table->unsignedSmallInteger('travel_package_carousel_interval_ms')
                ->default(2500)
                ->after('travel_package_background_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->dropColumn('travel_package_carousel_interval_ms');
        });
    }
};
