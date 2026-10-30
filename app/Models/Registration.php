<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    public const TYPE_PERSONAL = 'personal';

    public const TYPE_CORPORATE = 'corporate';

    protected $guarded = [];

    public function courseSession(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isCorporate(): bool
    {
        return $this->submission_type === self::TYPE_CORPORATE;
    }
}
