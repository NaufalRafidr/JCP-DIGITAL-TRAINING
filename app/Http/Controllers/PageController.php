<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\GalleryFolder;
use App\Models\JcpProfile;
use App\Models\Registration;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Corporate training page (Slide 12).
     */
    public function corporate(): View
    {
        $categories = Category::all();

        return view('pages.corporate', compact('categories'));
    }

    /**
     * Articles page.
     */
    public function artikel(): View
    {
        $articles = Article::query()
            ->where('status', 'Publish')
            ->where(function ($query) {
                $query->whereNull('published_at')->orWhereDate('published_at', '<=', today());
            })
            ->latest('published_at')
            ->latest('id')
            ->get();

        return view('pages.artikel', compact('articles'));
    }

    /**
     * Article detail page.
     */
    public function artikelDetail(Article $article): View
    {
        abort_unless(
            $article->status === 'Publish'
                && ($article->published_at === null || $article->published_at->lte(today())),
            404,
        );

        return view('pages.artikel-detail', compact('article'));
    }

    /**
     * About page.
     */
    public function tentang(): View
    {
        $profile = JcpProfile::query()->firstOrFail();

        return view('pages.tentang', compact('profile'));
    }

    /**
     * Contact page.
     */
    public function kontak(): View
    {
        return view('pages.kontak');
    }

    /**
     * Privacy policy.
     */
    public function privacy(): View
    {
        return view('pages.privacy');
    }

    /**
     * Terms of service.
     */
    public function terms(): View
    {
        return view('pages.terms');
    }

    /**
     * Admin dashboard preview (Slide 16).
     */
    public function admin(): View
    {
        $jcpProfile = JcpProfile::query()->firstOrFail();
        $categories = Category::all();
        $totalCourses = Course::count();
        $totalSessions = CourseSession::count();
        $totalRegistrations = Registration::count();
        $articles = Article::latest()->get();
        $courses = Course::with('category')->latest()->get();
        $sessions = CourseSession::with('course')->latest()->get();
        $upcomingSessions = CourseSession::query()
            ->with('course:id,title,slug')
            ->withCount('registrations')
            ->whereDate('start_date', '>=', today())
            ->orderBy('start_date')
            ->orderBy('id')
            ->limit(6)
            ->get();
        $selectedRegistrationCourseId = request()->integer('course_id');
        $selectedRegistrationSessionId = request()->integer('session_id');
        $registrationsQuery = Registration::with(['course', 'courseSession'])->latest();

        if ($selectedRegistrationCourseId > 0) {
            $registrationsQuery->where('course_id', $selectedRegistrationCourseId);
        }

        if ($selectedRegistrationSessionId > 0) {
            $registrationsQuery->where('course_session_id', $selectedRegistrationSessionId);
        }

        $recentRegistrations = $registrationsQuery->get();
        $selectedRegistration = null;

        if (request()->query('tab') === 'pendaftaran' && request()->filled('registration_id')) {
            $selectedRegistration = Registration::with(['course', 'courseSession'])
                ->findOrFail(request()->integer('registration_id'));
        }

        $selectedCourse = null;
        $selectedSession = null;
        $selectedArticle = null;

        if (request()->query('tab') === 'program' && request()->filled('course_id')) {
            $selectedCourse = Course::with('category')->findOrFail(request()->integer('course_id'));
        }

        if (request()->query('tab') === 'jadwal' && request()->filled('session_id')) {
            $selectedSession = CourseSession::with('course')->findOrFail(request()->integer('session_id'));
        }

        if (request()->query('tab') === 'artikel' && request()->filled('article_id')) {
            $selectedArticle = Article::findOrFail(request()->integer('article_id'));
        }

        $galleryCategories = $categories;
        $galleryFolders = GalleryFolder::with(['category', 'previewPhoto'])->withCount('photos')->latest()->get();
        $selectedGalleryFolder = null;
        $selectedGalleryPhotos = collect();

        if (request()->query('tab') === 'galeri' && request()->filled('folder_id')) {
            $selectedGalleryFolder = GalleryFolder::with('category')
                ->withCount('photos')
                ->findOrFail(request()->integer('folder_id'));
            $selectedGalleryPhotos = $selectedGalleryFolder->photos()
                ->latest('created_at')
                ->latest('id')
                ->paginate(8)
                ->withQueryString();
        }

        return view('pages.admin', compact('jcpProfile', 'categories', 'totalCourses', 'totalSessions', 'totalRegistrations', 'articles', 'courses', 'sessions', 'upcomingSessions', 'recentRegistrations', 'selectedRegistrationCourseId', 'selectedRegistrationSessionId', 'selectedRegistration', 'selectedCourse', 'selectedSession', 'selectedArticle', 'galleryCategories', 'galleryFolders', 'selectedGalleryFolder', 'selectedGalleryPhotos'));
    }
}
