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
            $table->integer('capacity')->after('duration')->nullable(); // Add after duration, can be nullable if not always enforced
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_codes', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
};