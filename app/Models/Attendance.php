<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id',
        'attendance_date',
        'status',
        'marked_at',
        'student_id',
        'attendance_code_id', 
        'lecturer_id', 
    ];

    protected $casts = [
        'attendance_date' => 'datetime',
        'marked_at' => 'datetime', 
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attendanceCode(): BelongsTo 
    {
        return $this->belongsTo(AttendanceCode::class);
    }

    public function lecturer(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}