<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Make sure Carbon is imported if you use scopeActive

class AttendanceCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'unit_id',
        'code',
        'expires_at',
        'is_active',
        'capacity',
        'duration',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id'); // Assuming lecturer is a User model
    }

    /**
     * Get the attendance records associated with the attendance code.
     */
    public function attendances() 
    {
        return $this->hasMany(Attendance::class, 'attendance_code_id');
    }

    // Optional: Scope for active codes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('expires_at', '>', Carbon::now());
    }
}