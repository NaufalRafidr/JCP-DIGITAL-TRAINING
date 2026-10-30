<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\CourseAdminController;
use App\Http\Controllers\Admin\CourseSessionAdminController;
use App\Http\Controllers\Admin\GalleryAdminController;
use App\Http\Controllers\Admin\JcpProfileAdminController;
use App\Http\Controllers\Admin\RegistrationAdminController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Slide 5: Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Slide 3 & 7: Pelatihan (Catalog) & Detail
Route::get('/pelatihan', [CourseController::class, 'index'])->name('courses.index');
Route::get('/pelatihan/{slug}', [CourseController::class, 'show'])->name('courses.show');

// Slide 3 & 10: Jadwal & Cohort
Route::get('/jadwal', [CourseController::class, 'schedule'])->name('courses.schedule');

// Slide 3 & 12: Layanan B2B Corporate & In-House Training
Route::get('/corporate', [PageController::class, 'corporate'])->name('corporate');

// Slide 3: Content & Info Pages
Route::get('/artikel', [PageController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{article:slug}', [PageController::class, 'artikelDetail'])->name('artikel.show');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{folder:slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');

// Slide 3 & 11: Alur Pendaftaran MVP
Route::get('/daftar', [RegistrationController::class, 'create'])->name('register');
Route::post('/daftar', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/daftar/berhasil/{id}', [RegistrationController::class, 'success'])->name('register.success');

Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('login');
Route::post('/admin/login', [AdminLoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->middleware('admin')->name('admin.logout');

// Slide 3 & 16: Admin Back Office (Slide 16 Dedicated Layout)
Route::middleware('admin')->group(function () {
    Route::get('/admin', [PageController::class, 'admin'])->name('admin.dashboard');
    Route::put('/admin/account', [AdminAccountController::class, 'update'])->name('admin.account.update');
    Route::put('/admin/profile', [JcpProfileAdminController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/articles', [ArticleAdminController::class, 'store'])->name('admin.articles.store');
    Route::put('/admin/articles/{article}', [ArticleAdminController::class, 'update'])->name('admin.articles.update');
    Route::delete('/admin/articles/{article}', [ArticleAdminController::class, 'destroy'])->name('admin.articles.destroy');
    Route::post('/admin/categories', [CourseAdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::post('/admin/courses', [CourseAdminController::class, 'store'])->name('admin.courses.store');
    Route::put('/admin/courses/{course}', [CourseAdminController::class, 'update'])->name('admin.courses.update');
    Route::delete('/admin/courses/{course}/image', [CourseAdminController::class, 'destroyImage'])->name('admin.courses.image.destroy');
    Route::delete('/admin/courses/{course}', [CourseAdminController::class, 'destroy'])->name('admin.courses.destroy');
    Route::post('/admin/sessions', [CourseSessionAdminController::class, 'store'])->name('admin.sessions.store');
    Route::put('/admin/sessions/{session}', [CourseSessionAdminController::class, 'update'])->name('admin.sessions.update');
    Route::delete('/admin/sessions/{session}', [CourseSessionAdminController::class, 'destroy'])->name('admin.sessions.destroy');
    Route::put('/admin/registrations/{registration}', [RegistrationAdminController::class, 'update'])->name('admin.registrations.update');
    Route::post('/admin/gallery/folders', [GalleryAdminController::class, 'storeFolder'])->name('admin.gallery.folders.store');
    Route::put('/admin/gallery/folders/{folder}', [GalleryAdminController::class, 'updateFolder'])->name('admin.gallery.folders.update');
    Route::delete('/admin/gallery/folders/{folder}', [GalleryAdminController::class, 'destroyFolder'])->name('admin.gallery.folders.destroy');
    Route::post('/admin/gallery/photos', [GalleryAdminController::class, 'storePhotos'])->name('admin.gallery.photos.store');
    Route::put('/admin/gallery/photos/bulk', [GalleryAdminController::class, 'updatePhotos'])->name('admin.gallery.photos.bulk-update');
    Route::put('/admin/gallery/photos/{photo}', [GalleryAdminController::class, 'updatePhoto'])->name('admin.gallery.photos.update');
    Route::delete('/admin/gallery/photos', [GalleryAdminController::class, 'destroyPhotos'])->name('admin.gallery.photos.destroy');
});

// Legal & FAQ
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [PageController::class, 'terms'])->name('terms');
