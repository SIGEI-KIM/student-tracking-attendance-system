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
        Schema::table('courses', function (Blueprint $table) {
            // Add the academic_year column
            // It's usually an integer (e.g., 2023, 2024)
            // You might want to make it nullable initially if you have existing data
            // that doesn't have an academic_year yet, and then update it.
            $table->integer('academic_year')->nullable()->after('abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the academic_year column if the migration is rolled back
            $table->dropColumn('academic_year');
        });
    }
};