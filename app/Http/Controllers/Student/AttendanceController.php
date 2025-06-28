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

        $todayNumeric = Carbon::now()->dayOfWeek; // 0=Sunday, 5=Friday
        $currentTime = Carbon::now()->toTimeString(); // e.g., "17:05:00"

        $todayFormatted = now()->format('D, M d, Y');

        if (!$student || !$student->currentEnrollment()) {
            return view('student.attendance.index', [
                'units' => collect(),
                'message' => 'Your academic profile (Level/Semester) is incomplete. Please contact administration.',
                'today' => $todayFormatted,
                'attendancesToday' => collect(),
            ]);
        }

        $currentEnrollment = $student->currentEnrollment();
        $currentLevelId = $currentEnrollment->pivot->level_id;
        $currentSemesterId = $currentEnrollment->pivot->semester_id;

        $unitsQuery = Unit::where('level_id', $currentLevelId)
                         ->where('semester_id', $currentSemesterId)
                         ->whereHas('schedules', function ($query) use ($todayNumeric, $currentTime) {
                             $query->where('day_of_week_numeric', $todayNumeric)
                                   ->where('start_time', '<=', $currentTime)
                                   ->where('end_time', '>=', $currentTime);
                         })
                         ->with('course', 'level', 'semester', 'lecturers', 'schedules');

        $units = $unitsQuery->get();

        $attendancesToday = Attendance::where('student_id', $student->id)
                                      ->whereDate('attendance_date', Carbon::today())
                                      ->get()
                                      ->keyBy('unit_id')
                                      ->map(fn($attendance) => $attendance->status);

        $message = null;
        if ($units->isEmpty()) {
            $message = "No units are scheduled for your current academic level and semester that are active right now.";
        }

        return view('student.attendance.index', [
            'units' => $units,
            'today' => $todayFormatted,
            'attendancesToday' => $attendancesToday,
            'message' => $message,
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

        $todayNumeric = Carbon::now()->dayOfWeek; // 0 for Sunday, 5 for Friday
        $currentTime = Carbon::now(); // Get full Carbon instance for comparison

        $unit->load('schedules'); // Ensure schedules are loaded for this unit

        // --- Remove the previous dd() block here ---

        // FIX: Convert schedule times to Carbon objects for robust comparison
        $isActiveNow = $unit->schedules->filter(function ($schedule) use ($todayNumeric, $currentTime) {
            $scheduleStartTime = Carbon::parse($schedule->start_time);
            $scheduleEndTime = Carbon::parse($schedule->end_time);

            return $schedule->day_of_week_numeric === $todayNumeric &&
                   $currentTime->gte($scheduleStartTime) && // gte = greater than or equal to
                   $currentTime->lte($scheduleEndTime);     // lte = less than or equal to
        })->isNotEmpty();


        if (!$isActiveNow) {
            return redirect()->back()->with('error', 'This unit is not active at the current time according to its schedule.');
        }

        $existingAttendance = Attendance::where('student_id', $student->id)
                                        ->where('unit_id', $unit->id)
                                        ->whereDate('attendance_date', Carbon::today())
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You have already marked attendance for this unit today.');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'unit_id' => $unit->id,
            'attendance_date' => Carbon::today(),
            'status' => 'present',
            'marked_at' => now(),
            'student_id' => $student->id,
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully for ' . $unit->name . '.');
    }
}