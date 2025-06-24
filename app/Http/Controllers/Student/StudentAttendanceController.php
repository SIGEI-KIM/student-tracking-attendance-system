<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Unit; // Import the Unit model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    /**
     * Show the form for students to mark attendance for a specific unit.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function showMarkAttendanceForm(Unit $unit)
    {
        // Optional: Check if the student is actually enrolled in this unit
        // This is a crucial validation step. You might have a relationship
        // between User, Course, and Unit to verify enrollment.
        // For simplicity, we'll assume the student has access for now,
        // but in a real app, you'd add:
        /*
        $user = Auth::user();
        if (!$user->enrolledUnits->contains($unit->id)) {
            abort(403, 'You are not enrolled in this unit.');
        }
        */

        return view('student.mark_attendance', compact('unit'));
    }

}