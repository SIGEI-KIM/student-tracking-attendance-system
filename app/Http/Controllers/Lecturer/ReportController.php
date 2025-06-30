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
        $user = Auth::user();

        $units = $user->units()->with('course')->get();
        $totalUnits = $units->count();
        $totalAttendanceRecords = 0;

        if ($totalUnits > 0) {
            $unitIds = $units->pluck('id');
            $totalAttendanceRecords = Attendance::whereIn('unit_id', $unitIds)->count();
        }

        return view('lecturer.reports.index', compact('units', 'totalUnits', 'totalAttendanceRecords'));
    }

    /**
     * Generate a PDF attendance report for a specific unit and date range.
     */
    public function generateUnitReportPdf(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $unitId = $request->input('unit_id');

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfWeek(Carbon::SUNDAY);
        $endDate->endOfDay();

        $user = Auth::user();
        $unit = Unit::with('course', 'level', 'schedules')->findOrFail($unitId);
        $isAssignedToUnit = $user->units()->where('units.id', $unitId)->exists();

        if (!$isAssignedToUnit) {
            return redirect()->back()->with('error', 'You are not assigned to this unit.');
        }
        $scheduledDaysOfWeek = $unit->schedules->pluck('day_of_week_numeric')->toArray();

        $students = Student::whereHas('courses', function ($query) use ($unit) {
                $query->where('courses.id', $unit->course_id);
                if ($unit->level_id) {
                    $query->where('course_enrollments.level_id', $unit->level_id);
                }
            })
            ->with(['user', 'attendances' => function ($query) use ($unitId, $startDate, $endDate) {
                $query->where('unit_id', $unitId)
                      ->whereBetween('attendance_date', [$startDate, $endDate])
                      ->orderBy('attendance_date');
            }])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('students.*')
            ->get();

        $reportData = [];
        $reportDates = [];

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if (in_array($currentDate->dayOfWeek, $scheduledDaysOfWeek)) {
                $reportDates[] = $currentDate->toDateString();
            }
            $currentDate->addDay();
        }

        $totalScheduledClasses = count($reportDates);

        foreach ($students as $student) {
            $presence = [];
            $classesAttended = 0;

            $studentAttendancesMap = $student->attendances->keyBy(function($attendance) {
                return $attendance->attendance_date->toDateString();
            });

            foreach ($reportDates as $dateString) {
                $attendanceRecord = $studentAttendancesMap->get($dateString);
                $status = $attendanceRecord ? $attendanceRecord->status : 'Absent';
                $presence[$dateString] = $status;

                if (strtolower($status) === 'present' || strtolower($status) === 'late') {
                    $classesAttended++;
                }
            }

            $attendancePercentage = 0;
            if ($totalScheduledClasses > 0) {
                $attendancePercentage = ($classesAttended / $totalScheduledClasses) * 100;
            }

            $reportData[] = [
                'name' => $student->user->name,
                'registration_number' => $student->registration_number ?? $student->reg_number,
                'presence' => $presence,
                'percentage' => round($attendancePercentage, 2),
                'attended_count' => $classesAttended,
                'total_scheduled_count' => $totalScheduledClasses,
            ];
        }

        $pdf = Pdf::loadView('lecturer.reports.pdf_template', compact('unit', 'reportData', 'reportDates', 'startDate', 'endDate', 'user'));
        $filename = 'Attendance_Report_' . $unit->code . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}