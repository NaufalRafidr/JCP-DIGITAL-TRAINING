<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CourseSession;
use App\Models\JcpProfile;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the homepage based on Slide 1 to 5 specifications.
     */
    public function index(): View
    {
        $jcpProfile = JcpProfile::query()->firstOrFail();
        $categories = Category::query()
            ->withCount(['courses' => fn ($query) => $query->where('status', 'Publish')])
            ->orderBy('id')
            ->get();

        // Ambil sesi jadwal sesuai urutan tanggal pelaksanaan (Slide 10)
        $upcomingSessions = CourseSession::with(['course.category'])
            ->withCount('registrations')
            ->orderBy('start_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('home', compact('categories', 'upcomingSessions', 'jcpProfile'));
    }
}
