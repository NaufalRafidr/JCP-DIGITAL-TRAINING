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
    ];
}
