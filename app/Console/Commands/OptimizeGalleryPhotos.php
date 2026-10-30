<?php

namespace App\Console\Commands;

use App\Models\GalleryPhoto;
use App\Services\GalleryImageService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gallery:optimize-photos')]
#[Description('Resize and compress stored gallery photos')]
class OptimizeGalleryPhotos extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(GalleryImageService $imageService): int
    {
        $optimizedCount = 0;

        foreach (GalleryPhoto::query()->cursor() as $photo) {
            $thumbnailPath = 'gallery/'.$photo->gallery_folder_id.'/thumbnails/'.pathinfo($photo->path, PATHINFO_FILENAME).'.webp';

            if ($imageService->createThumbnail($photo->path, $thumbnailPath)) {
                $photo->update(['thumbnail_path' => $thumbnailPath]);
                $optimizedCount++;
            }
        }

        $this->info($optimizedCount.' foto galeri berhasil dioptimalkan.');

        return self::SUCCESS;
    }
}
