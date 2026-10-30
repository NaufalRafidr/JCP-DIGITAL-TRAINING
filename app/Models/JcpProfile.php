<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JcpProfile extends Model
{
    protected $fillable = [
        'organization_name',
        'tagline',
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
