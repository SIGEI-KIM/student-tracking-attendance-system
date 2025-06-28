<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Unit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $lecturer = auth()->user()->lecturer;
        $units = $lecturer->units()
                          ->with(['course', 'level', 'schedules']) 
                          ->get();

        // Get the IDs of units taught by this lecturer
        $unitIds = $units->pluck('id');

        // Today's attendance for units taught by this lecturer
        $todayAttendances = Attendance::whereIn('unit_id', $unitIds)
            ->whereDate('attendance_date', Carbon::today())
            ->with(['student.user', 'unit.schedules']) 
            ->get()
            ->groupBy('unit_id');

        return view('lecturer.dashboard', compact('units', 'todayAttendances'));
    }

    public function attendancesIndex()
    {
        $lecturer = auth()->user()->lecturer;
        $units = $lecturer->units()
            ->with(['course', 'level', 'attendances' => function($query) {
                $query->orderBy('attendance_date', 'desc')->take(5); // <--- Potentially CORRECTED (depends on your DB)
            }])
            ->get();

        // Recent attendances across all units
        $recentAttendances = Attendance::whereIn('unit_id', $units->pluck('id'))
            ->with(['student', 'unit'])
            ->latest('attendance_date') 
            ->take(20)
            ->get();

        return view('lecturer.attendance.index', compact('units', 'recentAttendances'));
    }

    public function viewUnitAttendances(Unit $unit)
    {
        $lecturer = auth()->user()->lecturer;
        
        // Verify lecturer teaches this unit
        if (!$lecturer->units->contains($unit->id)) {
            abort(403);
        }

        $attendances = Attendance::where('unit_id', $unit->id)
            ->with('student')
            ->orderBy('attendance_date', 'desc') 
            ->paginate(20);

        return view('lecturer.unit-attendances', compact('unit', 'attendances'));
    }  
}