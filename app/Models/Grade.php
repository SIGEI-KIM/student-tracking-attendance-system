<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'unit_id',
        'grade_type',
        'score',
        'max_score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Optional: If you uncommented assignment_id
    // public function assignment()
    // {
    //     return $this->belongsTo(Assignment::class);
    // }
}