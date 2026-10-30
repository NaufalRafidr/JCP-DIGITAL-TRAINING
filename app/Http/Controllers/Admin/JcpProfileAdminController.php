<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JcpProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JcpProfileAdminController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:500'],
            'vision_title' => ['required', 'string', 'max:255'],
            'vision_content' => ['required', 'string', 'max:2000'],
            'programs_stat' => ['required', 'string', 'max:30'],
            'programs_label' => ['required', 'string', 'max:120'],
            'expertise_stat' => ['required', 'string', 'max:30'],
            'expertise_label' => ['required', 'string', 'max:120'],
            'practice_stat' => ['required', 'string', 'max:30'],
            'practice_label' => ['required', 'string', 'max:120'],
        ]);

        JcpProfile::query()->firstOrFail()->update($validated);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'profil'])
            ->with('success', 'Profil JCP berhasil diperbarui.');
    }
}
