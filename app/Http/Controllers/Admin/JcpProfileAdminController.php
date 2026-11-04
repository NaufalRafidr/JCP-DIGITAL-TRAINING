<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JcpProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class JcpProfileAdminController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:500'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'hero_image_url' => ['nullable', 'url', 'max:2048'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'vision_title' => ['required', 'string', 'max:255'],
            'vision_content' => ['required', 'string', 'max:2000'],
        ]);

        $profile = JcpProfile::query()->firstOrFail();
        $oldImagePath = null;
        $heroImagePath = $profile->hero_image;
        $heroImageUrl = $profile->hero_image_url;

        if (isset($validated['hero_image'])) {
            $oldImagePath = $profile->hero_image;
            $heroImagePath = $this->storeHeroImage($validated['hero_image']);
            $heroImageUrl = null;
        } elseif (array_key_exists('hero_image_url', $validated) && $validated['hero_image_url'] !== null) {
            $oldImagePath = $profile->hero_image;
            $heroImagePath = null;
            $heroImageUrl = $validated['hero_image_url'];
        } elseif ($request->boolean('remove_hero_image')) {
            $oldImagePath = $profile->hero_image;
            $heroImagePath = null;
            $heroImageUrl = null;
        }

        $profile->update([
            'organization_name' => $validated['organization_name'],
            'tagline' => $validated['tagline'],
            'hero_image' => $heroImagePath,
            'hero_image_url' => $heroImageUrl,
            'vision_title' => $validated['vision_title'],
            'vision_content' => $validated['vision_content'],
        ]);

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'profil'])
            ->with('success', 'Profil JCP berhasil diperbarui.');
    }

    private function storeHeroImage(?UploadedFile $image): ?string
    {
        if ($image === null) {
            return null;
        }

        $path = $image->store('jcp/hero', 'public');

        abort_if($path === false, 500, 'Gambar hero beranda gagal disimpan.');

        return $path;
    }
}
