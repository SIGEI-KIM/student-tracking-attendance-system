<?php

// app/Http/Controllers/Api/AttendanceController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function markAttendance(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'status' => 'required|in:present,absent,late',
        ]);

        $student = Auth::user();
        $unit = Unit::findOrFail($request->unit_id);

        // Check if student is enrolled in the course that has this unit
        if (!$student->courses()->where('course_id', $unit->course_id)->exists()) {
            return response()->json(['error' => 'You are not enrolled in this course'], 403);
        }

        // Check if attendance already marked today
        $existingAttendance = Attendance::where('user_id', $student->id)
            ->where('unit_id', $request->unit_id)
            ->whereDate('date', now())
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Attendance already marked for today'], 400);
        }

        $attendance = Attendance::create([
            'user_id' => $student->id,
            'unit_id' => $request->unit_id,
            'date' => now(),
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance,
        ]);
    }

    public function getStudentAttendances()
    {
        $student = Auth::user();
        $attendances = Attendance::where('user_id', $student->id)
            ->with('unit')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json($attendances);
    }

    public function getUnitAttendances($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $lecturer = Auth::user();

        // Verify lecturer is assigned to this unit
        if (!$lecturer->units()->where('unit_id', $unitId)->exists()) {
            return response()->json(['error' => 'You are not assigned to this unit'], 403);
        }

        $attendances = Attendance::where('unit_id', $unitId)
            ->with('student')
            ->orderBy('date', 'desc')
            ->paginate(20);

        return response()->json([
            'unit' => $unit,
            'attendances' => $attendances,
        ]);
    }
}