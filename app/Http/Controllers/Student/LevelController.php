<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Level;
use App\Models\Course;

class LevelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.profile.complete')->with('error', 'Please complete your profile first.');
        }

        // --- CHANGE HERE: Remove ->with('levels') ---
        // Fetch enrolled courses and include the pivot table's level_id
        $enrolledCourses = $student->courses()->withPivot('level_id')->get();

        $availableLevels = Level::all(); // Fetch all available academic levels

        return view('student.manage_levels', compact('enrolledCourses', 'availableLevels'));
    }

    public function updateLevel(Request $request, Course $course)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
        ]);

        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', 'Student profile not found.');
        }

        // Check if the student is actually enrolled in this course
        $isEnrolled = $student->courses()->where('course_id', $course->id)->exists();

        if (!$isEnrolled) {
            return back()->with('error', 'You are not enrolled in this course.');
        }

        // Update the pivot table with the new level_id
        $student->courses()->updateExistingPivot($course->id, ['level_id' => $request->level_id]);

        return back()->with('success', 'Academic level updated successfully for ' . $course->name . '.');
    }
}