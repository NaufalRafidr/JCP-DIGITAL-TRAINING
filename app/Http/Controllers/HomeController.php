<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the homepage based on Slide 1 to 5 specifications.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount(['courses' => fn ($query) => $query->where('status', 'Publish')])
            ->orderBy('id')
            ->get();

        $featuredCourses = Course::with(['category', 'sessions'])
            ->where('is_featured', true)
            ->where('status', 'Publish')
            ->take(6)
            ->get();

        $allCourses = Course::with(['category', 'sessions'])
            ->where('status', 'Publish')
            ->get();

        // Ambil sesi jadwal sesuai urutan tanggal pelaksanaan (Slide 10)
        $upcomingSessions = CourseSession::with(['course.category'])
            ->withCount('registrations')
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('categories', 'featuredCourses', 'allCourses', 'upcomingSessions'));
    }
}
