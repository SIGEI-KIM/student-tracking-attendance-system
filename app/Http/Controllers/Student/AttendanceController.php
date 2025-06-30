<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Attendance;
use App\Models\AttendanceCode; // Important: Import the AttendanceCode model
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the units for the student to mark attendance.
     * Includes logic to show units active right now.
     */
    public function index()
    {
        $student = Auth::user()->student;

        // Get today's numeric day of the week (0=Sunday, 1=Monday, ..., 6=Saturday)
        // Adjust if your schedule uses 1 for Monday, etc.
        // Assuming 'schedules' day_of_week_numeric aligns with Carbon's default (Sunday = 0).
        $todayNumeric = Carbon::now()->dayOfWeek; // Carbon::MONDAY is 1, Carbon::FRIDAY is 5
        $currentTime = Carbon::now()->toTimeString(); // e.g., "17:05:00"

        $todayFormatted = now()->format('D, M d, Y');

        if (!$student || !$student->currentEnrollment()) {
            return view('student.attendance.index', [
                'units' => collect(),
                'message' => 'Your academic profile (Level/Semester) is incomplete. Please contact administration.',
                'today' => $todayFormatted,
                'attendancesToday' => collect(), // Still pass empty collections to avoid errors
            ]);
        }

        $currentEnrollment = $student->currentEnrollment();
        $currentLevelId = $currentEnrollment->pivot->level_id;
        $currentSemesterId = $currentEnrollment->pivot->semester_id;

        // Fetch units relevant to the student's current enrollment and currently active schedules
        $unitsQuery = Unit::where('level_id', $currentLevelId)
                         ->where('semester_id', $currentSemesterId)
                         ->whereHas('schedules', function ($query) use ($todayNumeric, $currentTime) {
                             $query->where('day_of_week_numeric', $todayNumeric)
                                   ->where('start_time', '<=', $currentTime)
                                   ->where('end_time', '>=', $currentTime);
                         })
                         ->with('course', 'level', 'semester', 'lecturers', 'schedules');

        $units = $unitsQuery->get();

        // Get attendance statuses for today for these units
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

    /**
     * Handles marking attendance via an attendance code provided by the student.
     */
    public function markByCode(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'code' => 'required|string|size:6', // Ensure the code is 6 characters long
        ]);

        $student = Auth::user()->student;

        // Basic check for student profile completion
        if (!$student || !$student->currentEnrollment()) {
            return redirect()->back()->with('error', 'Your academic profile (Level/Semester) is incomplete. Cannot mark attendance.');
        }

        // 2. Find the Attendance Code
        $attendanceCode = AttendanceCode::where('code', strtoupper($request->code)) // Convert to uppercase for case-insensitive match if codes are stored as uppercase
                                        ->where('unit_id', $request->unit_id) // Ensure code is for the selected unit
                                        ->where('expires_at', '>', now())     // Check if the code is still valid (not expired)
                                        ->where('is_active', true)            // Check if the code has not been manually invalidated
                                        ->first();

        if (!$attendanceCode) {
            return redirect()->back()->with('error', 'Invalid, expired, or incorrect attendance code for this unit. Please try again.');
        }

        $unit = Unit::find($request->unit_id); // Re-fetch unit to ensure it's valid and load schedules

        // 3. Verify Student Enrollment in the Unit
        $currentEnrollment = $student->currentEnrollment();
        if (!$currentEnrollment ||
            $unit->level_id !== $currentEnrollment->pivot->level_id ||
            $unit->semester_id !== $currentEnrollment->pivot->semester_id) {
            return redirect()->back()->with('error', 'This unit is not part of your current academic enrollment.');
        }

        // 4. Verify Unit Schedule (is it active right now?)
        $todayNumeric = Carbon::now()->dayOfWeek;
        $currentTime = Carbon::now();

        $unit->load('schedules'); // Ensure schedules are loaded
        $isActiveNow = $unit->schedules->filter(function ($schedule) use ($todayNumeric, $currentTime) {
            $scheduleStartTime = Carbon::parse($schedule->start_time);
            $scheduleEndTime = Carbon::parse($schedule->end_time);

            return $schedule->day_of_week_numeric === $todayNumeric &&
                   $currentTime->gte($scheduleStartTime) &&
                   $currentTime->lte($scheduleEndTime);
        })->isNotEmpty();

        if (!$isActiveNow) {
            // It's possible the code was generated for a unit, but the current time is outside its schedule
            return redirect()->back()->with('error', 'The unit associated with this code is not active at the current time according to its schedule.');
        }

        // 5. Check if Attendance Already Marked
        $existingAttendance = Attendance::where('student_id', $student->id)
                                        ->where('unit_id', $unit->id)
                                        ->whereDate('attendance_date', Carbon::today())
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('info', 'You have already marked attendance for ' . $unit->name . ' today. Status: ' . ucfirst($existingAttendance->status) . '.');
        }

        // 6. Check Attendance Code Capacity (Optional, if you implemented 'capacity' column)
        if ($attendanceCode->capacity !== null && $attendanceCode->attendances()->count() >= $attendanceCode->capacity) {
             return redirect()->back()->with('error', 'This attendance code has reached its maximum capacity.');
        }

        // 7. Mark Attendance
        Attendance::create([
            'user_id' => Auth::id(), // The user who is marking attendance (student's user ID)
            'student_id' => $student->id,
            'unit_id' => $unit->id,
            'attendance_date' => Carbon::today(),
            'status' => 'present',
            'marked_at' => now(),
            'attendance_code_id' => $attendanceCode->id, // Link to the attendance code used
            'lecturer_id' => $attendanceCode->lecturer_id, // Link to the lecturer who generated the code
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully for ' . $unit->name . '.');
    }

    // You can remove the 'mark' method below if you are fully transitioning to code-based attendance.
    // If you need it for another purpose, keep it but ensure your routes don't conflict.
    /*
    public function mark(Request $request, Unit $unit)
    {
        // ... (previous logic for direct marking) ...
    }
    */
}