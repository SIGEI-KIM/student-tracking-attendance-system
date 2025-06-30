<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Unit;
use App\Models\User; // Make sure User model is imported
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Make sure Auth facade is imported

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user (who is the lecturer)

        // CORRECTED: Access units directly from the User model's 'units' relationship
        // because lecturer_unit pivot table uses user_id, not lecturer_id.
        $units = $user->units()
                      ->with(['course', 'level', 'schedules', 'semester']) // Added 'semester' eager load
                      ->get();

        // Get the IDs of units assigned to this lecturer
        $unitIds = $units->pluck('id');

        // Today's attendance for units assigned to this lecturer
        $todayAttendances = Attendance::whereIn('unit_id', $unitIds)
            ->whereDate('attendance_date', Carbon::today())
            ->with(['student.user', 'unit.schedules'])
            ->get()
            ->groupBy('unit_id');

        return view('lecturer.dashboard', compact('units', 'todayAttendances'));
    }

    public function attendancesIndex()
    {
        $user = Auth::user(); // Get the authenticated user (who is the lecturer)

        // CORRECTED: Access units directly from the User model's 'units' relationship
        $units = $user->units()
            ->with(['course', 'level', 'attendances' => function($query) {
                $query->orderBy('attendance_date', 'desc')->take(5);
            }])
            ->get();

        // Recent attendances across all units
        $recentAttendances = Attendance::whereIn('unit_id', $units->pluck('id'))
            ->with(['student.user', 'unit']) // Eager load student.user for name display
            ->latest('attendance_date')
            ->take(20)
            ->get();

        return view('lecturer.attendance.index', compact('units', 'recentAttendances'));
    }

    public function viewUnitAttendances(Unit $unit)
    {
        $user = Auth::user(); // Get the authenticated user (who is the lecturer)

        // Verify lecturer teaches this unit by checking the user's units
        if (!$user->units->contains($unit->id)) {
            abort(403, 'Unauthorized. You are not assigned to this unit.');
        }

        $attendances = Attendance::where('unit_id', $unit->id)
            ->with('student.user') // Eager load student.user for name display
            ->orderBy('attendance_date', 'desc')
            ->paginate(20);

        return view('lecturer.unit-attendances', compact('unit', 'attendances'));
    }
}