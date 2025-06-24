<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = [
        'name',
        'course_type',
        'abbreviation',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function students(): BelongsToMany
    {
        // This line needs correction for consistency with User model's 'courses'
        return $this->belongsToMany(User::class) // Default pivot name is 'course_user'
            ->withPivot('level_id')
            ->withTimestamps();
    }

    public function lecturer(): BelongsTo
    {
        // This implies Course has a 'lecturer_id' FK directly to 'users' table
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}