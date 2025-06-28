<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Unit;
use App\Models\Schedule; 

class AttendanceController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        // 1. Fetch Units for the "Attendance Overview" section
        $units = $lecturer->units()
                          ->with(['course', 'level', 'attendances', 'schedules']) // Eager load 'schedules' as well for potential display
                          ->get();

        // Get the IDs of all units taught by this lecturer for filtering attendances
        $unitIds = $units->pluck('id');

        // 2. Fetch Recent Attendance Records for the table section
        $recentAttendances = Attendance::whereIn('unit_id', $unitIds)
                                       ->with(['student', 'unit'])
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
        // Ensure the authenticated lecturer is assigned to this unit for security
        $lecturer = Auth::user()->lecturer;
        if (!$lecturer->units()->where('units.id', $unit->id)->exists()) {
            return redirect()->route('lecturer.dashboard')->with('error', 'You are not assigned to this unit.');
        }

        // Fetch all attendance records for this unit
        // You might want to paginate these if there are many
        $attendances = Attendance::where('unit_id', $unit->id)
                                 ->with('student.user') // Eager load student and its user for name
                                 ->orderBy('attendance_date', 'desc')
                                 ->paginate(20); // Paginate for large datasets

        // Fetch scheduled days for this unit to potentially display in the view
        $scheduledDays = $unit->schedules()->get();

        return view('lecturer.attendance.unit_attendances', compact('unit', 'attendances', 'scheduledDays'));
    }
}