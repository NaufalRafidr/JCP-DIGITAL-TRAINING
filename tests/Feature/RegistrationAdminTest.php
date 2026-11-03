<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates a registration status from the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $registration = Registration::create([
        'name' => 'Naufal Rafid',
        'email' => 'naufal@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.registrations.update', $registration), [
        'status' => 'Confirmed',
    ]);

    $response->assertRedirect(route('admin.dashboard', ['tab' => 'pendaftaran']));
    expect($registration->refresh()->status)->toBe('Confirmed');
});

it('rejects a registration status outside the allowed workflow', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $registration = Registration::create([
        'name' => 'Naufal Rafid',
        'email' => 'naufal@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.registrations.update', $registration), [
        'status' => 'Rejected',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('status');
    expect($registration->refresh()->status)->toBe('Pending');
});

it('stores corporate requests with the institution name and shows their WhatsApp template', function () {
    config(['services.whatsapp.admin_number' => '628111222333']);

    $response = $this->post(route('register.store'), [
        'name' => 'Rina',
        'email' => 'rina@company.test',
        'phone' => '081234567890',
        'submission_type' => 'corporate',
        'organization' => 'PT Sinar Abadi',
        'training_area' => 'Digital Marketing',
        'quota_est' => '15 orang',
        'location_pref' => 'Jakarta, November 2026',
        'notes' => 'Fokus pada strategi konten.',
        'consent' => '1',
    ]);

    $registration = Registration::query()->latest('id')->firstOrFail();
    $successResponse = $this->get(route('register.success', ['id' => $registration->id]));

    $response->assertRedirect(route('register.success', ['id' => $registration->id]));
    expect($registration->submission_type)->toBe('corporate')
        ->and($registration->organization)->toBe('PT Sinar Abadi')
        ->and($registration->notes)->toContain('Bidang pelatihan: Digital Marketing');
    $successResponse->assertOk()
        ->assertSee('Permintaan Corporate Berhasil Diterima')
        ->assertSee('https://wa.me/628111222333', false)
        ->assertSee('ID%20registrasi%20saya%20%23JCP-'.str_pad($registration->id, 4, '0', STR_PAD_LEFT), false)
        ->assertSee('Perkenalkan%2C%20saya%20Rina', false)
        ->assertSee('Nama%20instansi%20PT%20Sinar%20Abadi', false);
});

it('does not allow registration for batches that are not open', function () {
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-registration-availability',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Program Availability',
        'slug' => 'program-registration-availability',
        'description' => 'Program untuk menguji status jadwal.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);

    foreach (['Segera', 'Penuh'] as $status) {
        $session = CourseSession::create([
            'course_id' => $course->id,
            'batch_name' => 'Batch '.$status,
            'start_date' => '2030-04-10',
            'end_date' => '2030-04-11',
            'quota' => 15,
            'status' => $status,
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Peserta Tidak Tersedia',
            'email' => 'unavailable-'.$status.'@example.com',
            'phone' => '081234567890',
            'course_id' => $course->id,
            'course_session_id' => $session->id,
            'consent' => '1',
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors('course_session_id');
    }

    $this->get(route('register'))
        ->assertOk()
        ->assertDontSee('Batch Segera')
        ->assertDontSee('Batch Penuh');
});

it('shows the personal registration details in the WhatsApp template', function () {
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi-personal-whatsapp',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Photography Dasar',
        'slug' => 'photography-dasar-whatsapp',
        'description' => 'Pelatihan fotografi dasar.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $session = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 02',
        'start_date' => '2030-03-10',
        'end_date' => '2030-03-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);

    config(['services.whatsapp.admin_number' => '628111222333']);

    $response = $this->post(route('register.store'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone' => '081234567890',
        'course_id' => $course->id,
        'course_session_id' => $session->id,
        'consent' => '1',
    ]);

    $registration = Registration::query()->latest('id')->firstOrFail();
    $successResponse = $this->get(route('register.success', ['id' => $registration->id]));

    $response->assertRedirect(route('register.success', ['id' => $registration->id]));
    $successResponse->assertOk()
        ->assertSee('https://wa.me/628111222333', false)
        ->assertSee('ID%20registrasi%20saya%20%23JCP-'.str_pad($registration->id, 4, '0', STR_PAD_LEFT), false)
        ->assertSee('Perkenalkan%2C%20saya%20Budi%20Santoso', false)
        ->assertSee('Saya%20sudah%20mendaftar%20kelas%20Photography%20Dasar', false)
        ->assertSee('mendaftar%20kelas%20Photography%20Dasar%20pada%20batch%20Batch%2002', false)
        ->assertDontSee('Daftar sekarang');
});

it('shows corporate requests with a distinct dashboard color and label', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $personal = Registration::create([
        'name' => 'Peserta Personal',
        'email' => 'personal@example.com',
        'phone' => '081234567890',
        'submission_type' => 'personal',
        'status' => 'Pending',
    ]);
    $corporate = Registration::create([
        'name' => 'Rina',
        'email' => 'rina@company.test',
        'phone' => '081234567891',
        'organization' => 'PT Sinar Abadi',
        'submission_type' => 'corporate',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'pendaftaran']));

    $response->assertOk()
        ->assertSee('Pendaftaran Personal')
        ->assertSee('Permintaan Corporate')
        ->assertSee('bg-orange-200 text-orange-900 ring-1 ring-orange-300', false)
        ->assertSee('bg-sky-100 text-sky-800 ring-1 ring-sky-200', false)
        ->assertSee('ID registrasi')
        ->assertSee('#JCP-'.str_pad($personal->id, 4, '0', STR_PAD_LEFT))
        ->assertSee('#JCP-'.str_pad($corporate->id, 4, '0', STR_PAD_LEFT))
        ->assertSee($personal->name)
        ->assertSee($corporate->organization);
});

it('shows the complete registration details when a participant is selected', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi-registration-detail',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Photography Dasar',
        'slug' => 'photography-dasar-registration-detail',
        'description' => 'Pelatihan fotografi dasar.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $session = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch Detail 01',
        'start_date' => '2030-03-10',
        'end_date' => '2030-03-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);
    $registration = Registration::create([
        'course_id' => $course->id,
        'course_session_id' => $session->id,
        'name' => 'Peserta Detail',
        'email' => 'detail@example.com',
        'phone' => '081234567890',
        'organization' => 'PT Detail Indonesia',
        'notes' => "Bidang pelatihan: Fotografi\nJumlah peserta: 5 orang\nCatatan tambahan peserta.",
        'consent' => true,
        'status' => 'Follow-up',
        'submission_type' => Registration::TYPE_PERSONAL,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'pendaftaran',
        'registration_id' => $registration->id,
    ]));

    $response->assertOk()
        ->assertSee('Detail pendaftar')
        ->assertSee('Peserta Detail')
        ->assertSee('detail@example.com')
        ->assertSee('081234567890')
        ->assertSee('PT Detail Indonesia')
        ->assertSee('Photography Dasar')
        ->assertSee('Batch Detail 01')
        ->assertSee('Bidang pelatihan: Fotografi')
        ->assertSee('Jumlah peserta: 5 orang')
        ->assertSee('Catatan tambahan peserta.')
        ->assertSee('Follow-up')
        ->assertSee('registration_id='.$registration->id, false)
        ->assertSee('data-registration-url=', false)
        ->assertSee('Tutup')
        ->assertDontSee('Kembali ke daftar');
});

it('filters registrations by the selected course', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Teknologi Informasi',
        'slug' => 'teknologi-informasi',
    ]);
    $firstCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Website Development',
        'slug' => 'website-development',
        'description' => 'Pelatihan pengembangan website.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $secondCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Data Analytics',
        'slug' => 'data-analytics',
        'description' => 'Pelatihan analisis data.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    Registration::create([
        'course_id' => $firstCourse->id,
        'name' => 'Peserta Website',
        'email' => 'website@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);
    Registration::create([
        'course_id' => $secondCourse->id,
        'name' => 'Peserta Data',
        'email' => 'data@example.com',
        'phone' => '081234567891',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'pendaftaran',
        'course_id' => $firstCourse->id,
    ]));

    $response->assertOk();
    $response->assertSee('Peserta Website');
    $response->assertDontSee('Peserta Data');
});

it('filters registrations by the selected batch', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Photography Dasar',
        'slug' => 'photography-dasar',
        'description' => 'Pelatihan dasar fotografi.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $firstSession = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 01',
        'start_date' => '2030-01-10',
        'end_date' => '2030-01-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);
    $secondSession = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 02',
        'start_date' => '2030-02-10',
        'end_date' => '2030-02-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);
    Registration::create([
        'course_id' => $course->id,
        'course_session_id' => $firstSession->id,
        'name' => 'Peserta Batch Satu',
        'email' => 'batch-one@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);
    Registration::create([
        'course_id' => $course->id,
        'course_session_id' => $secondSession->id,
        'name' => 'Peserta Batch Dua',
        'email' => 'batch-two@example.com',
        'phone' => '081234567891',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', [
        'tab' => 'pendaftaran',
        'course_id' => $course->id,
        'session_id' => $firstSession->id,
    ]));

    $response->assertOk();
    $response->assertSee('Peserta Batch Satu');
    $response->assertDontSee('Peserta Batch Dua');
});

it('rejects a batch that does not belong to the selected training program', function () {
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-registration',
    ]);
    $selectedCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Drone Mapping',
        'slug' => 'drone-mapping-registration',
        'description' => 'Pelatihan pemetaan menggunakan drone.',
        'duration' => '3 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $otherCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Digital Marketing',
        'slug' => 'digital-marketing-registration',
        'description' => 'Pelatihan pemasaran digital.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $otherCourseSession = CourseSession::create([
        'course_id' => $otherCourse->id,
        'batch_name' => 'Batch 01',
        'start_date' => '2030-03-10',
        'end_date' => '2030-03-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);

    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => 'Peserta Tidak Sesuai',
        'email' => 'tidak-sesuai@example.com',
        'phone' => '081234567890',
        'course_id' => $selectedCourse->id,
        'course_session_id' => $otherCourseSession->id,
        'consent' => '1',
    ]);

    $response->assertRedirect(route('register'))
        ->assertSessionHasErrors('course_session_id');
    expect(Registration::count())->toBe(0);
});

it('renders batch options with their related training program', function () {
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-registration-form',
    ]);
    $firstCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Drone Mapping',
        'slug' => 'drone-mapping-registration-form',
        'description' => 'Pelatihan pemetaan menggunakan drone.',
        'duration' => '3 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $secondCourse = Course::create([
        'category_id' => $category->id,
        'title' => 'Digital Marketing',
        'slug' => 'digital-marketing-registration-form',
        'description' => 'Pelatihan pemasaran digital.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $firstSession = CourseSession::create([
        'course_id' => $firstCourse->id,
        'batch_name' => 'Batch Drone 01',
        'start_date' => '2030-04-10',
        'end_date' => '2030-04-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);
    $secondSession = CourseSession::create([
        'course_id' => $secondCourse->id,
        'batch_name' => 'Batch Marketing 01',
        'start_date' => '2030-05-10',
        'end_date' => '2030-05-11',
        'quota' => 15,
        'status' => 'Buka',
    ]);

    $response = $this->get(route('register', ['course_id' => $firstCourse->id]));

    $response->assertOk()
        ->assertSee('data-course-id="'.$firstCourse->id.'"', false)
        ->assertSee('data-course-id="'.$secondCourse->id.'"', false)
        ->assertSee($firstSession->batch_name)
        ->assertSee($secondSession->batch_name)
        ->assertSee('filterSessionsByCourse', false);
});

it('shows the actual batch duration on the schedule and course detail pages', function () {
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi-duration-display',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Drone Mapping',
        'slug' => 'drone-mapping-duration-display',
        'description' => 'Pelatihan pemetaan menggunakan drone.',
        'duration' => '18 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 01',
        'start_date' => '2030-06-10',
        'end_date' => '2030-06-12',
        'quota' => 15,
        'status' => 'Buka',
    ]);

    $scheduleResponse = $this->get(route('courses.schedule'));
    $courseResponse = $this->get(route('courses.show', $course->slug));
    $catalogResponse = $this->get(route('courses.index', ['kategori' => $category->slug]));
    $homeResponse = $this->get(route('home'));

    $scheduleResponse->assertOk()
        ->assertSee('⏱ 3 hari', false)
        ->assertDontSee('⏱ 18 hari', false);
    $courseResponse->assertOk()
        ->assertSee('3 hari')
        ->assertDontSee('18 hari');
    $catalogResponse->assertOk()
        ->assertSee('⏱ 3 hari', false)
        ->assertDontSee('⏱ 18 hari', false);
    $homeResponse->assertOk()
        ->assertSee('⏱ 3 hari', false)
        ->assertDontSee('⏱ 18 hari', false);
});

it('shows the actual registration count on the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::create([
        'name' => 'Teknologi',
        'slug' => 'teknologi',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Drone Mapping',
        'slug' => 'drone-mapping',
        'description' => 'Pelatihan pemetaan menggunakan drone.',
        'duration' => '3 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
    ]);
    $session = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 01',
        'start_date' => '2030-01-10',
        'end_date' => '2030-01-12',
        'quota' => 15,
        'registered_count' => 99,
        'status' => 'Buka',
    ]);
    Registration::create([
        'course_id' => $course->id,
        'course_session_id' => $session->id,
        'name' => 'Peserta Aktual',
        'email' => 'aktual@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard', ['tab' => 'dashboard']));

    $response->assertOk()
        ->assertSee('>1</span>', false)
        ->assertSee('/ 15', false)
        ->assertDontSee('>99</span>', false);
});

it('shows remaining quota from actual registrations on the homepage', function () {
    $category = Category::create([
        'name' => 'Fotografi',
        'slug' => 'fotografi-home',
    ]);
    $course = Course::create([
        'category_id' => $category->id,
        'title' => 'Photography Dasar',
        'slug' => 'photography-dasar-home',
        'description' => 'Pelatihan fotografi dasar.',
        'duration' => '2 hari',
        'level' => 'Basic',
        'format' => 'Offline',
        'status' => 'Publish',
        'is_featured' => true,
    ]);
    $session = CourseSession::create([
        'course_id' => $course->id,
        'batch_name' => 'Batch 01',
        'start_date' => '2030-02-10',
        'end_date' => '2030-02-11',
        'quota' => 15,
        'registered_count' => 99,
        'status' => 'Buka',
    ]);
    Registration::create([
        'course_id' => $course->id,
        'course_session_id' => $session->id,
        'name' => 'Peserta Aktual',
        'email' => 'aktual-home@example.com',
        'phone' => '081234567890',
        'status' => 'Pending',
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Sisa Kuota: 14 kursi')
        ->assertDontSee('Sisa Kuota: 0 kursi');
});
