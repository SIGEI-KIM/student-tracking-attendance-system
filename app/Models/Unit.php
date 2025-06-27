<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Important
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    // Remove 'lecturer_id' from fillable as it no longer exists on the table
    protected $fillable = ['name', 'code', 'course_id', 'level_id', 'semester_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Define the many-to-many relationship with users (lecturers).
     */
    public function lecturers(): BelongsToMany // <-- This must be 'lecturers' (plural)
{
    return $this->belongsToMany(User::class, 'lecturer_unit', 'unit_id', 'user_id');
}

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}