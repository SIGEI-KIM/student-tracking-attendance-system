<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // The unit for which attendance is marked
            $table->date('attendance_date'); // The specific date for this attendance record
            $table->enum('status', ['present', 'absent'])->default('absent'); // 'present' if marked, 'absent' otherwise
            $table->timestamp('marked_at')->nullable(); // When the attendance was explicitly marked (if present)
            $table->timestamps(); // created_at (when record was made), updated_at

            // Ensure a student can only have one attendance record per unit per day
            $table->unique(['user_id', 'unit_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};