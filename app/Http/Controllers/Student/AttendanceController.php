<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Attendance;
use Carbon\Carbon; 

class AttendanceController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $today = now()->format('D, M d, Y'); 

        if (!$student || !$student->currentEnrollment()) {
            return view('student.attendance.index', [
                'units' => collect(), 
                'message' => 'Your academic profile (Level/Semester) is incomplete. Please contact administration.',
                'today' => $today, 
                'attendancesToday' => collect(),
            ]);
        }

        $currentEnrollment = $student->currentEnrollment();
        $currentLevelId = $currentEnrollment->pivot->level_id;
        $currentSemesterId = $currentEnrollment->pivot->semester_id;

        $units = Unit::where('level_id', $currentLevelId)
                     ->where('semester_id', $currentSemesterId)
                     ->with('course', 'level', 'semester', 'lecturers')
                     ->get();

        // CHANGE THIS LINE: Use 'user_id' and Auth::id()
        $attendancesToday = Attendance::where('student_id', $student->id)
                                      ->whereDate('attendance_date', Carbon::today())
                                      ->get()
                                      ->keyBy('unit_id')
                                      ->map(fn($attendance) => $attendance->status);

        return view('student.attendance.index', [
            'units' => $units,
            'today' => $today,
            'attendancesToday' => $attendancesToday,
        ]);
    }

    public function mark(Request $request, Unit $unit)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->currentEnrollment()) {
            return redirect()->back()->with('error', 'Your academic profile is incomplete. Cannot mark attendance.');
        }

        $currentEnrollment = $student->currentEnrollment();
        if ($unit->level_id !== $currentEnrollment->pivot->level_id ||
            $unit->semester_id !== $currentEnrollment->pivot->semester_id) {
            return redirect()->back()->with('error', 'This unit is not part of your current academic enrollment.');
        }

        // CHANGE THIS LINE: Use 'user_id' and Auth::id()
        $existingAttendance = Attendance::where('student_id', $student->id) 
                                        ->where('unit_id', $unit->id)
                                        ->whereDate('attendance_date', Carbon::today())
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You have already marked attendance for this unit today.');
        }

        // CRITICAL CHANGE HERE: Use 'user_id' and Auth::id()
        Attendance::create([
            'user_id' => Auth::id(), // <--- THIS IS THE FIX for your specific error!
            'unit_id' => $unit->id,
            'attendance_date' => Carbon::today(),
            'status' => 'present',
            'marked_at' => now(), 
            'student_id' => $student->id,
            
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully for ' . $unit->name . '.');
    }
}