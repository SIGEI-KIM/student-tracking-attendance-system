<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    /**
     * Show the form for completing the student profile.
     */
    public function create()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            $student = new Student(['user_id' => $user->id]);
        }

        // Check if profile is complete AND a course is already enrolled
        if ($student->profile_completed && $student->courses()->exists()) {
            return redirect()->route('student.dashboard')->with('info', 'Your profile is already complete and you are enrolled in a course.');
        }

        $courses = Course::all();

        return view('student.profile-complete', compact('user', 'student', 'courses'));
    }

    /**
     * Store or update the student's profile and initial course enrollment.
     */
    // In App\Http\Controllers\Student\StudentProfileController.php

public function store(Request $request)
{
    $user = Auth::user();

    $validatedData = $request->validate([
        'full_name' => 'required|string|max:255',
        'registration_number' => 'required|string|max:255|unique:students,registration_number,' . ($user->student->id ?? 'NULL'),
        'id_number' => 'required|string|max:255|unique:students,id_number,' . ($user->student->id ?? 'NULL'),
        'gender' => 'required|in:Male,Female,Other',
        'course_id' => 'required|exists:courses,id',
        'academic_level_id' => 'nullable|exists:levels,id', // Keep nullable if the dropdown isn't always filled
    ]);

    $student = $user->student()->firstOrCreate(
        ['user_id' => $user->id],
        [
            'full_name' => $validatedData['full_name'],
            'registration_number' => $validatedData['registration_number'],
            'id_number' => $validatedData['id_number'],
            'gender' => $validatedData['gender'],
            // Do NOT set profile_completed here on creation, handle it below with update
        ]
    );

    // Update the student record with all validated data, and explicitly set profile_completed to true
    $student->update([
        'full_name' => $validatedData['full_name'],
        'registration_number' => $validatedData['registration_number'],
        'id_number' => $validatedData['id_number'],
        'gender' => $validatedData['gender'],
        'profile_completed' => true, // Set this on the Student model
    ]);

    // ******************************************************************
    // CRUCIAL STEP: Update the 'profile_completed' field on the User model
    $user->update(['profile_completed' => true]);
    // ******************************************************************

    $courseId = $validatedData['course_id'];
    $levelId = $validatedData['academic_level_id'] ?? null;

    if ($student->courses()->wherePivot('course_id', $courseId)->wherePivot('level_id', $levelId)->exists()) {
        return redirect()->back()->with('error', 'You are already enrolled in this exact course and level.');
    } else {
        $student->courses()->attach($courseId, ['level_id' => $levelId]);
    }

    return redirect()->route('student.dashboard')->with('success', 'Profile completed and course enrolled successfully!');
}
}