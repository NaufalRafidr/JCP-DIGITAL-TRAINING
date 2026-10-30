<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleAdminController extends Controller
{
    /**
     * Simpan artikel baru dari dashboard admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateArticle($request);
        $imagePath = $this->storeImage($validated['image'] ?? null);
        unset($validated['image']);

        Article::create([
            ...$validated,
            'image_path' => $imagePath,
            'slug' => $this->uniqueSlug($validated['title']),
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'Publish' ? today() : null),
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'artikel'])
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Perbarui artikel dari dashboard admin.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $this->validateArticle($request);
        $oldImagePath = null;
        $imagePath = $article->image_path;

        if (isset($validated['image'])) {
            $oldImagePath = $article->image_path;
            $imagePath = $this->storeImage($validated['image']);
        }

        unset($validated['image']);

        $article->update([
            ...$validated,
            'image_path' => $imagePath,
            'slug' => $this->uniqueSlug($validated['title'], $article),
            'published_at' => $validated['published_at'] ?? ($article->published_at ?? ($validated['status'] === 'Publish' ? today() : null)),
        ]);

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.dashboard', ['tab' => 'artikel'])
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Hapus artikel dari dashboard admin.
     */
    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image_path !== null) {
            Storage::disk('public')->delete($article->image_path);
        }

        $article->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'artikel'])
            ->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * @return array{title: string, category: string, excerpt: string, content: string, published_at: ?string, status: string, image?: UploadedFile}
     */
    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:10240',
            'published_at' => 'nullable|date',
            'status' => 'required|in:Publish,Draft',
        ]);
    }

    private function storeImage(?UploadedFile $image): ?string
    {
        if ($image === null) {
            return null;
        }

        $path = $image->store('articles', 'public');

        abort_if($path === false, 500, 'Foto artikel gagal disimpan.');

        return $path;
    }

    private function uniqueSlug(string $title, ?Article $ignore = null): string
    {
        $baseSlug = Str::slug($title) ?: 'artikel';
        $slug = $baseSlug;
        $counter = 1;

        while (Article::where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
