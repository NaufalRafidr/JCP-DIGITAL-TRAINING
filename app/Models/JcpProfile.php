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
        'vision_title',
        'vision_content',
        'programs_stat',
        'programs_label',
        'expertise_stat',
        'expertise_label',
        'practice_stat',
        'practice_label',
    ];
}
