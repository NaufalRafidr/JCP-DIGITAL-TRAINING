<?php

namespace Tests\Feature;

use App\Models\Course;
use Tests\TestCase;

class WebsiteBlueprintTest extends TestCase
{
    public function test_homepage_loads_successfully_with_slide_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('JCP TRAINING');
        $response->assertSee('Skill baru, peluang baru');
        $response->assertSee('Praktik langsung • mentor berpengalaman');
        $response->assertSee('UPGRADE YOUR SKILLS');
        $response->assertSee('15 PROGRAM');
        $response->assertSee('5 BIDANG');
        $response->assertSee('Pilih bidang pelatihan', false);
        $response->assertSee('Jadwal pelatihan terdekat');
        $response->assertSee('Website training sebagai mesin akuisisi peserta');
        $response->assertSee('TEMUKAN');
        $response->assertSee('PAHAMI');
        $response->assertSee('DAFTAR');
    }

    public function test_catalog_page_shows_courses(): void
    {
        $course = Course::with('category')->firstOrFail();
        $response = $this->get(route('courses.index', ['kategori' => $course->category->slug]));

        $response->assertStatus(200);
        $response->assertSee($course->category->name);
        $response->assertDontSee('Semua Pelatihan');
    }

    public function test_course_detail_page_loads(): void
    {
        $course = Course::first();
        $response = $this->get('/pelatihan/'.$course->slug);
        $response->assertStatus(200);
        $response->assertSee($course->title);
        $response->assertSee('Yang akan dipelajari');
        $response->assertSee('Ilustrasi kegiatan');
        $response->assertDontSee('RINGKASAN KELAS');
    }

    public function test_schedule_page_loads(): void
    {
        $response = $this->get('/jadwal');
        $response->assertStatus(200);
        $response->assertSee('Jadwal Pelatihan');
    }

    public function test_corporate_page_loads(): void
    {
        $response = $this->get('/corporate');
        $response->assertStatus(200);
        $response->assertSee('Pelatihan sesuai kebutuhan tim Anda.');
    }

    public function test_registration_form_and_submission(): void
    {
        $course = Course::first();

        $formResponse = $this->get('/daftar');
        $formResponse->assertStatus(200);
        $formResponse->assertSee('Form Peserta');

        $submitResponse = $this->post('/daftar', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'course_id' => $course->id,
            'consent' => '1',
        ]);

        $submitResponse->assertRedirect();
    }
}
