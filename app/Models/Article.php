<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'slug',
        'category',
        'excerpt',
        'content',
        'image_path',
        'image_url',
        'reading_time',
        'published_at',
        'status',
        'autosave_token',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
