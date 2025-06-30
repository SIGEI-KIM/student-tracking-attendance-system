<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Unit;
use App\Models\Schedule;
use App\Models\User; // Make sure to import the User model

class AttendanceController extends Controller
{
    public function index()
    {
        // Get the authenticated user (who is the lecturer)
        $user = Auth::user();

        // Access units directly from the User model's 'units' relationship
        // This is the correct relationship for units assigned via 'lecturer_unit.user_id'
        $units = $user->units()
                          ->with(['course', 'level', 'attendances', 'schedules', 'semester']) // Added semester here for completeness
                          ->get();

        // Get the IDs of all units taught by this lecturer for filtering attendances
        $unitIds = $units->pluck('id');

        // 2. Fetch Recent Attendance Records for the table section
        $recentAttendances = Attendance::whereIn('unit_id', $unitIds)
                                       ->with(['student.user', 'unit']) // Eager load student's user for name display
                                       ->latest('marked_at')
                                       ->paginate(15);

        return view('lecturer.attendance.index', compact('units', 'recentAttendances'));
    }

    /**
     * Display attendance records for a specific unit for the lecturer.
     * This method is called when a lecturer wants to view detailed attendance for one unit.
     *
     * @param  \App\Models\Unit  $unit The unit for which to view attendance.
     * @return \Illuminate\View\View
     */
    public function viewUnitAttendances(Unit $unit)
    {
        // Get the authenticated user (who is the lecturer)
        $user = Auth::user();

        // CORRECTED CHECK: Verify the user (lecturer) is assigned to this unit
        // Use the units relationship on the User model directly.
        if (!$user->units()->where('units.id', $unit->id)->exists()) {
            // Also log this for debugging
            \Log::error("Attempted unauthorized access: User ID {$user->id} tried to view Unit ID {$unit->id}. Not assigned.");
            return redirect()->route('lecturer.dashboard')->with('error', 'You are not assigned to this unit.');
        }

        // Fetch all attendance records for this unit
        $attendances = Attendance::where('unit_id', $unit->id)
                                 ->with('student.user') // Eager load student and its user for name
                                 ->orderBy('attendance_date', 'desc')
                                 ->paginate(20);

        // Fetch scheduled days for this unit to potentially display in the view
        $scheduledDays = $unit->schedules()->get();

        return view('lecturer.attendance.unit_attendances', compact('unit', 'attendances', 'scheduledDays'));
    }

}