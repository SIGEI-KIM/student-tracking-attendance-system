<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Enums\Role;

use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Show the form to generate PDF reports.
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            return redirect()->route('dashboard')->with('error', 'Lecturer profile not found for your account. Please complete your profile.');
        }

        $units = $lecturer->units()->with('course')->get();
        $totalAttendanceRecords = Attendance::whereIn('unit_id', $units->pluck('id'))->count();

        return view('lecturer.reports.index', compact('units', 'totalAttendanceRecords'));
    }

    /**
     * Generate a PDF attendance report for a specific unit and date range.
     */
    public function generateUnitReportPdf(Request $request)
    {
        // 1. Validate incoming request
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $unitId = $request->input('unit_id');

        // Determine the date range for the report (default to last week if not provided)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subWeek();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        // Ensure endDate includes the entire day for accurate filtering
        $endDate->endOfDay();

        // 2. Get the authenticated lecturer
        $lecturer = Auth::user()->lecturer;

        if (is_null($lecturer)) {
            return redirect()->back()->with('error', 'Lecturer profile not found.');
        }

        // 3. Verify the lecturer is assigned to this unit (security check)
        // Load unit with course and level for report details
        $unit = Unit::with('course', 'level')->findOrFail($unitId);
        $isAssignedToUnit = $lecturer->units()->where('units.id', $unitId)->exists();

        if (!$isAssignedToUnit) {
            return redirect()->back()->with('error', 'You are not assigned to this unit.');
        }

        // 4. Fetch students relevant to this unit and their attendance records within the date range
        $students = Student::whereHas('courses', function ($query) use ($unit) {
                $query->where('courses.id', $unit->course_id);

                if ($unit->level_id) {
                    $query->where('course_enrollments.level_id', $unit->level_id);
                }
            })
            ->with(['user', 'attendances' => function ($query) use ($unitId, $startDate, $endDate) {
                $query->where('unit_id', $unitId)
                      ->whereBetween('attendance_date', [$startDate, $endDate]) // <-- FIX: Changed 'date' to 'attendance_date'
                      ->orderBy('attendance_date'); // <-- FIX: Changed 'date' to 'attendance_date'
            }])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('students.*')
            ->get();

        // 5. Prepare data for the report table
        $reportData = [];
        $reportDates = [];

        // Generate all dates within the specified range to serve as table headers
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $reportDates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        foreach ($students as $student) {
            $presence = [];
            foreach ($reportDates as $dateString) { // Rename $date to $dateString for clarity
                // Find the attendance record for the specific date string
                $attendanceRecord = $student->attendances->first(function ($attendance) use ($dateString) {
                    return $attendance->attendance_date->toDateString() === $dateString;
                });
        
                $presence[$dateString] = $attendanceRecord ? $attendanceRecord->status : 'Absent';
            }
            $reportData[] = [
                'name' => $student->user->name,
                'registration_number' => $student->registration_number,
                'presence' => $presence,
            ];
        }

        // 6. Load the Blade view and generate PDF
        $pdf = Pdf::loadView('lecturer.reports.pdf_template', compact('unit', 'reportData', 'reportDates', 'startDate', 'endDate'));
        $filename = 'Attendance_Report_' . $unit->code . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}