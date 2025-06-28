<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecturer extends Model
{
    use HasFactory;

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

    /**
     * The units that this lecturer profile is associated with.
     * This now uses the standard 4 arguments for a many-to-many.
     * It assumes 'user_id' in the 'lecturer_unit' pivot table points to the 'id' of the User (who is the lecturer).
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'lecturer_unit', 'user_id', 'unit_id');
    }
}