<?php

// app/Http/Controllers/Student/DashboardController.php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Unit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        // Get courses with their pivot data (level_id)
        $enrolledCourses = $student->courses()->withPivot('level_id')->get();
        
        // Load the level for each course
        $enrolledCourses->each(function ($course) {
            $course->level = Level::find($course->pivot->level_id);
        });
        
        return view('student.dashboard', compact('enrolledCourses'));
    }


    public function selectCourse()
    {
        $courses = Course::all();
        $levels = Level::all();
        return view('student.select-course', compact('courses', 'levels'));
    }

    public function enrollCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
        ]);

        $student = auth()->user();
        
        // Check if already enrolled
        if ($student->courses()->where('course_id', $request->course_id)->exists()) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        $student->courses()->attach($request->course_id, ['level_id' => $request->level_id]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Course enrolled successfully.');
    }

    public function viewUnits($courseId, $levelId)
    {
        $units = Unit::where('course_id', $courseId)
            ->where('level_id', $levelId)
            ->with('lecturers')
            ->get();

        return view('student.units', compact('units'));
    }
}
