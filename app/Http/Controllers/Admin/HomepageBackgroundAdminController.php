<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JcpProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HomepageBackgroundAdminController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'background' => ['required', 'string', 'max:2048'],
            'position_x' => ['required', 'integer', 'between:0,100'],
            'position_y' => ['required', 'integer', 'between:0,100'],
            'scale' => ['required', 'integer', 'between:100,200'],
        ]);

        $profile = JcpProfile::query()->firstOrFail();
        $backgrounds = $this->backgrounds($profile);

        abort_unless(in_array($validated['background'], $backgrounds, true), 404);

        $settings = $profile->travel_package_background_settings ?? [];
        $settings[JcpProfile::travelPackageBackgroundSettingsKey($validated['background'])] = [
            'position_x' => $validated['position_x'],
            'position_y' => $validated['position_y'],
            'scale' => $validated['scale'],
        ];
        $profile->update(['travel_package_background_settings' => $settings]);

        return response()->json([
            'message' => 'Posisi dan ukuran background berhasil disimpan.',
            'settings' => $profile->travelPackageBackgroundSettings($validated['background']),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'backgrounds' => ['required', 'array', 'min:1', 'max:8'],
            'backgrounds.*' => ['required', 'string', 'distinct', 'max:2048'],
        ]);

        $profile = JcpProfile::query()->firstOrFail();
        $backgrounds = $this->backgrounds($profile);
        $selectedBackgrounds = array_values(array_intersect($backgrounds, $validated['backgrounds']));

        if (count($selectedBackgrounds) !== count($validated['backgrounds'])) {
            throw ValidationException::withMessages([
                'backgrounds' => 'Satu atau beberapa background yang dipilih tidak ditemukan.',
            ]);
        }

        $settings = $profile->travel_package_background_settings ?? [];

        foreach ($selectedBackgrounds as $background) {
            unset($settings[JcpProfile::travelPackageBackgroundSettingsKey($background)]);
        }

        $remainingBackgrounds = array_values(array_diff($backgrounds, $selectedBackgrounds));
        $profile->update([
            'travel_package_background_images' => $remainingBackgrounds,
            'travel_package_background_settings' => $settings,
        ]);

        Storage::disk('public')->delete(array_filter(
            $selectedBackgrounds,
            fn (string $background): bool => ! $this->isRemoteImage($background),
        ));

        return response()->json([
            'message' => count($selectedBackgrounds).' background berhasil dihapus.',
            'deleted' => $selectedBackgrounds,
            'remaining_count' => count($remainingBackgrounds),
        ]);
    }

    /**
     * @return list<string>
     */
    private function backgrounds(JcpProfile $profile): array
    {
        return array_values(array_filter(
            $profile->travel_package_background_images ?? [],
            fn (mixed $background): bool => is_string($background) && $background !== '',
        ));
    }

    private function isRemoteImage(string $background): bool
    {
        return in_array(strtolower((string) parse_url($background, PHP_URL_SCHEME)), ['http', 'https'], true);
    }
}
