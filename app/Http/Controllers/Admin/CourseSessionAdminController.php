<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseSessionAdminController extends Controller
{
    /**
     * Simpan batch/jadwal baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'quota' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Buka,Segera,Penuh',
        ]);

        CourseSession::create([
            'course_id' => $validated['course_id'],
            'batch_name' => $validated['batch_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'location' => $validated['location'] ?? 'JCP Learning Studio / Onsite',
            'quota' => $validated['quota'],
            'price' => $validated['price'] ?? 0,
            'status' => $validated['status'],
        ]);

        $duration = CourseSession::make($validated)->duration;

        return redirect()->route('admin.dashboard', ['tab' => 'jadwal'])
            ->with('success', 'Jadwal / Batch "'.$validated['batch_name'].'" berhasil ditambahkan dengan durasi '.$duration.'!');
    }

    /**
     * Update batch/jadwal.
     */
    public function update(Request $request, CourseSession $session): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'quota' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Buka,Segera,Penuh',
        ]);

        $session->update([
            'course_id' => $validated['course_id'],
            'batch_name' => $validated['batch_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'location' => $validated['location'] ?? 'JCP Learning Studio / Onsite',
            'quota' => $validated['quota'],
            'price' => $validated['price'] ?? 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'jadwal'])
            ->with('success', 'Jadwal / Batch "'.$validated['batch_name'].'" berhasil diperbarui dengan durasi '.$session->duration.'!');
    }

    /**
     * Hapus batch/jadwal.
     */
    public function destroy(CourseSession $session): RedirectResponse
    {
        $batchName = $session->batch_name;
        $session->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'jadwal'])
            ->with('success', 'Jadwal / Batch "'.$batchName.'" berhasil dihapus.');
    }
}
