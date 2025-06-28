<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Unit; 

class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Get all units the student is enrolled in
        // Eager load the 'grades' relationship for the current student and 'course' for unit details
        $enrolledUnits = $student->units() // Use the relationship defined in Student model
                                 ->with(['grades' => function($query) use ($student) {
                                     $query->where('student_id', $student->id)
                                           ->orderBy('grade_type'); // Or order by score, or date
                                 }])
                                 ->get();

        // If you want to show a consolidated overall grade per unit,
        // you might need additional logic here or in your Grade model/relationships
        // to determine which 'grade_type' represents the 'overall' grade.

        return view('student.grades.index', compact('student', 'enrolledUnits'));
    }
}