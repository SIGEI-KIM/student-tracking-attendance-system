<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level; // Make sure Level model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    /**
     * Display the enrollment form.
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $enrolledCourseIds = $student->courses->pluck('id')->toArray();
        $availableCourses = Course::whereNotIn('id', $enrolledCourseIds)->get();

        // No need to pass 'levels' to the view anymore if student doesn't select it here
        return view('student.enroll', compact('availableCourses'));
    }

    /**
     * Handle the course enrollment.
     */
    public function store(Request $request)
    {
        // IMPORTANT: Validate only course_id. Level will be assigned automatically.
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Prevent re-enrollment if already enrolled in this course
        if ($student->courses()->where('course_id', $request->course_id)->exists()) {
            return redirect()->route('student.dashboard')->with('info', 'You are already enrolled in this course.');
        }

        // --- AUTOMATICALLY ASSIGN DEFAULT LEVEL ---
        $defaultLevel = Level::where('year_number', 1) // Assuming 'year_number' column exists
                             ->where('semester_number', 1) // Assuming 'semester_number' column exists
                             ->first();

        if (!$defaultLevel) {
            // Handle case where default level isn't configured in your database
            return redirect()->back()->with('error', 'Default academic level (Year 1, Semester 1) not found. Please contact administration.');
        }

        // Attach the course with the automatically assigned level_id
        $student->courses()->attach($request->course_id, [
            'level_id' => $defaultLevel->id,
            'user_id' => $user->id, // Explicitly provide user_id if your pivot uses it
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Successfully enrolled in the course!');
    }
}