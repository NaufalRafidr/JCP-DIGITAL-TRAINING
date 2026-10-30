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
        Schema::table('courses', function (Blueprint $table): void {
            $table->text('image_url')->nullable()->after('image');
        });

        Schema::table('gallery_photos', function (Blueprint $table): void {
            $table->string('path')->nullable()->change();
            $table->text('image_url')->nullable()->after('thumbnail_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropColumn('image_url');
        });

        Schema::table('gallery_photos', function (Blueprint $table): void {
            $table->dropColumn('image_url');
            $table->string('path')->nullable(false)->change();
        });
    }
};
