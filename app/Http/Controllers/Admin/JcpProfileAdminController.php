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
        ]);

        JcpProfile::query()->firstOrFail()->update($validated);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'profil'])
            ->with('success', 'Profil JCP berhasil diperbarui.');
    }
}
