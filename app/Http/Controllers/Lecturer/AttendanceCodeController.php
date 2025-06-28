<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceCode;
use App\Models\Unit;
use App\Models\Attendance; 
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceCodeController extends Controller
{
    /**
     * Display the form to generate an attendance code.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isLecturer() || !$user->lecturer) {
            return redirect()->route('lecturer.dashboard')->with('error', 'Access denied. Lecturer profile not found or incomplete.');
        }
        $units = $user->units()->orderBy('name')->get();
        $latestCode = AttendanceCode::with('unit')
                                   ->withCount('attendances') 
                                   ->where('lecturer_id', $user->id) 
                                   ->where('is_active', true) 
                                   ->where('expires_at', '>', Carbon::now())
                                   ->orderByDesc('created_at')
                                   ->first();

        return view('lecturer.attendance_codes.create', compact('units', 'latestCode'));
    }

    /**
     * Store a newly generated attendance code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => ['required', 'exists:units,id'],
            'duration' => ['required', 'integer', 'min:1'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $lecturerId = Auth::id();
        $unitId = $request->input('unit_id');
        $durationMinutes = (int) $request->input('duration');
        $capacity = (int) $request->input('capacity'); 

        // Invalidate any existing active codes for this lecturer and unit
        AttendanceCode::where('lecturer_id', $lecturerId)
                      ->where('unit_id', $unitId)
                      ->where('is_active', true)
                      ->update(['is_active' => false]);

        $code = Str::upper(Str::random(6)); 
        $expiresAt = Carbon::now()->addMinutes($durationMinutes);

        $attendanceCode = AttendanceCode::create([
            'lecturer_id' => $lecturerId,
            'unit_id' => $unitId,
            'code' => $code,
            'expires_at' => $expiresAt,
            'is_active' => true,
            'capacity' => $capacity, 
            'duration' => $durationMinutes, 
        ]);

        return redirect()->route('lecturer.attendance.create')->with('success', 'Attendance code generated: ' . $code);
    }

    /**
     * Invalidate an active attendance code.
     */
    public function invalidate(AttendanceCode $attendanceCode)
    {
        // Simple ownership check
        if ($attendanceCode->lecturer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $attendanceCode->update(['is_active' => false]);

        return redirect()->route('lecturer.attendance.create')->with('success', 'Attendance code invalidated.');
    }

    /**
     * Handle student submission of an attendance code.
     * This method would typically be in a StudentController or a dedicated Attendance API controller.
     * For demonstration, placing it here.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $student = Auth::user(); 

        // Find the active attendance code
        $attendanceCode = AttendanceCode::where('code', $request->code)
                                        ->where('is_active', true)
                                        ->where('expires_at', '>', Carbon::now())
                                        ->first();

        if (!$attendanceCode) {
            return redirect()->back()->with('error', 'Invalid, inactive, or expired attendance code.');
        }

        // Check if student has already marked attendance for this code
        if (Attendance::where('attendance_code_id', $attendanceCode->id)
                      ->where('student_id', $student->id)
                      ->exists()) {
            return redirect()->back()->with('error', 'You have already marked attendance for this session.');
        }

        // Check against capacity if set
        if ($attendanceCode->capacity !== null) { 
            $currentAttendeesCount = $attendanceCode->attendances()->count();
            if ($currentAttendeesCount >= $attendanceCode->capacity) {
                return redirect()->back()->with('error', 'The maximum number of students for this session has been reached.');
            }
        }

        // Mark attendance for the student
        Attendance::create([
            'attendance_code_id' => $attendanceCode->id,
            'student_id' => $student->id,
            'marked_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully!');
    }
}