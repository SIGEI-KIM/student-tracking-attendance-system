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
        $units = $lecturer->units()->with('course', 'level')->get();
        
        // Today's attendance for units taught by this lecturer
        $todayAttendances = Attendance::whereIn('unit_id', $units->pluck('id'))
            ->whereDate('date', Carbon::today())
            ->with('student', 'unit')
            ->get()
            ->groupBy('unit_id');

        return view('lecturer.dashboard', compact('units', 'todayAttendances'));
    }

    public function attendancesIndex()
    {
        $lecturer = auth()->user()->lecturer;
        $units = $lecturer->units()
            ->with(['course', 'level', 'attendances' => function($query) {
                $query->latest()->take(5);
            }])
            ->get();

        // Recent attendances across all units
        $recentAttendances = Attendance::whereIn('unit_id', $units->pluck('id'))
            ->with(['student', 'unit'])
            ->latest()
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
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('lecturer.unit-attendances', compact('unit', 'attendances'));
    }  
}