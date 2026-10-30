<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GalleryFolder;
use App\Models\GalleryPhoto;
use App\Services\GalleryImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryAdminController extends Controller
{
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')],
            'short_name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);

        $baseSlug = Str::slug($validated['name']) ?: 'bidang-galeri';
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'short_name' => $validated['short_name'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_gallery' => true,
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri'])
            ->with('success', 'Bidang galeri berhasil ditambahkan.');
    }

    public function storeFolder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('is_gallery', true),
            ],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (GalleryFolder::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        $folder = GalleryFolder::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'status' => 'Draft',
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id])
            ->with('success', 'Folder galeri dibuat sebagai draft. Lengkapi pengaturannya lalu tekan Save & Upload.');
    }

    public function updateFolder(Request $request, GalleryFolder $folder): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('is_gallery', true),
            ],
            'status' => ['required', Rule::in(['Publish', 'Draft'])],
        ]);

        $slug = $folder->slug;
        if ($folder->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']) ?: 'folder-galeri';
            $slug = $baseSlug;
            $counter = 1;

            while (GalleryFolder::where('slug', $slug)->where('id', '!=', $folder->id)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
        }

        $folder->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri'])
            ->with('success', $folder->status === 'Draft'
                ? 'Folder galeri disimpan sebagai draft.'
                : 'Pengaturan folder galeri berhasil disimpan.');
    }

    public function destroyFolder(GalleryFolder $folder): RedirectResponse
    {
        $paths = $folder->photos()
            ->get(['path', 'thumbnail_path'])
            ->flatMap(fn (GalleryPhoto $photo): array => array_filter([$photo->path, $photo->thumbnail_path]))
            ->all();
        Storage::disk('public')->delete($paths);
        $folder->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri'])
            ->with('success', 'Folder galeri berhasil dihapus.');
    }

    public function storePhotos(Request $request, GalleryImageService $imageService): RedirectResponse|JsonResponse
    {
        $photos = array_merge(
            $request->file('photos', []) ?? [],
            $request->file('photo_folder', []) ?? [],
        );

        $photos = array_values(array_filter(
            $photos,
            fn (mixed $photo): bool => $photo instanceof UploadedFile && $photo->getError() !== UPLOAD_ERR_NO_FILE,
        ));

        $request->files->set('photos', $photos);

        $validated = $request->validate([
            'gallery_folder_id' => 'required|exists:gallery_folders,id',
            'photos' => 'required|array|min:1|max:100',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp,avif|max:25600',
            'batch_count' => 'sometimes|integer|min:1|max:100',
        ]);

        $folder = GalleryFolder::findOrFail($validated['gallery_folder_id']);
        foreach ($validated['photos'] as $photo) {
            $storedPhoto = $imageService->store($photo, 'gallery/'.$folder->id);
            $folder->photos()->create($storedPhoto);
        }
        $folder->update(['status' => 'Draft']);

        $message = ($validated['batch_count'] ?? count($validated['photos'])).' foto berhasil diunggah.';

        if ($request->expectsJson()) {
            $request->session()->flash('success', $message);

            return response()->json([
                'message' => $message,
                'redirect' => route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]),
            ]);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id])
            ->with('success', $message);
    }

    public function destroyPhotos(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'folder_id' => 'required|integer|exists:gallery_folders,id',
            'photo_ids' => 'required|array|min:1|max:500',
            'photo_ids.*' => 'required|integer|distinct|exists:gallery_photos,id',
        ]);

        $folder = GalleryFolder::findOrFail($validated['folder_id']);
        $photos = $folder->photos()->whereKey($validated['photo_ids'])->get();

        abort_unless($photos->count() === count($validated['photo_ids']), 404);

        Storage::disk('public')->delete(
            $photos->flatMap(fn (GalleryPhoto $photo): array => array_filter([$photo->path, $photo->thumbnail_path]))->all(),
        );
        $photos->each->delete();
        $folder->update(['status' => 'Draft']);

        $count = $photos->count();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id])
            ->with('success', $count.' foto berhasil dihapus.');
    }

    public function updatePhoto(Request $request, GalleryPhoto $photo, GalleryImageService $imageService): RedirectResponse
    {
        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'photo' => 'sometimes|image|mimes:jpg,jpeg,png,webp,avif|max:25600',
        ]);

        $oldPath = null;
        $oldThumbnailPath = null;
        if ($request->hasFile('photo')) {
            $oldPath = $photo->path;
            $oldThumbnailPath = $photo->thumbnail_path;
            $storedPhoto = $imageService->store($request->file('photo'), 'gallery/'.$photo->gallery_folder_id);

            $photo->path = $storedPhoto['path'];
            $photo->thumbnail_path = $storedPhoto['thumbnail_path'];
        }

        $photo->caption = $validated['caption'] ?? $photo->caption;
        $photo->save();
        $photo->folder()->update(['status' => 'Draft']);

        if ($oldPath !== null) {
            Storage::disk('public')->delete(array_filter([$oldPath, $oldThumbnailPath]));
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $photo->gallery_folder_id])
            ->with('success', 'Foto berhasil diperbarui.');
    }

    public function updatePhotos(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'folder_id' => 'required|integer|exists:gallery_folders,id',
            'photo_ids' => 'required|array|min:1|max:500',
            'photo_ids.*' => 'required|integer|distinct|exists:gallery_photos,id',
            'caption' => 'nullable|string|max:255',
        ]);

        $folder = GalleryFolder::findOrFail($validated['folder_id']);
        $photos = $folder->photos()->whereKey($validated['photo_ids'])->get();

        abort_unless($photos->count() === count($validated['photo_ids']), 404);

        $photos->each->update(['caption' => $validated['caption'] ?? null]);
        $folder->update(['status' => 'Draft']);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id])
            ->with('success', $photos->count().' keterangan foto berhasil diperbarui.');
    }
}
