<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentUnitsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Initialize variables for the view
        $units = collect(); // Default to empty collection
        $message = null;   // Default message to null

        // Get filter parameters from the request
        $selectedCourseId = $request->input('course_id');
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        // Fetch all levels and courses for filter dropdowns (always needed)
        $allCourses = Course::all();
        $allLevels = Level::all();


        // --- Determine Student's Enrollment Status for Informational Message ONLY ---
        $enrolledCourse = $student->courses()->first(); // Get primary enrolled course with pivot data
        $currentLevelForEnrolledCourse = null;

        if ($enrolledCourse) {
            // If the course is found, check if a level_id is assigned in the pivot table
            if ($enrolledCourse->pivot->level_id) {
                $currentLevelForEnrolledCourse = Level::find($enrolledCourse->pivot->level_id);
            }
        }

        // Set informational message based on student's enrollment status
        // This message will be displayed, but will NOT prevent the unit query below from running.
        if (!$enrolledCourse) {
            $message = 'You are not yet enrolled in any course. Please enroll to view units.';
        } elseif (!$currentLevelForEnrolledCourse) {
            $message = 'Your academic level for ' . ($enrolledCourse->name ?? 'your enrolled course') . ' has not yet been assigned by administration. Please contact them.';
        }


        // --- Build the Units Query based purely on filters (THIS BLOCK IS NOW ALWAYS EXECUTED) ---
        $query = Unit::query();

        // Filter by Course if selected in the dropdown
        if ($selectedCourseId) {
            // Using 'course' (singular) here assuming your Unit model has a belongsTo relationship named 'course'.
            // If your Unit model truly has a many-to-many relationship named 'courses', then use 'courses'.
            $query->whereHas('course', function ($q) use ($selectedCourseId) {
                $q->where('id', $selectedCourseId);
            });
        }

        // Filter by Year and Semester if both selected in the dropdowns
        if ($selectedYear && $selectedSemester) {
            $filterLevel = Level::where('year_number', $selectedYear)
                                ->where('semester_number', $selectedSemester)
                                ->first();

            if ($filterLevel) {
                // Using 'level' (singular) here assuming your Unit model has a belongsTo relationship named 'level'.
                // If your Unit model truly has a many-to-many relationship named 'levels', then use 'levels'.
                $query->whereHas('level', function ($q) use ($filterLevel) {
                    $q->where('id', $filterLevel->id);
                });
            } else {
                // If the selected Year/Semester combination doesn't exist as a Level,
                // ensure the query returns no units to prevent showing irrelevant units.
                $query->whereRaw('1 = 0'); // A common trick to return an empty set
                // Optionally set a message for invalid filter combination
                if (is_null($message)) { // Don't override more critical messages
                     $message = 'The selected Year/Semester combination does not exist. Please refine your filter.';
                }
            }
        }

        // Get the units after applying all filters
        // Eager load singular relationships 'course', 'level', and 'lecturer'.
        $units = $query->with(['course', 'level', 'lecturer'])->get();


        // --- Final message check if no units were found after filtering ---
        // This message should only appear if NO units were found, AND there isn't a more specific critical message already set.
        if ($units->isEmpty() && is_null($message)) {
            $message = 'No units found matching your selected criteria. Please adjust your filters.';
        }

        // Pass all necessary variables to the view
        return view('student.my-units', compact(
            'units',
            'allCourses',
            'allLevels',
            'selectedCourseId',
            'selectedYear',
            'selectedSemester',
            'enrolledCourse',
            'currentLevelForEnrolledCourse',
            'message' // The consolidated message
        ));
    }
}