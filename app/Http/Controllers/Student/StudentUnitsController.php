<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Level; // Make sure to import Level model
use App\Models\Course; // Keep if needed for other parts, but not for year filter from levels
use App\Models\Semester; // Keep if your 'semester' filter uses Semester model directly, otherwise remove.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentUnitsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Basic role and profile completion checks (important to keep)
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. You are not a student.');
        }
        if (!$user->profile_completed) {
            return redirect()->route('student.profile.complete')->with('error', 'Please complete your student profile first.');
        }

        // Fetch distinct academic years from the 'levels' table using the new 'year_number' column
        $availableYears = Level::distinct('year_number')->pluck('year_number')->sortDesc();

        // Fetch distinct semester numbers from the 'levels' table using the new 'semester_number' column
        $availableSemesters = Level::distinct('semester_number')->pluck('semester_number')->sort();

        // Get selected filters from the request
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        // Start building the query for units the student is enrolled in
        // Eager load level to access its year_number and semester_number
        $unitsQuery = $user->units()->with('level');

        // Apply filters if they exist
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

        // Get the paginated units
        $units = $unitsQuery->paginate(10);

        // Pass the data to the view
        return view('student.my_units', compact('units', 'availableYears', 'availableSemesters', 'selectedYear', 'selectedSemester'));
    }
}