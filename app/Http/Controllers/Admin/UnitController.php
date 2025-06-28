<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Course;
use App\Models\Level;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Schedule; // Import the Schedule model

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['course', 'level', 'semester', 'lecturers', 'schedules'])->latest()->paginate(10); // Eager load schedules
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $courses = Course::all();
        $levels = Level::all();
        $lecturers = User::where('role', 'lecturer')->get();
        $semesters = Semester::all();
        $daysOfWeek = [ // For the schedule dropdown
            1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
            4 => 'Thursday', 5 => 'Friday',
        ];
        return view('admin.units.create', compact('courses', 'levels', 'lecturers', 'semesters', 'daysOfWeek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:units',
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            'semester_id' => 'required|exists:semesters,id',
            'lecturers' => 'required|array',
            'lecturers.*' => 'exists:users,id',
            // Validation for schedules - expecting an array of arrays
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week_numeric' => 'required_with:schedules.*.start_time,schedules.*.end_time|integer|between:1,5',
            'schedules.*.start_time' => 'required_with:schedules.*.day_of_week_numeric,schedules.*.end_time|date_format:H:i',
            'schedules.*.end_time' => 'required_with:schedules.*.day_of_week_numeric,schedules.*.start_time|date_format:H:i|after:schedules.*.start_time',
        ]);

        $unit = Unit::create($request->only(['name', 'code', 'course_id', 'level_id', 'semester_id']));
        $unit->lecturers()->sync($request->lecturers);

        // Save schedules
        if ($request->has('schedules')) {
            foreach ($request->schedules as $scheduleData) {
                // Only create if all parts of a schedule entry are provided
                if (isset($scheduleData['day_of_week_numeric']) && isset($scheduleData['start_time']) && isset($scheduleData['end_time'])) {
                    $unit->schedules()->create($scheduleData);
                }
            }
        }


        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        $courses = Course::all();
        $levels = Level::all();
        $lecturers = User::where('role', 'lecturer')->get();
        $assignedLecturers = $unit->lecturers->pluck('id')->toArray();
        $semesters = Semester::all();
        $daysOfWeek = [ // For the schedule dropdown
            1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
            4 => 'Thursday', 5 => 'Friday',
        ];

        return view('admin.units.edit', compact('unit', 'courses', 'levels', 'lecturers', 'assignedLecturers', 'semesters', 'daysOfWeek'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:units,code,' . $unit->id,
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            'semester_id' => 'required|exists:semesters,id',
            'lecturers' => 'required|array',
            'lecturers.*' => 'exists:users,id',
            // Validation for schedules
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week_numeric' => 'required_with:schedules.*.start_time,schedules.*.end_time|integer|between:1,5',
            'schedules.*.start_time' => 'required_with:schedules.*.day_of_week_numeric,schedules.*.end_time|date_format:H:i',
            'schedules.*.end_time' => 'required_with:schedules.*.day_of_week_numeric,schedules.*.start_time|date_format:H:i|after:schedules.*.start_time',
        ]);

        $unit->update($request->only(['name', 'code', 'course_id', 'level_id', 'semester_id']));
        $unit->lecturers()->sync($request->lecturers);

        // Update schedules:
        // 1. Delete existing schedules for this unit
        $unit->schedules()->delete();
        // 2. Create new ones from the request
        if ($request->has('schedules')) {
            foreach ($request->schedules as $scheduleData) {
                // Only create if all parts of a schedule entry are provided
                if (isset($scheduleData['day_of_week_numeric']) && isset($scheduleData['start_time']) && isset($scheduleData['end_time'])) {
                    $unit->schedules()->create($scheduleData);
                }
            }
        }

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        // Deleting the unit with cascade on 'unit_id' in schedules table should automatically delete schedules.
        // If not, explicitly delete schedules first: $unit->schedules()->delete();
        $unit->lecturers()->detach();
        $unit->delete();
        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}