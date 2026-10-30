<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Show courses catalog (Slide 7 & 8).
     */
    public function index(Request $request): View
    {
        $selectedCategorySlug = $request->query('kategori');
        $searchQuery = $request->query('q');

        $categories = Category::withCount('courses')->get();

        $query = Course::with(['category', 'sessions'])->where('status', 'Publish');

        if ($selectedCategorySlug) {
            $query->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $courses = $query->get();

        return view('courses.index', compact('categories', 'courses', 'selectedCategorySlug', 'searchQuery'));
    }

    /**
     * Show single course detail (Slide 9).
     */
    public function show(string $slug): View
    {
        $course = Course::with(['category', 'sessions' => function ($q) {
            $q->orderBy('start_date', 'asc');
        }])->where('slug', $slug)->firstOrFail();

        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->take(3)
            ->get();
        $summarySession = $course->sessions->firstWhere('status', '!=', 'Penuh')
            ?? $course->sessions->first();

        return view('courses.show', compact('course', 'relatedCourses', 'summarySession'));
    }

    /**
     * Show schedules page (Slide 10).
     */
    public function schedule(Request $request): View
    {
        $selectedCategory = $request->query('kategori');

        $categories = Category::all();

        $sessionsQuery = CourseSession::with(['course.category'])
            ->orderBy('start_date', 'asc');

        if ($selectedCategory) {
            $sessionsQuery->whereHas('course.category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        $sessions = $sessionsQuery->get();

        return view('courses.schedule', compact('categories', 'sessions', 'selectedCategory'));
    }
}
