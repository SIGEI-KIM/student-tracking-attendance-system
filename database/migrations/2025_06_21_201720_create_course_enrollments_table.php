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
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('set null');

            // Add the semester_id column here
            $table->foreignId('semester_id')
                  ->nullable() // Make it nullable if an enrollment might not immediately have a semester
                  ->constrained('semesters') // Assumes you have a 'semesters' table
                  ->onDelete('set null'); // Or 'restrict', depending on your desired referential integrity

            $table->timestamps();
            $table->unique(['student_id', 'course_id']);
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