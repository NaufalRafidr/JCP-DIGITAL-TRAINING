<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Registration;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Show the MVP registration form (Slide 11).
     */
    public function create(Request $request): View
    {
        $selectedCourseId = $request->query('course_id');
        $selectedSessionId = $request->query('session_id');

        $courses = Course::with('sessions')
            ->where('status', 'Publish')
            ->whereHas('sessions', fn ($query) => $query->where('status', 'Buka'))
            ->get();
        $sessions = CourseSession::with('course')->where('status', 'Buka')->orderBy('start_date', 'asc')->get();

        return view('registration.create', compact('courses', 'sessions', 'selectedCourseId', 'selectedSessionId'));
    }

    /**
     * Store registration and redirect to confirmation (Slide 11).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'course_id' => [
                'nullable',
                'exists:courses,id',
                Rule::requiredIf($request->input('submission_type') !== Registration::TYPE_CORPORATE),
                function (string $attribute, mixed $value, Closure $fail) use ($request): void {
                    if ($value === null || $request->input('submission_type') === Registration::TYPE_CORPORATE) {
                        return;
                    }

                    $hasOpenSession = Course::query()
                        ->whereKey($value)
                        ->where('status', 'Publish')
                        ->whereHas('sessions', fn ($query) => $query->where('status', 'Buka'))
                        ->exists();

                    if (! $hasOpenSession) {
                        $fail('Program ini belum memiliki jadwal/batch yang sedang dibuka.');
                    }
                },
            ],
            'course_session_id' => [
                'nullable',
                Rule::exists('course_sessions', 'id')
                    ->where(fn ($query) => $query
                        ->where('course_id', $request->integer('course_id'))
                        ->where('status', 'Buka')),
            ],
            'organization' => 'nullable|string|max:255',
            'submission_type' => [
                'nullable',
                Rule::in([Registration::TYPE_PERSONAL, Registration::TYPE_CORPORATE]),
            ],
            'training_area' => 'nullable|string|max:255',
            'quota_est' => 'nullable|string|max:255',
            'location_pref' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'consent' => 'accepted',
        ]);

        $submissionType = $validated['submission_type'] ?? Registration::TYPE_PERSONAL;
        $notes = collect([
            isset($validated['training_area']) ? 'Bidang pelatihan: '.$validated['training_area'] : null,
            isset($validated['quota_est']) ? 'Jumlah peserta: '.$validated['quota_est'] : null,
            isset($validated['location_pref']) ? 'Lokasi & waktu: '.$validated['location_pref'] : null,
            $validated['notes'] ?? null,
        ])->filter()->implode("\n");

        $registration = Registration::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'course_id' => $validated['course_id'] ?? null,
            'course_session_id' => $validated['course_session_id'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'notes' => $notes !== '' ? $notes : null,
            'consent' => true,
            'status' => 'Pending',
            'submission_type' => $submissionType,
        ]);

        return redirect()->route('register.success', ['id' => $registration->id])
            ->with('success', 'Pendaftaran Anda berhasil tercatat! Tim Admin JCP Training akan segera menghubungi via WhatsApp.');
    }

    /**
     * Confirmation page.
     */
    public function success(int $id): View
    {
        $registration = Registration::with(['course', 'courseSession'])->findOrFail($id);

        return view('registration.success', compact('registration'));
    }
}
