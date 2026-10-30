<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseAdminController extends Controller
{
    /**
     * Simpan bidang pelatihan baru dari dashboard admin.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')],
            'short_name' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $baseSlug = Str::slug($validated['name']) ?: 'bidang-pelatihan';
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
            ->route('admin.dashboard', ['tab' => 'bidang'])
            ->with('success', 'Bidang pelatihan berhasil ditambahkan dan tersedia di Beranda serta Galeri.');
    }

    /**
     * Hapus bidang pelatihan beserta data program yang dimilikinya.
     */
    public function destroyCategory(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'bidang'])
            ->with('success', 'Bidang pelatihan "'.$name.'" berhasil dihapus.');
    }

    /**
     * Hitung durasi otomatis dari hari ini ke tanggal program berjalan.
     */
    private function calculateAutomaticDuration(?string $startDate): string
    {
        if (empty($startDate)) {
            return '2 hari'; // fallback default jika tanggal tidak diisi
        }

        $today = Carbon::today();
        $start = Carbon::parse($startDate)->startOfDay();
        $daysDiff = $today->diffInDays($start, false);

        if ($daysDiff <= 0) {
            return 'Hari ini (1 hari)';
        } elseif ($daysDiff == 1) {
            return 'Besok (1 hari)';
        } else {
            return $daysDiff.' hari';
        }
    }

    /**
     * Simpan program pelatihan baru dari Dashboard Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'nullable|date',
            'facilities' => 'nullable|string|max:1000',
            'level' => 'required|string|max:50',
            'format' => 'required|string|max:100',
            'description' => 'required|string',
            'outcome' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:10240',
            'image_url' => 'nullable|url|max:2048',
            'image_position' => $this->imagePositionRules(),
            'remove_image' => ['nullable', 'boolean'],
            'status' => 'required|in:Publish,Draft',
        ]);

        // Buat slug otomatis dan pastikan unik
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        // Parse syllabus baris per baris menjadi array JSON
        $syllabusArray = null;
        if (! empty($validated['syllabus'])) {
            $syllabusArray = array_values(array_filter(array_map('trim', explode("\n", $validated['syllabus']))));
        }

        $imagePath = $this->storeImage($validated['image'] ?? null);
        unset($validated['image']);

        // Hitung durasi otomatis dari tanggal hari ini hingga tanggal program berjalan
        $duration = $this->calculateAutomaticDuration($validated['start_date'] ?? null);

        $course = Course::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'duration' => $duration,
            'facilities' => $validated['facilities'] ?? null,
            'level' => $validated['level'],
            'format' => $validated['format'],
            'description' => $validated['description'],
            'outcome' => $validated['outcome'] ?? null,
            'syllabus' => $syllabusArray,
            'image' => $imagePath,
            'image_url' => $imagePath !== null ? null : ($validated['image_url'] ?? null),
            'image_position' => $validated['image_position'] ?? 'center center',
            'status' => $validated['status'],
        ]);

        // Jika tanggal mulai diisi, otomatis buatkan Batch 01 awal
        if (! empty($validated['start_date'])) {
            $course->sessions()->create([
                'batch_name' => 'Batch 01 - '.Carbon::parse($validated['start_date'])->format('M Y'),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['start_date'],
                'location' => 'JCP Learning Studio / Onsite',
                'quota' => 15,
                'status' => 'Buka',
            ]);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'program'])
            ->with('success', 'Program "'.$validated['title'].'" berhasil ditambahkan!');
    }

    /**
     * Update program pelatihan.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'nullable|date',
            'facilities' => 'nullable|string|max:1000',
            'level' => 'required|string|max:50',
            'format' => 'required|string|max:100',
            'description' => 'required|string',
            'outcome' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:10240',
            'image_url' => 'nullable|url|max:2048',
            'image_position' => $this->imagePositionRules(),
            'remove_image' => ['nullable', 'boolean'],
            'status' => 'required|in:Publish,Draft',
        ]);

        $syllabusArray = null;
        if (! empty($validated['syllabus'])) {
            $syllabusArray = array_values(array_filter(array_map('trim', explode("\n", $validated['syllabus']))));
        }

        $oldImagePath = null;
        $imagePath = $course->image;
        $imageUrl = $course->image_url;
        $hasNewImage = isset($validated['image']);
        if ($hasNewImage) {
            $oldImagePath = $course->image;
            $imagePath = $this->storeImage($validated['image']);
            $imageUrl = null;
        } elseif (array_key_exists('image_url', $validated) && $validated['image_url'] !== null) {
            $oldImagePath = $course->image;
            $imagePath = null;
            $imageUrl = $validated['image_url'];
        } elseif ($request->boolean('remove_image') && $course->image !== null) {
            $oldImagePath = $course->image;
            $imagePath = null;
            $imageUrl = null;
        }
        unset($validated['image']);

        $duration = $course->duration;
        if (! empty($validated['start_date'])) {
            $duration = $this->calculateAutomaticDuration($validated['start_date']);
        }

        $course->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'duration' => $duration,
            'facilities' => $validated['facilities'] ?? null,
            'level' => $validated['level'],
            'format' => $validated['format'],
            'description' => $validated['description'],
            'outcome' => $validated['outcome'] ?? null,
            'syllabus' => $syllabusArray,
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'image_position' => $hasNewImage
                ? ($validated['image_position'] ?? '50% 50%')
                : ($request->boolean('remove_image') ? '50% 50%' : ($validated['image_position'] ?? $course->image_position ?? '50% 50%')),
            'status' => $validated['status'],
        ]);

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'program'])
            ->with('success', 'Program "'.$validated['title'].'" berhasil diperbarui!');
    }

    /**
     * Hapus program pelatihan.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $title = $course->title;
        $course->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'program'])
            ->with('success', 'Program "'.$title.'" berhasil dihapus.');
    }

    /**
     * Hapus gambar program tanpa mengubah pengaturan program lainnya.
     */
    public function destroyImage(Course $course): JsonResponse
    {
        $imagePath = $course->image;

        $course->update([
            'image' => null,
            'image_url' => null,
            'image_position' => '50% 50%',
        ]);

        if ($imagePath !== null) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json([
            'message' => 'Gambar program berhasil dihapus.',
        ]);
    }

    private function storeImage(?UploadedFile $image): ?string
    {
        if ($image === null) {
            return null;
        }

        $path = $image->store('courses', 'public');

        abort_if($path === false, 500, 'Gambar program gagal disimpan.');

        return $path;
    }

    /**
     * @return list<mixed>
     */
    private function imagePositionRules(): array
    {
        return [
            'nullable',
            'string',
            'max:30',
            function (string $attribute, mixed $value, Closure $fail): void {
                if (! Course::isValidImagePosition($value)) {
                    $fail('Posisi gambar tidak valid.');
                }
            },
        ];
    }
}
