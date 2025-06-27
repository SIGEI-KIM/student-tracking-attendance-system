<?php

namespace App\Http\Controllers\Student; // THIS IS CRUCIAL: Must match the path

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Level;
use App\Models\Semester;
use Illuminate\Http\Request;

class UnitCatalogController extends Controller // THIS IS CRUCIAL: Must match the file name
{
    public function index(Request $request)
    {
        // Get selected filters from the request
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        $query = Unit::query();

        // Apply filters only if both year and semester are selected
        if ($selectedYear && $selectedSemester) {
            // Get the ID of the selected Semester by its name
            $semesterRecord = Semester::where('name', $selectedSemester)->first();

            // If a valid semester record is found
            if ($semesterRecord) {
                // Filter Units by Level's year_number AND Semester's name
                $query->whereHas('level', function ($q_level) use ($selectedYear) { // Removed semesterRecord from closure as it's filtered directly below
                    // Assuming 'levels' table has 'year_number'
                    $q_level->where('year_number', $selectedYear);
                })
                ->where('semester_id', $semesterRecord->id); // This filters units directly by their assigned semester_id
            } else {
                // If semester name is invalid, return no units
                $query->whereRaw('1 = 0');
            }
        } else {
            // If no filters are selected, ensure an empty collection is returned
            // This will trigger the "Please Select Filters" message in the view
            $units = collect();
            // We still need to populate $years and $semesters for the dropdowns
            $years = Level::distinct('year_number')->pluck('year_number')->sort();
            $semesters = Semester::distinct('name')->pluck('name')->sort();
            return view('units.catalog', compact('units', 'years', 'semesters', 'selectedYear', 'selectedSemester'));
        }

        // Eager load all necessary relationships including 'lecturers' (plural for many-to-many)
        $units = $query->with(['course', 'level', 'semester', 'lecturers'])
                       ->latest()
                       ->get();

        // Populate dropdowns with distinct years from the 'levels' table
        $years = Level::distinct('year_number')->pluck('year_number')->sort();
        // Populate dropdowns with distinct semester names from the 'semesters' table
        $semesters = Semester::distinct('name')->pluck('name')->sort();

        return view('units.catalog', compact('units', 'years', 'semesters', 'selectedYear', 'selectedSemester'));
    }
}