<?php

use Database\Seeders\TrainingProgramSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $programsByCategory = app(TrainingProgramSeeder::class)->programsByCategory();
        $categoryIds = DB::table('categories')
            ->whereIn('slug', array_keys($programsByCategory))
            ->pluck('id', 'slug');

        if ($categoryIds->count() !== count($programsByCategory)) {
            return;
        }

        DB::transaction(function () use ($categoryIds, $programsByCategory): void {
            $timestamp = now();

            foreach ($programsByCategory as $categorySlug => $programs) {
                foreach ($programs as $program) {
                    if (DB::table('courses')->where('slug', $program['slug'])->exists()) {
                        continue;
                    }

                    DB::table('courses')->insert([
                        ...$program,
                        'category_id' => $categoryIds->get($categorySlug),
                        'syllabus' => json_encode($program['syllabus'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                        'is_featured' => false,
                        'status' => 'Publish',
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Program data is preserved because it may already be linked to schedules and registrations.
    }
};
