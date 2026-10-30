<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class GalleryImageService
{
    /**
     * Keep gallery images suitable for web display while preserving their format.
     */
    private const THUMBNAIL_MAX_DIMENSION = 1600;

    private const WEBP_QUALITY = 80;

    /**
     * @return array{path: string, thumbnail_path: ?string}
     */
    public function store(UploadedFile $photo, string $directory): array
    {
        $path = $photo->store($directory, 'public');

        if ($path === false) {
            throw new RuntimeException('Foto gagal disimpan.');
        }

        $thumbnailPath = $directory.'/thumbnails/'.pathinfo($path, PATHINFO_FILENAME).'.webp';

        return [
            'path' => $path,
            'thumbnail_path' => $this->createThumbnail($path, $thumbnailPath) ? $thumbnailPath : null,
        ];
    }

    public function createThumbnail(string $sourcePath, string $thumbnailPath): bool
    {
        ini_set('memory_limit', '256M');

        $disk = Storage::disk('public');

        if (! $disk->exists($sourcePath)) {
            return false;
        }

        $source = $this->loadSourceImage($disk, $sourcePath);

        if ($source === false) {
            return false;
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = min(1, self::THUMBNAIL_MAX_DIMENSION / max($sourceWidth, $sourceHeight));
        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        $temporaryPath = tempnam(sys_get_temp_dir(), 'gallery-image-');

        if ($temporaryPath === false) {
            imagedestroy($canvas);
            imagedestroy($source);

            return false;
        }

        try {
            $written = imagewebp($canvas, $temporaryPath, self::WEBP_QUALITY);

            if (! $written) {
                return false;
            }

            $stream = fopen($temporaryPath, 'rb');

            if ($stream === false) {
                return false;
            }

            try {
                return $disk->put($thumbnailPath, $stream);
            } finally {
                fclose($stream);
            }
        } finally {
            imagedestroy($canvas);
            imagedestroy($source);
            unlink($temporaryPath);
        }
    }

    private function loadSourceImage(mixed $disk, string $sourcePath): mixed
    {
        try {
            $localPath = $disk->path($sourcePath);

            if (is_file($localPath)) {
                return match (strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION))) {
                    'jpg', 'jpeg' => imagecreatefromjpeg($localPath),
                    'png' => imagecreatefrompng($localPath),
                    'webp' => imagecreatefromwebp($localPath),
                    'avif' => function_exists('imagecreatefromavif')
                        ? imagecreatefromavif($localPath)
                        : false,
                    default => false,
                };
            }
        } catch (Throwable) {
            // Fall back to the filesystem contents for non-local disks.
        }

        return imagecreatefromstring($disk->get($sourcePath));
    }
}
