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
        Schema::create('course_report_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null'); // Unit ID is optional
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null'); // Course ID is optional
            $table->string('file_path'); 
            $table->string('file_name'); 
            $table->text('remarks')->nullable(); 
            $table->timestamp('submitted_at')->useCurrent(); 
            $table->boolean('is_reviewed')->default(false); 
            $table->text('admin_feedback')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_report_submissions');
    }
};