<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'level_id',
        'full_name',
        'registration_number',
        'id_number',
        'gender',
        'profile_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => Role::class,
        'profile_completed' => 'boolean',
        'password' => 'hashed',
    ];

    // Existing relationships...
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'user_id', 'course_id')
            ->withPivot('level_id')
            ->withTimestamps();
    }

    public function teachingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // *** NEW: Relationship for units directly enrolled by this user (student) ***
    // Assuming a pivot table named 'unit_user' or 'enrollments'
    public function units(): BelongsToMany
    {
        // Using 'unit_user' as a common pivot table name between Unit and User
        // If your pivot table is different (e.g., 'student_unit'), adjust accordingly.
        return $this->belongsToMany(Unit::class, 'unit_user', 'user_id', 'unit_id')
                    ->withTimestamps(); // Optional: if you track when enrollment happened
    }


    // Existing role checks...
    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isLecturer(): bool
    {
        return $this->hasRole(Role::LECTURER);
    }

    public function isStudent(): bool
    {
        return $this->hasRole(Role::STUDENT);
    }
}