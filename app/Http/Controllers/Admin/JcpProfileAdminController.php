<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JcpProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

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
            'travel_package_badge' => ['sometimes', 'required', 'string', 'max:100'],
            'travel_package_title' => ['sometimes', 'required', 'string', 'max:255'],
            'travel_package_description' => ['sometimes', 'required', 'string', 'max:1000'],
            'travel_package_features' => ['sometimes', 'required', 'string', 'max:500'],
            'travel_package_button_text' => ['sometimes', 'required', 'string', 'max:100'],
            'travel_package_button_url' => ['nullable', 'url:http,https', 'max:2048'],
            'travel_package_background_images' => ['nullable', 'array', 'max:8'],
            'travel_package_background_images.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'remove_travel_package_background_images' => ['nullable', 'array', 'max:8'],
            'remove_travel_package_background_images.*' => ['string', 'max:255'],
            'vision_title' => ['required', 'string', 'max:255'],
            'vision_content' => ['required', 'string', 'max:2000'],
        ]);

        $profile = JcpProfile::query()->firstOrFail();
        $oldImagePath = null;
        $heroImagePath = $profile->hero_image;
        $heroImageUrl = $profile->hero_image_url;
        $travelPackageFeatures = array_key_exists('travel_package_features', $validated)
            ? $this->parseTravelPackageFeatures($validated['travel_package_features'])
            : $profile->travel_package_features;
        $existingBackgroundImages = array_values(array_filter(
            $profile->travel_package_background_images ?? [],
            fn (mixed $path): bool => is_string($path) && $path !== '',
        ));
        $requestedBackgroundRemovals = $validated['remove_travel_package_background_images'] ?? [];
        $backgroundImagesToRemove = array_values(array_intersect($existingBackgroundImages, $requestedBackgroundRemovals));
        $travelPackageBackgroundImages = array_values(array_diff($existingBackgroundImages, $backgroundImagesToRemove));
        $newBackgroundUploads = $validated['travel_package_background_images'] ?? [];

        if (count($travelPackageBackgroundImages) + count($newBackgroundUploads) > 8) {
            throw ValidationException::withMessages([
                'travel_package_background_images' => 'Maksimal delapan gambar background dapat disimpan.',
            ]);
        }

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

        $newBackgroundImagePaths = [];

        try {
            foreach ($newBackgroundUploads as $backgroundImage) {
                $newBackgroundImagePaths[] = $this->storeTravelPackageBackgroundImage($backgroundImage);
            }

            $travelPackageBackgroundImages = [
                ...$travelPackageBackgroundImages,
                ...$newBackgroundImagePaths,
            ];

            $profile->update([
                'organization_name' => $validated['organization_name'],
                'tagline' => $validated['tagline'],
                'hero_image' => $heroImagePath,
                'hero_image_url' => $heroImageUrl,
                'travel_package_badge' => $validated['travel_package_badge'] ?? $profile->travel_package_badge,
                'travel_package_title' => $validated['travel_package_title'] ?? $profile->travel_package_title,
                'travel_package_description' => $validated['travel_package_description'] ?? $profile->travel_package_description,
                'travel_package_features' => $travelPackageFeatures,
                'travel_package_button_text' => $validated['travel_package_button_text'] ?? $profile->travel_package_button_text,
                'travel_package_button_url' => array_key_exists('travel_package_button_url', $validated)
                    ? $validated['travel_package_button_url']
                    : $profile->travel_package_button_url,
                'travel_package_background_images' => $travelPackageBackgroundImages,
                'vision_title' => $validated['vision_title'],
                'vision_content' => $validated['vision_content'],
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newBackgroundImagePaths);

            throw $exception;
        }

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        Storage::disk('public')->delete($backgroundImagesToRemove);

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

    /**
     * @return list<string>
     */
    private function parseTravelPackageFeatures(string $features): array
    {
        $items = array_values(array_filter(array_map(
            fn (string $item): string => trim($item),
            preg_split('/\R/u', $features) ?: [],
        )));

        if ($items === [] || count($items) > 5 || collect($items)->contains(fn (string $item): bool => mb_strlen($item) > 80)) {
            throw ValidationException::withMessages([
                'travel_package_features' => 'Isi satu sampai lima keunggulan, maksimal 80 karakter per baris.',
            ]);
        }

        return $items;
    }

    private function storeTravelPackageBackgroundImage(UploadedFile $image): string
    {
        $path = $image->store('jcp/travel-package', 'public');

        abort_if($path === false, 500, 'Gambar background paket wisata gagal disimpan.');

        return $path;
    }
}
