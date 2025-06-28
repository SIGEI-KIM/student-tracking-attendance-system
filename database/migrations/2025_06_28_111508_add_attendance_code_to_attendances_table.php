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
            $table->foreignId('attendance_code_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lecturer_id')->nullable()->constrained('users')->onDelete('set null'); // Assuming users table for lecturers
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('attendance_code_id');
            $table->dropConstrainedForeignId('lecturer_id');
        });
    }
};