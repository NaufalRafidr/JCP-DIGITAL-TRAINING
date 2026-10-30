<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('outcome')->nullable();
            $table->json('syllabus')->nullable();
            $table->string('duration');
            $table->string('level')->default('Basic');
            $table->string('format')->default('Offline');
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('Publish');
            $table->timestamps();
        });

        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('batch_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('location')->default('JCP Learning Studio / Onsite');
            $table->integer('quota')->default(15);
            $table->integer('registered_count')->default(0);
            $table->decimal('price', 12, 2)->default(0);
            $table->string('status')->default('Buka'); // Buka, Segera, Penuh
            $table->timestamps();
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_session_id')->nullable()->constrained('course_sessions')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('organization')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('consent')->default(true);
            $table->string('status')->default('Pending'); // Pending, Follow-up, Confirmed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('course_sessions');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('categories');
    }
};
