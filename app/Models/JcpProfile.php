<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JcpProfile extends Model
{
    protected $fillable = [
        'organization_name',
        'tagline',
        'hero_image',
        'hero_image_url',
        'travel_package_badge',
        'travel_package_title',
        'travel_package_description',
        'travel_package_features',
        'travel_package_button_text',
        'travel_package_button_url',
        'travel_package_background_images',
        'travel_package_background_settings',
        'travel_package_carousel_interval_ms',
        'vision_title',
        'vision_content',
        'programs_stat',
        'programs_label',
        'expertise_stat',
        'expertise_label',
        'practice_stat',
        'practice_label',
    ];

    protected $casts = [
        'travel_package_features' => 'array',
        'travel_package_background_images' => 'array',
        'travel_package_background_settings' => 'array',
        'travel_package_carousel_interval_ms' => 'integer',
    ];

    public static function travelPackageBackgroundSettingsKey(string $background): string
    {
        return hash('sha256', $background);
    }

    /**
     * @return array{position_x: int, position_y: int, scale: int}
     */
    public function travelPackageBackgroundSettings(string $background): array
    {
        $settings = $this->travel_package_background_settings[
            self::travelPackageBackgroundSettingsKey($background)
        ] ?? [];

        return [
            'position_x' => max(0, min(100, (int) ($settings['position_x'] ?? 50))),
            'position_y' => max(0, min(100, (int) ($settings['position_y'] ?? 50))),
            'scale' => max(100, min(200, (int) ($settings['scale'] ?? 100))),
        ];
    }

    public function travelPackageCarouselIntervalMilliseconds(): int
    {
        $interval = $this->travel_package_carousel_interval_ms ?: 2500;

        return max(1000, min(60000, $interval));
    }
}
