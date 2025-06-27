<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course; // Make sure Course model is imported
use App\Models\Level;  // Make sure Level model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAcademicController extends Controller
{
    public function index(Request $request) // Inject Request to get filter inputs
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Get all available levels for the filter dropdowns
        $allLevels = Level::all(); // Assuming you have a Level model

        // Get filter inputs from the request
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        // Start with all courses the student is enrolled in
        $enrolledCoursesQuery = $student->courses(); // Get the relationship query builder

        // Apply filters if both year and semester are selected
        if ($selectedYear && $selectedSemester) {
            // Find the level matching the selected year and semester
            $filterLevel = Level::where('year_number', $selectedYear)
                                ->where('semester_number', $selectedSemester)
                                ->first();

            if ($filterLevel) {
                // Filter the enrolled courses by the pivot's level_id
                $enrolledCoursesQuery->wherePivot('level_id', $filterLevel->id);
            } else {
                // If no matching level is found, no courses will match the filter.
                // We can set a message or simply let the query return empty.
                // For now, it will just not apply the level filter if no level matches.
            }
        }

        // Get the filtered enrolled courses
        $enrolledCourses = $enrolledCoursesQuery->get();

        return view('student.academic', compact(
            'student',
            'enrolledCourses',
            'allLevels', // Pass all levels for the filter dropdown
            'selectedYear',
            'selectedSemester'
        ));
    }
}