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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('grade_type')->nullable(); // e.g., 'Quiz 1', 'Midterm', 'Final Exam', 'Overall'
            $table->decimal('score', 8, 2); // Score obtained
            $table->decimal('max_score', 8, 2)->nullable(); // Maximum possible score
            $table->timestamps();

            // Optional: If you want grades tied to specific assignments
            // $table->foreignId('assignment_id')->nullable()->constrained('assignments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};