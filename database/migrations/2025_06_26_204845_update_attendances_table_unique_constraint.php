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
        Schema::table('attendances', function (Blueprint $table) {
            // Drop the existing unique constraint if it exists
            $table->dropUnique(['user_id', 'unit_id', 'attendance_date']); // Change 'user_id' to 'student_id' if you used it before

            // Add the corrected unique constraint
            $table->unique(['student_id', 'unit_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Reverse the changes in down() method
            $table->dropUnique(['student_id', 'unit_id', 'attendance_date']);
            // Re-add the old unique constraint if you want to revert fully
            $table->unique(['user_id', 'unit_id', 'attendance_date']); // Or remove if it wasn't intended
        });
    }
};