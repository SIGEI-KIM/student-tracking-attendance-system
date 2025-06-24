<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'day_of_week_numeric',
        'start_time',
        'end_time',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}