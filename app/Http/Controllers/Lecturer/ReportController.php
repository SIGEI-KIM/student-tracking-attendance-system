<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
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
            // Redirect to dashboard or a specific error page if lecturer profile is missing
            return redirect()->route('dashboard')->with('error', 'Lecturer profile not found for your account. Please complete your profile.');
        }

        // Get units assigned to the logged-in lecturer
        // Eager load the 'course' relationship for display in the dropdown
        $units = $lecturer->units()->with('course')->get();

        // Example for total attendance records across all units assigned to this lecturer (optional for overview)
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
        // Filter students by their role (STUDENT), by the course associated with the unit,
        // and by their level if units are level-specific.
        $students = User::where('role', Role::STUDENT)
            ->whereHas('courses', function ($query) use ($unit) {
                $query->where('courses.id', $unit->course_id);
            })
            // Only include the level filter if units are truly level-specific.
            // If a unit can be taught to multiple levels of the same course, remove this.
            ->when($unit->level_id, function ($query) use ($unit) {
                $query->where('level_id', $unit->level_id);
            })
            ->with(['attendances' => function ($query) use ($unitId, $startDate, $endDate) {
                $query->where('unit_id', $unitId)
                      ->whereBetween('date', [$startDate, $endDate])
                      ->orderBy('date');
            }])
            // ->where('level_id', $someLevelIdVariable)
            ->orderBy('name') 
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
            foreach ($reportDates as $date) {
                // Find the attendance record for this student, unit, and date
                $attendanceRecord = $student->attendances->firstWhere('date', $date);

                // Determine status based on the 'status' column in Attendance model
                // Assuming 'status' can be 'Present', 'Absent', 'Late', etc.
                // If a record exists, use its status; otherwise, default to 'Absent'.
                $presence[$date] = $attendanceRecord ? $attendanceRecord->status : 'Absent';
            }

            $reportData[] = [
                'name' => $student->name,
                'registration_number' => $student->registration_number,
                'presence' => $presence,
            ];
        }

        // 6. Load the Blade view and generate PDF
        $pdf = Pdf::loadView('lecturer.reports.pdf_template', compact('unit', 'reportData', 'reportDates', 'startDate', 'endDate'));

        // Optional: Set paper size and orientation if needed for better layout
        // $pdf->setPaper('A4', 'landscape'); // Example for landscape A4

        // Optional: Set a base path for images if you use local images (e.g., logo) in your PDF template
        // $pdf->setBasePath(public_path('images'));

        // Generate a descriptive filename
        $filename = 'Attendance_Report_' . $unit->code . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        // Download the PDF file to the user's browser
        return $pdf->download($filename);
        // If you want to display the PDF directly in the browser instead of downloading, use ->stream():
        // return $pdf->stream($filename);
    }
}