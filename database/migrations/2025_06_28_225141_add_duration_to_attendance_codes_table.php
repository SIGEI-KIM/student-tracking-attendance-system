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
        Schema::table('attendance_codes', function (Blueprint $table) {
            // Add the 'duration' column as an integer, defaulting to 60 minutes, after 'capacity'
            $table->integer('duration')->default(60)->after('capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_codes', function (Blueprint $table) {
            // Drop the 'duration' column if the migration is rolled back
            $table->dropColumn('duration');
        });
    }
};