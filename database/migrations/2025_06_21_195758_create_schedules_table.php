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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('unit_id')->constrained()->onDelete('cascade');
        $table->tinyInteger('day_of_week_numeric') 
              ->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
        $table->time('start_time');
        $table->time('end_time');
        $table->timestamps();

        // Add a unique constraint to prevent duplicate schedules for the same unit on the same day
        $table->unique(['unit_id', 'day_of_week_numeric']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
