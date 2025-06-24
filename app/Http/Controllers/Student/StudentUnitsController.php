<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // Import Course model
use App\Models\Level;  // Import Level model
use App\Models\Unit;   // Import Unit model

class StudentUnitsController extends Controller
{
    // Important: Parameters Course $course = null, Level $level = null
    // These allow Laravel to inject the Course and Level models if they are in the URL path.
    public function index(Request $request, Course $course = null, Level $level = null)
    {
        $user = Auth::user();

        // --- Basic role and profile completion checks (KEEP THESE) ---
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. You are not a student.');
        }
        if (!$user->profile_completed) {
            return redirect()->route('student.profile.complete')->with('error', 'Please complete your student profile first.');
        }
        // --- END Basic checks ---


        $unitsQuery = $user->units()->with(['course', 'level', 'lecturers']); // Eager load relationships

        // Determine if we are viewing specific units from the dashboard link
        // This is true if $course and $level objects are provided by Route Model Binding
        $isSpecificCourseLevelView = ($course !== null && $level !== null);

        $selectedYear = null;
        $selectedSemester = null;

        if ($isSpecificCourseLevelView) {
            // --- SCENARIO 1: Coming from Dashboard "View Units" link ---
            // Filter strictly by the provided course and level IDs
            $unitsQuery->whereHas('course', function ($query) use ($course) {
                $query->where('id', $course->id);
            })->whereHas('level', function ($query) use ($level) {
                $query->where('id', $level->id);
            });

            // Pre-populate filters based on the specific level, so the dropdowns show the correct values
            $selectedYear = $level->year_number;
            $selectedSemester = $level->semester_number;

        } else {
            // --- SCENARIO 2: Coming from Sidebar "My Units" link (general view with filters) ---
            // Get selected filters from the request (these are from the form dropdowns)
            $selectedYear = $request->input('year');
            $selectedSemester = $request->input('semester');

            // Apply filters if they exist from the form
            if ($selectedYear) {
                $unitsQuery->whereHas('level', function ($query) use ($selectedYear) {
                    $query->where('year_number', $selectedYear);
                });
            }
            if ($selectedSemester) {
                $unitsQuery->whereHas('level', function ($query) use ($selectedSemester) {
                    $query->where('semester_number', $selectedSemester);
                });
            }
        }

        // Get the paginated units after applying all filters
        $units = $unitsQuery->orderBy('name')->paginate(10);

        // Fetch distinct academic years and semesters for the filter dropdowns (always needed for general view,
        // and useful even for specific view if they want to switch filters).
        $availableYears = Level::distinct()->pluck('year_number')->filter()->sort()->toArray();
        $availableSemesters = Level::distinct()->pluck('semester_number')->filter()->sort()->toArray();

        // Pass all necessary data to the view
        return view('student.my_units', compact(
            'units',
            'availableYears',
            'availableSemesters',
            'selectedYear',
            'selectedSemester',
            'course', // Pass these so the view can display specific course/level info
            'level',  // Pass these so the view can display specific course/level info
            'isSpecificCourseLevelView' // Crucial flag for conditional rendering in the view
        ));
    }
}