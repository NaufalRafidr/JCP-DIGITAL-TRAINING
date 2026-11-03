<?php

namespace Database\Seeders;

use App\Models\CourseSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use RuntimeException;

class DummyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templateSessions = CourseSession::query()
            ->with('course')
            ->whereBetween('start_date', ['2026-11-01', '2026-11-30'])
            ->orderBy('start_date')
            ->orderBy('id')
            ->limit(6)
            ->get()
            ->filter(fn (CourseSession $session): bool => $session->course !== null)
            ->values();

        if ($templateSessions->isEmpty()) {
            throw new RuntimeException('Tidak ditemukan jadwal template pada tabel course_sessions.');
        }

        $monthStart = Carbon::create(2026, 11, 1);
        $lastScheduleMonth = Carbon::create(2027, 12, 1);
        $monthCount = $monthStart->diffInMonths($lastScheduleMonth) + 1;
        $createdSessions = 0;

        foreach (range(0, $monthCount - 1) as $monthOffset) {
            $currentMonth = $monthStart->copy()->addMonths($monthOffset);
            $batchNumber = str_pad((int) floor($monthOffset / 3) + 1, 2, '0', STR_PAD_LEFT);

            foreach ($templateSessions as $templateSession) {
                $dayOfMonth = min(
                    $templateSession->start_date->day,
                    $currentMonth->copy()->endOfMonth()->day,
                );
                $startDate = $currentMonth->copy()->day($dayOfMonth);
                $durationInDays = $templateSession->end_date === null
                    ? 1
                    : max($templateSession->start_date->diffInDays($templateSession->end_date) + 1, 1);
                $batchName = 'Batch '.$batchNumber.' - '.$currentMonth->format('F Y');

                $session = CourseSession::firstOrCreate(
                    [
                        'course_id' => $templateSession->course_id,
                        'batch_name' => $batchName,
                        'start_date' => $startDate->toDateString(),
                    ],
                    [
                        'end_date' => $startDate->copy()->addDays($durationInDays - 1),
                        'location' => $templateSession->location,
                        'quota' => $templateSession->quota,
                        'price' => $templateSession->price,
                        'status' => $templateSession->status,
                    ],
                );

                if ($session->wasRecentlyCreated) {
                    $createdSessions++;
                }
            }
        }

        $this->command?->info(sprintf(
            'Jadwal template: %d, jadwal baru: %d, total jadwal: %d.',
            $templateSessions->count(),
            $createdSessions,
            CourseSession::count(),
        ));
    }
}
