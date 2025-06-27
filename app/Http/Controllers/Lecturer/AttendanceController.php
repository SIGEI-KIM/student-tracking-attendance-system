<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Unit; // Make sure to import the Unit model

class AttendanceController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        // 1. Fetch Units for the "Attendance Overview" section
        // Eager load 'course', 'level', and 'attendances' for the count in the blade
        $units = $lecturer->units()
                          ->with(['course', 'level', 'attendances']) // Make sure 'attendances' is a relationship on Unit model
                          ->get();

        // Get the IDs of all units taught by this lecturer for filtering attendances
        $unitIds = $units->pluck('id');

        // 2. Fetch Recent Attendance Records for the table section
        // Renamed to $recentAttendances to match your blade template
        $recentAttendances = Attendance::whereIn('unit_id', $unitIds)
                                       ->with(['student', 'unit']) // Eager load student and unit for display
                                       ->latest('marked_at') // Assuming 'marked_at' is the correct column for latest attendances
                                       ->paginate(15);

        // Pass both variables to the view
        return view('lecturer.attendance.index', compact('units', 'recentAttendances'));
    }
}