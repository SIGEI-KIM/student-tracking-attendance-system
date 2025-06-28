<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'id_number',
        'gender',
        'profile_completed',
        'full_name',
    ];

    protected $casts = [
        'profile_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'student_id', 'course_id')
            ->withPivot('level_id', 'semester_id')
            ->withTimestamps();
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'unit_student', 'student_id', 'unit_id')
                    ->withTimestamps();
    }

    /**
     * Get the student's current active course enrollment.
     * This method fetches the first course enrollment found for the student,
     * assuming a student has one primary active enrollment for attendance tracking.
     * It eager loads the pivot data (level_id, semester_id).
     *
     * You might need to refine the logic here if a student can have multiple
     * active enrollments and you need to determine the *specific* one for attendance.
     * For example, by adding an 'is_active' flag to 'course_enrollments' or
     * filtering by current academic year/period.
     *
     * @return \App\Models\Course|null
     */
    public function currentEnrollment(): ?Course
    {
        return $this->courses()->withPivot('level_id', 'semester_id')->first();
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}