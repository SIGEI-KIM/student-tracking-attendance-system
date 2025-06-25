<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Unit;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.profile.complete')->with('error', 'Please complete your student profile.');
        }

        $enrolledCourses = $student->courses()->withPivot('level_id')->get();

        $enrolledCourses->each(function ($course) {
            if ($course->pivot && $course->pivot->level_id) {
                $course->level = Level::find($course->pivot->level_id);
            } else {
                $course->level = null;
            }
        });

        $primaryEnrolledCourse = $enrolledCourses->first();

        return view('student.dashboard', compact('student', 'enrolledCourses', 'primaryEnrolledCourse'));
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

        $studentUser = auth()->user();
        $student = $studentUser->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found. Please complete your profile first.');
        }

        if ($student->courses()->wherePivot('course_id', $request->course_id)->wherePivot('level_id', $request->level_id)->exists()) {
            return redirect()->back()->with('error', 'You are already enrolled in this exact course and level.');
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
            ->orderBy('name')
            ->get();

        return view('student.units', compact('units'));
    }
}