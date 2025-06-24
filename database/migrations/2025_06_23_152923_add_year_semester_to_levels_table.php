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
        Schema::table('levels', function (Blueprint $table) {
            // Add year_number (e.g., 1, 2, 3)
            $table->integer('year_number')->nullable()->after('code'); // Make nullable temporarily for existing data

            // Add semester_number (e.g., 1, 2)
            $table->integer('semester_number')->nullable()->after('year_number'); // Make nullable temporarily
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('semester_number');
            $table->dropColumn('year_number');
        });
    }
};