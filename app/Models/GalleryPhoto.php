<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    protected $guarded = [];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(GalleryFolder::class, 'gallery_folder_id');
    }
}
