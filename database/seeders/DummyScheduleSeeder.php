<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DummyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::query()
            ->with('category')
            ->where('status', 'Publish')
            ->get()
            ->values();

        if ($courses->isEmpty()) {
            return;
        }

        $scheduleLocations = [
            'photo-video' => 'Studio Foto Komersial JCP',
            'digital-marketing' => 'JCP Learning Studio & Zoom',
            'artificial-intelligence' => 'JCP Learning Studio & Online Hybrid',
            'drone-technology' => 'Lapangan Terbang Gading & Studio JCP',
            'information-technology' => 'JCP Tech Hub Yogyakarta',
        ];

        $schedulePrices = [
            'photo-video' => 1500000,
            'digital-marketing' => 1250000,
            'artificial-intelligence' => 1750000,
            'drone-technology' => 2500000,
            'information-technology' => 2250000,
        ];

        $monthStart = Carbon::today()->startOfMonth();
        $lastScheduleMonth = Carbon::create(2027, 12, 1);

        if ($monthStart->greaterThan($lastScheduleMonth)) {
            return;
        }

        $monthCount = $monthStart->diffInMonths($lastScheduleMonth) + 1;

        foreach (range(0, $monthCount - 1) as $monthOffset) {
            $currentMonth = $monthStart->copy()->addMonths($monthOffset);
            $batchNumber = str_pad((int) floor($monthOffset / 3) + 1, 2, '0', STR_PAD_LEFT);

            foreach ($courses as $courseIndex => $course) {
                if ($courseIndex % 3 !== $monthOffset % 3) {
                    continue;
                }

                $categorySlug = $course->category->slug;
                $durationInDays = max((int) preg_replace('/\D+/', '', $course->duration), 1);
                $startDate = $currentMonth->copy()
                    ->next(Carbon::SATURDAY)
                    ->addWeeks($courseIndex % 4);
                $batchName = 'Batch '.$batchNumber.' - '.$currentMonth->format('F Y');

                CourseSession::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'batch_name' => $batchName,
                        'start_date' => $startDate->toDateString(),
                    ],
                    [
                        'end_date' => $startDate->copy()->addDays($durationInDays - 1),
                        'location' => $scheduleLocations[$categorySlug],
                        'quota' => $categorySlug === 'drone-technology' ? 12 : 15,
                        'price' => $schedulePrices[$categorySlug],
                        'status' => 'Buka',
                    ],
                );
            }
        }
    }
}
