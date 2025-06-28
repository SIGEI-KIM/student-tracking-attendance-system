<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Make sure this is imported
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

    /**
     * Get the student profile associated with the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the courses taught by this lecturer (if this user is a lecturer).
     * Consider if this relationship is still needed if `units()` covers unit assignments,
     * or if courses are collections of units and lecturers teach units.
     */
    public function teachingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }

    public function generatedAttendanceCodes(): HasMany
    {
        return $this->hasMany(AttendanceCode::class, 'lecturer_id');
    }

    /**
     * Get the lecturer profile associated with the user.
     * This allows you to store additional lecturer-specific data.
     * The `units()` relationship will now be directly on this User model.
     */
    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Removed: This relationship is generally incorrect for a generic User model.
     * If a student belongs to a course, that relationship should be on the Student model.
     * public function course()
     * {
     * return $this->belongsTo(Course::class);
     * }
     */

    /**
     * The units that this User (who is a lecturer) teaches.
     * This is where you would put the relationship if you want to access units directly
     * from the User model (e.g., $user->units).
     */
    public function units(): BelongsToMany
    {
        // This assumes a many-to-many relationship via the 'lecturer_unit' pivot table.
        // 'user_id' is the foreign key for the User (lecturer) in the pivot table.
        // 'unit_id' is the foreign key for the Unit in the pivot table.
        return $this->belongsToMany(Unit::class, 'lecturer_unit', 'user_id', 'unit_id');
    }


    // Role checks remain on the User model
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