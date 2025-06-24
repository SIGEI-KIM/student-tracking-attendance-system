<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Course; // Make sure you have a Course model
use App\Models\Level;  // Make sure you have a Level model
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // <-- ADDED THIS LINE

class StudentEnrollmentController extends Controller
{
    public function index()
    {
        // Fetch all available courses
        $courses = Course::all();

        // Fetch all available levels (e.g., Year 1, Year 2, etc.)
        // You might want to filter these based on the student's program or other logic later.
        $levels = Level::all();

        return view('student.enroll.index', compact('courses', 'levels'));
    }

    // You will need a method to handle the actual enrollment submission
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            // Add semester validation if you have a semester field in your form
            // 'semester' => 'required|string|in:Semester 1,Semester 2',
        ]);

        $user = Auth::user();
        $course = Course::find($request->course_id);
        $level = Level::find($request->level_id);

        // Check if student is already enrolled in this course and level
        if ($user->courses()->where('course_id', $course->id)->wherePivot('level_id', $level->id)->exists()) {
             return redirect()->back()->with('warning', 'You are already enrolled in this course for this level.');
        }

        // Attach the course and level to the student (assuming a many-to-many relationship with pivot table)
        // Your pivot table needs 'user_id', 'course_id', and 'level_id'
        $user->courses()->attach($course->id, ['level_id' => $level->id]);

        return redirect()->route('student.dashboard')->with('success', 'Successfully enrolled in ' . $course->name . ' (' . $level->name . ')!');
    }
}