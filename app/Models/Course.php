<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    private const IMAGE_POSITION_PATTERN = '/\A(?:(?:left|center|right|(?:0|[1-9]\d?|100)%)\s+(?:top|center|bottom|(?:0|[1-9]\d?|100)%))\z/';

    protected $guarded = [];

    protected $casts = [
        'syllabus' => 'array',
        'is_featured' => 'boolean',
    ];

    public static function isValidImagePosition(mixed $imagePosition): bool
    {
        return is_string($imagePosition) && preg_match(self::IMAGE_POSITION_PATTERN, $imagePosition) === 1;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        $session = $this->sessions()->where('status', '!=', 'Penuh')->first();
        if ($session && $session->price > 0) {
            return 'Rp '.number_format($session->price, 0, ',', '.');
        }

        return 'Hubungi Admin';
    }
}
