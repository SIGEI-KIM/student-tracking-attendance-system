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
        Schema::create('attendance_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique(); // The 6-digit code, ensure uniqueness
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // Which unit this code is for
            $table->foreignId('lecturer_id')->constrained('users')->onDelete('cascade'); // Who generated it (assuming 'users' table for lecturers)
            $table->timestamp('expires_at'); // When the code expires
            $table->boolean('is_active')->default(true); // Can be manually deactivated if needed
            $table->timestamps();

            // Add index for faster lookup
            $table->index(['unit_id', 'lecturer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_codes');
    }
};