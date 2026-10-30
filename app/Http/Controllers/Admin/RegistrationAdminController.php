<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationAdminController extends Controller
{
    /**
     * Perbarui status pendaftaran dari dashboard admin.
     */
    public function update(Request $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Follow-up,Confirmed',
            'redirect_course_id' => 'nullable|integer|exists:courses,id',
            'redirect_session_id' => 'nullable|integer|exists:course_sessions,id',
        ]);

        $registration->update([
            'status' => $validated['status'],
        ]);

        $redirectParameters = ['tab' => 'pendaftaran'];
        if (! empty($validated['redirect_course_id'])) {
            $redirectParameters['course_id'] = $validated['redirect_course_id'];
        }
        if (! empty($validated['redirect_session_id'])) {
            $redirectParameters['session_id'] = $validated['redirect_session_id'];
        }

        return redirect()
            ->route('admin.dashboard', $redirectParameters)
            ->with('success', 'Status pendaftar "'.$registration->name.'" berhasil diperbarui.');
    }
}
