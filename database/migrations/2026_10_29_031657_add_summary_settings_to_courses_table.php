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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('summary_duration')->nullable()->after('duration');
            $table->decimal('investment', 12, 2)->nullable()->after('summary_duration');
            $table->text('facilities')->nullable()->after('investment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['summary_duration', 'investment', 'facilities']);
        });
    }
};
