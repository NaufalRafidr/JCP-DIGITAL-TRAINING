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
            $table->string('hero_image')->nullable()->after('tagline');
            $table->text('hero_image_url')->nullable()->after('hero_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcp_profiles', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'hero_image_url']);
        });
    }
};
