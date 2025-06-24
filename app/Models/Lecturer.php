<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Add if you use factories

class Lecturer extends Model
{
    use HasFactory; // Use HasFactory trait

    protected $fillable = [
        'user_id',
        'staff_id',
        'department',
        'faculty',
        'specialization'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function units(): BelongsToMany
    {
        // This is the explicit, 6-argument `belongsToMany` definition needed here:
        // 1. `Unit::class`: The related model (units)
        // 2. `'lecturer_unit'`: The name of the pivot table
        // 3. `'user_id'`: The foreign key of *this* model (Lecturer) on the pivot table.
        //    (This refers to `lecturer_unit.user_id`)
        // 4. `'unit_id'`: The foreign key of the *related* model (Unit) on the pivot table.
        //    (This refers to `lecturer_unit.unit_id`)
        // 5. `'user_id'`: The local key of *this* model (Lecturer) that matches the pivot's foreign key.
        //    (This refers to `lecturers.user_id`)
        // 6. `'id'`: The local key of the *related* model (Unit). (This refers to `units.id`)
        return $this->belongsToMany(Unit::class, 'lecturer_unit', 'user_id', 'unit_id', 'user_id', 'id');
    }
}