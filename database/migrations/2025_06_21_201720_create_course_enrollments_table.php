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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Assuming 'users' table is for students
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade'); // To store the level at which the student enrolled in this course
            $table->timestamps();

            // Add a unique constraint to prevent a student from enrolling in the same course at the same level multiple times
            $table->unique(['user_id', 'course_id', 'level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};