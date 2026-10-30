<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryFolder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->where('is_gallery', true)
            ->orderBy('id')
            ->get();
        $requestedCategorySlug = $request->query('bidang');
        $selectedCategorySlug = is_string($requestedCategorySlug)
            && $categories->pluck('slug')->contains($requestedCategorySlug)
            ? $requestedCategorySlug
            : null;

        $foldersQuery = GalleryFolder::query()
            ->where('status', 'Publish')
            ->withCount('photos')
            ->with(['category', 'photos' => fn ($query) => $query->latest()->limit(1)])
            ->latest();

        if ($selectedCategorySlug !== null) {
            $foldersQuery->whereHas('category', fn ($query) => $query->where('slug', $selectedCategorySlug));
        }

        $folders = $foldersQuery->get();
        $foldersByCategory = $folders->whereNotNull('category_id')->groupBy('category_id');
        $uncategorizedFolders = $folders->whereNull('category_id');

        return view('gallery.index', compact('categories', 'foldersByCategory', 'uncategorizedFolders', 'selectedCategorySlug'));
    }

    public function show(GalleryFolder $folder): View
    {
        abort_unless($folder->status === 'Publish', 404);

        $folder->load('category');
        $photos = $folder->photos()
            ->latest('created_at')
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        return view('gallery.show', compact('folder', 'photos'));
    }
}
