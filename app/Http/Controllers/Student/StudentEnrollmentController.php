<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Course; // Make sure you have a Course model
use App\Models\Level;  // Make sure you have a Level model
use App\Models\Unit;   // <--- IMPORTANT: Add this line to use the Unit model
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class StudentEnrollmentController extends Controller
{
    public function index()
    {
        // Fetch all available courses
        $courses = Course::all();

        // Fetch all available levels (e.g., Year 1, Year 2, etc.)
        $levels = Level::all();

        return view('student.enroll.index', compact('courses', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
        ]);

        $user = Auth::user();
        $course = Course::find($request->course_id);
        $level = Level::find($request->level_id);

        // Check if student is already enrolled in this exact course and level combination
        if ($user->courses()->where('course_id', $course->id)->wherePivot('level_id', $level->id)->exists()) {
             return redirect()->back()->with('warning', 'You are already enrolled in this course for this level.');
        }

        // --- Step 1: Enroll student in the course/level ---
        // Attach the course and level to the student (using the 'course_enrollments' pivot table)
        // Your User model's `courses()` relationship must correctly use 'course_enrollments' pivot table
        $user->courses()->attach($course->id, ['level_id' => $level->id]);

        // --- Step 2: Automatically enroll student in units for this course and level ---
        // Find all units that belong to the selected course AND level
        $unitsToEnroll = Unit::where('course_id', $course->id)
                             ->where('level_id', $level->id)
                             ->pluck('id') // Get just the IDs of these units
                             ->toArray();

        // Attach these units to the student using the 'unit_user' pivot table
        // The `syncWithoutDetaching` method is good here; it attaches new units
        // without detaching any existing ones the student might already be linked to.
        // If you want to ensure the student ONLY has units for the *current* enrollment:
        // Use $user->units()->sync($unitsToEnroll); -- but this might remove units from other enrollments.
        // For general enrollment, syncWithoutDetaching is safer.
        $user->units()->syncWithoutDetaching($unitsToEnroll);

        return redirect()->route('student.dashboard')->with('success', 'Successfully enrolled in ' . $course->name . ' (' . $level->name . ') and its associated units!');
    }
}