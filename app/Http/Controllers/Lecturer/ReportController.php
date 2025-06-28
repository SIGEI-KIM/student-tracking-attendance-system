<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User; // This might not be strictly needed if only using via relationship
use App\Models\Student;
use App\Models\Attendance;
use App\Enums\Role; // Assuming this Enum is still in use
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
        $unit = Unit::with('course', 'level', 'schedules')->findOrFail($unitId);

        $isAssignedToUnit = $lecturer->units()->where('units.id', $unitId)->exists();

        if (!$isAssignedToUnit) {
            return redirect()->back()->with('error', 'You are not assigned to this unit.');
        }
        $scheduledDaysOfWeek = $unit->schedules->pluck('day_of_week_numeric')->toArray();

        // 4. Fetch students relevant to this unit and their attendance records within the date range
        $students = Student::whereHas('courses', function ($query) use ($unit) {
                $query->where('courses.id', $unit->course_id);
                // The 'level_id' condition here assumes 'course_enrollments' table has 'level_id'
                // and students are associated with courses through 'course_enrollments'
                if ($unit->level_id) {
                    $query->where('course_enrollments.level_id', $unit->level_id);
                }
            })
            // Eager load the 'user' relationship to get student's name, email etc.
            // Eager load 'attendances' specific to this unit and date range.
            ->with(['user', 'attendances' => function ($query) use ($unitId, $startDate, $endDate) {
                $query->where('unit_id', $unitId)
                      ->whereBetween('attendance_date', [$startDate, $endDate])
                      ->orderBy('attendance_date');
            }])
            // Join to the 'users' table to enable ordering by user's name
            ->join('users', 'students.user_id', '=', 'users.id')
            // FIX: Order by the 'name' column directly from the 'users' table.
            // This corrects the PostgreSQL "operator does not exist: users ->> unknown" error.
            ->orderBy('users.name')
            // Ensure we select only student columns to prevent column name clashes
            ->select('students.*')
            ->get();

        // 5. Prepare data for the report table
        $reportData = [];
        $reportDates = []; // This will hold only the scheduled class dates

        // Generate all *scheduled class dates* within the specified range to serve as table headers
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Carbon's dayOfWeek returns 0 for Sunday, 1 for Monday... 6 for Saturday
            if (in_array($currentDate->dayOfWeek, $scheduledDaysOfWeek)) {
                $reportDates[] = $currentDate->toDateString();
            }
            $currentDate->addDay();
        }

        foreach ($students as $student) {
            $presence = [];
            // Create a map for the student's fetched attendances for quick lookup by date string
            $studentAttendancesMap = $student->attendances->keyBy(function($attendance) {
                // Ensure the key is a 'YYYY-MM-DD' string to match $dateString from $reportDates
                return $attendance->attendance_date->toDateString();
            });

            foreach ($reportDates as $dateString) { // Iterate over *only* scheduled class dates
                // Use the map to find the attendance record for this student and scheduled date
                $attendanceRecord = $studentAttendancesMap->get($dateString);

                // Determine status based on the 'status' column in Attendance model
                $presence[$dateString] = $attendanceRecord ? $attendanceRecord->status : 'Absent';
            }

            $reportData[] = [
                'name' => $student->user->name,
                // Make sure 'registration_number' matches the actual column/accessor on your Student model
                'registration_number' => $student->registration_number ?? $student->reg_number,
                'presence' => $presence,
            ];
        }

        // 6. Load the Blade view and generate PDF
        // Pass the lecturer object to the view
        $pdf = Pdf::loadView('lecturer.reports.pdf_template', compact('unit', 'reportData', 'reportDates', 'startDate', 'endDate', 'lecturer'));
        $filename = 'Attendance_Report_' . $unit->code . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}