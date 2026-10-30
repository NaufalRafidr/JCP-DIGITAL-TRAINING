<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        $publishedAt = $validated['published_at'] ?? ($validated['status'] === 'Publish' ? today()->toDateString() : null);

        if ($validated['status'] === 'Publish' && $publishedAt !== null && $publishedAt > today()->toDateString()) {
            $validated['status'] = 'Draft';
        }

        $this->ensurePublicationDateAvailable($publishedAt);

        Article::create([
            ...$validated,
            'image_path' => $imagePath,
            'image_url' => $imagePath !== null ? null : ($validated['image_url'] ?? null),
            'slug' => $this->uniqueSlug($validated['title']),
            'published_at' => $publishedAt,
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
        $validated = $this->validateArticle($request, $article);
        $oldImagePath = null;
        $imagePath = $article->image_path;
        $imageUrl = $article->image_url;

        if (isset($validated['image'])) {
            $oldImagePath = $article->image_path;
            $imagePath = $this->storeImage($validated['image']);
            $imageUrl = null;
        } elseif (array_key_exists('image_url', $validated) && $validated['image_url'] !== null) {
            $oldImagePath = $article->image_path;
            $imagePath = null;
            $imageUrl = $validated['image_url'];
        }

        unset($validated['image']);

        $publishedAt = $validated['status'] === 'Publish'
            ? ($validated['published_at'] ?? today()->toDateString())
            : ($validated['published_at'] ?? null);

        if ($validated['status'] === 'Publish' && $publishedAt > today()->toDateString()) {
            $validated['status'] = 'Draft';
        }

        $this->ensurePublicationDateAvailable($publishedAt, $article);

        $article->update([
            ...$validated,
            'image_path' => $imagePath,
            'image_url' => $imageUrl,
            'slug' => $this->uniqueSlug($validated['title'], $article),
            'published_at' => $publishedAt,
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
     * @return array{title: string, author: ?string, category: string, excerpt: string, content: string, published_at: ?string, status: string, image_url: ?string, image?: UploadedFile}
     */
    private function validateArticle(Request $request, ?Article $article = null): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:10240',
            'image_url' => 'nullable|url|max:2048',
            'published_at' => 'nullable|date|date_format:Y-m-d',
            'status' => 'required|in:Publish,Draft,Terjadwalkan',
        ]);

        if ($validated['status'] === 'Terjadwalkan') {
            if (($validated['published_at'] ?? null) === null) {
                throw ValidationException::withMessages([
                    'published_at' => 'Isi tanggal tayang untuk menjadwalkan artikel.',
                ]);
            }

            $validated['status'] = 'Draft';
        }

        $validated['content'] = $this->sanitizeRichText($validated['content']);

        return $validated;
    }

    private function ensurePublicationDateAvailable(?string $publishedAt, ?Article $ignore = null): void
    {
        if ($publishedAt === null) {
            return;
        }

        $query = Article::query()->whereDate('published_at', $publishedAt);

        if ($ignore !== null) {
            $query->whereKeyNot($ignore->getKey());
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'published_at' => 'Maksimal satu artikel dapat dijadwalkan atau dipublikasikan dalam satu hari.',
            ]);
        }
    }

    private function sanitizeRichText(string $content): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $previousErrorMode = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div>'.$content.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorMode);

        $root = $document->documentElement;
        if (! $root instanceof \DOMElement) {
            return e($content);
        }

        $this->sanitizeRichTextNode($root);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        return trim($result);
    }

    private function sanitizeRichTextNode(\DOMNode $node): void
    {
        $allowedTags = ['a', 'b', 'blockquote', 'br', 'em', 'h2', 'h3', 'i', 'li', 'ol', 'p', 'strong', 'u', 'ul'];

        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMElement) {
                if (! in_array(strtolower($child->tagName), $allowedTags, true)) {
                    $parent = $child->parentNode;
                    if ($parent === null) {
                        continue;
                    }

                    while ($child->firstChild !== null) {
                        $parent->insertBefore($child->firstChild, $child);
                    }

                    $parent->removeChild($child);

                    continue;
                }

                foreach (iterator_to_array($child->attributes) as $attribute) {
                    if ($child->tagName !== 'a' || $attribute->name !== 'href') {
                        $child->removeAttribute($attribute->name);
                    }
                }

                if ($child->tagName === 'a') {
                    $href = trim($child->getAttribute('href'));
                    $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

                    if (! in_array($scheme, ['http', 'https', 'mailto'], true)) {
                        $child->removeAttribute('href');
                    } else {
                        $child->setAttribute('rel', 'noopener noreferrer');
                        $child->setAttribute('target', '_blank');
                    }
                }

                $this->sanitizeRichTextNode($child);
            }
        }
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
