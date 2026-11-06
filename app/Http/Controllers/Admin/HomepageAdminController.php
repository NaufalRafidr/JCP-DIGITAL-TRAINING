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

class HomepageAdminController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $backgroundUploads = array_values(array_filter([
            ...($request->file('travel_package_background_images', []) ?? []),
            ...($request->file('travel_package_background_folder', []) ?? []),
        ], fn (mixed $image): bool => $image instanceof UploadedFile && $image->getError() !== UPLOAD_ERR_NO_FILE));

        $request->files->set('travel_package_background_images', $backgroundUploads);

        $validated = $request->validate([
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'hero_image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'travel_package_badge' => ['required', 'string', 'max:100'],
            'travel_package_title' => ['required', 'string', 'max:255'],
            'travel_package_description' => ['required', 'string', 'max:1000'],
            'travel_package_button_text' => ['required', 'string', 'max:100'],
            'travel_package_carousel_interval_seconds' => ['nullable', 'numeric', 'min:1', 'max:60'],
            'travel_package_background_images' => ['nullable', 'array', 'max:8'],
            'travel_package_background_images.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
            'travel_package_background_upload_settings' => ['nullable', 'array', 'max:8'],
            'travel_package_background_upload_settings.*' => ['array'],
            'travel_package_background_upload_settings.*.position_x' => ['required', 'integer', 'between:0,100'],
            'travel_package_background_upload_settings.*.position_y' => ['required', 'integer', 'between:0,100'],
            'travel_package_background_upload_settings.*.scale' => ['required', 'integer', 'between:100,200'],
            'travel_package_background_urls' => ['nullable', 'string', 'max:20000'],
            'travel_package_background_url_settings' => ['nullable', 'array', 'max:8'],
            'travel_package_background_url_settings.*' => ['array'],
            'travel_package_background_url_settings.*.position_x' => ['required', 'integer', 'between:0,100'],
            'travel_package_background_url_settings.*.position_y' => ['required', 'integer', 'between:0,100'],
            'travel_package_background_url_settings.*.scale' => ['required', 'integer', 'between:100,200'],
            'remove_travel_package_background_images' => ['nullable', 'array', 'max:8'],
            'remove_travel_package_background_images.*' => ['string', 'max:2048'],
        ], [
            'travel_package_carousel_interval_seconds.numeric' => 'Waktu pergantian gambar harus berupa angka.',
            'travel_package_carousel_interval_seconds.min' => 'Waktu pergantian gambar minimal 1 detik.',
            'travel_package_carousel_interval_seconds.max' => 'Waktu pergantian gambar maksimal 60 detik.',
        ]);

        $profile = JcpProfile::query()->firstOrFail();
        $backgroundUrls = $this->parseBackgroundUrls($validated['travel_package_background_urls'] ?? '');
        $backgroundUploadSettings = array_values($validated['travel_package_background_upload_settings'] ?? []);
        $backgroundUrlSettings = array_values($validated['travel_package_background_url_settings'] ?? []);
        $existingBackgroundImages = array_values(array_filter(
            $profile->travel_package_background_images ?? [],
            fn (mixed $image): bool => is_string($image) && $image !== '',
        ));
        $requestedBackgroundRemovals = $validated['remove_travel_package_background_images'] ?? [];
        $backgroundImagesToRemove = array_values(array_intersect($existingBackgroundImages, $requestedBackgroundRemovals));
        $retainedBackgroundImages = array_values(array_diff($existingBackgroundImages, $backgroundImagesToRemove));
        $backgroundSettings = $profile->travel_package_background_settings ?? [];

        foreach ($backgroundImagesToRemove as $backgroundImageToRemove) {
            unset($backgroundSettings[JcpProfile::travelPackageBackgroundSettingsKey($backgroundImageToRemove)]);
        }

        if (count($retainedBackgroundImages) + count($backgroundUploads) + count($backgroundUrls) > 8) {
            throw ValidationException::withMessages([
                'travel_package_background_images' => 'Maksimal delapan gambar background dapat disimpan.',
            ]);
        }

        $heroImagePath = $profile->hero_image;
        $heroImageUrl = $profile->hero_image_url;
        $oldHeroImagePath = null;
        $newHeroImagePath = null;
        $newBackgroundImagePaths = [];

        try {
            if (isset($validated['hero_image'])) {
                $oldHeroImagePath = $profile->hero_image;
                $newHeroImagePath = $this->storeImage($validated['hero_image'], 'jcp/hero', 'Gambar hero beranda gagal disimpan.');
                $heroImagePath = $newHeroImagePath;
                $heroImageUrl = null;
            } elseif (array_key_exists('hero_image_url', $validated) && $validated['hero_image_url'] !== null) {
                $oldHeroImagePath = $profile->hero_image;
                $heroImagePath = null;
                $heroImageUrl = $validated['hero_image_url'];
            } elseif ($request->boolean('remove_hero_image')) {
                $oldHeroImagePath = $profile->hero_image;
                $heroImagePath = null;
                $heroImageUrl = null;
            }

            foreach ($backgroundUploads as $backgroundImage) {
                $newBackgroundImagePaths[] = $this->storeImage(
                    $backgroundImage,
                    'jcp/travel-package',
                    'Gambar background paket wisata gagal disimpan.',
                );
            }

            foreach ($newBackgroundImagePaths as $index => $newBackgroundImagePath) {
                $backgroundSettings[JcpProfile::travelPackageBackgroundSettingsKey($newBackgroundImagePath)] = $this->backgroundSettingsAt(
                    $backgroundUploadSettings,
                    $index,
                );
            }

            foreach ($backgroundUrls as $index => $backgroundUrl) {
                $backgroundSettings[JcpProfile::travelPackageBackgroundSettingsKey($backgroundUrl)] = $this->backgroundSettingsAt(
                    $backgroundUrlSettings,
                    $index,
                );
            }

            $profile->update([
                'hero_image' => $heroImagePath,
                'hero_image_url' => $heroImageUrl,
                'travel_package_badge' => $validated['travel_package_badge'],
                'travel_package_title' => $validated['travel_package_title'],
                'travel_package_description' => $validated['travel_package_description'],
                'travel_package_features' => [],
                'travel_package_button_text' => $validated['travel_package_button_text'],
                'travel_package_button_url' => null,
                'travel_package_carousel_interval_ms' => isset($validated['travel_package_carousel_interval_seconds'])
                    ? (int) round((float) $validated['travel_package_carousel_interval_seconds'] * 1000)
                    : $profile->travelPackageCarouselIntervalMilliseconds(),
                'travel_package_background_images' => array_values(array_unique([
                    ...$retainedBackgroundImages,
                    ...$newBackgroundImagePaths,
                    ...$backgroundUrls,
                ])),
                'travel_package_background_settings' => $backgroundSettings,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete([
                ...$newBackgroundImagePaths,
                ...array_filter([$newHeroImagePath]),
            ]);

            throw $exception;
        }

        if ($oldHeroImagePath !== null) {
            Storage::disk('public')->delete($oldHeroImagePath);
        }

        Storage::disk('public')->delete(array_filter(
            $backgroundImagesToRemove,
            fn (string $image): bool => ! $this->isRemoteImage($image),
        ));

        return redirect()
            ->route('admin.dashboard', ['tab' => 'beranda'])
            ->with('success', 'Konten beranda berhasil diperbarui.');
    }

    /**
     * @return list<string>
     */
    private function parseBackgroundUrls(string $urls): array
    {
        $backgroundUrls = array_values(array_unique(array_filter(array_map(
            fn (string $url): string => trim($url),
            preg_split('/\R/u', $urls) ?: [],
        ))));

        foreach ($backgroundUrls as $url) {
            if (! $this->isRemoteImage($url) || filter_var($url, FILTER_VALIDATE_URL) === false || mb_strlen($url) > 2048) {
                throw ValidationException::withMessages([
                    'travel_package_background_urls' => 'Setiap URL background harus berupa alamat HTTP atau HTTPS yang valid.',
                ]);
            }
        }

        return $backgroundUrls;
    }

    private function isRemoteImage(string $image): bool
    {
        return in_array(strtolower((string) parse_url($image, PHP_URL_SCHEME)), ['http', 'https'], true);
    }

    /**
     * @param  array<int, array{position_x?: int, position_y?: int, scale?: int}>  $settings
     * @return array{position_x: int, position_y: int, scale: int}
     */
    private function backgroundSettingsAt(array $settings, int $index): array
    {
        $backgroundSettings = $settings[$index] ?? [];

        return [
            'position_x' => (int) ($backgroundSettings['position_x'] ?? 50),
            'position_y' => (int) ($backgroundSettings['position_y'] ?? 50),
            'scale' => (int) ($backgroundSettings['scale'] ?? 100),
        ];
    }

    private function storeImage(UploadedFile $image, string $directory, string $failureMessage): string
    {
        $path = $image->store($directory, 'public');

        abort_if($path === false, 500, $failureMessage);

        return $path;
    }
}
