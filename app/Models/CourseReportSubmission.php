<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReportSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'course_id',
        'unit_id',
        'file_path',
        'file_name',
        'submitted_at',
        'remarks',
        'is_reviewed', // Ensure this is in fillable if you're mass assigning
        'admin_feedback',
    ];

    // >>> ADD THIS $CASTS PROPERTY <<<
    protected $casts = [
        'submitted_at' => 'datetime',
        'is_reviewed' => 'boolean', // <-- THIS IS THE CRITICAL LINE TO ADD
    ];
    // >>> END ADDITION <<<

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}