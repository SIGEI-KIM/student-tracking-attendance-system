<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['course', 'level'])->latest()->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $courses = Course::all();
        $levels = Level::all();
        $lecturers = User::where('role', 'lecturer')->get();
        return view('admin.units.create', compact('courses', 'levels', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:units',
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            'lecturers' => 'required|array',
            'lecturers.*' => 'exists:users,id',
        ]);

        $unit = Unit::create($request->only(['name', 'code', 'course_id', 'level_id']));
        $unit->lecturers()->sync($request->lecturers);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        $courses = Course::all();
        $levels = Level::all(); 
        $lecturers = User::where('role', 'lecturer')->get();
        $assignedLecturers = $unit->lecturers->pluck('id')->toArray();
        
        return view('admin.units.edit', compact('unit', 'courses', 'levels', 'lecturers', 'assignedLecturers'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:units,code,' . $unit->id,
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'required|exists:levels,id',
            'lecturers' => 'required|array',
            'lecturers.*' => 'exists:users,id',
        ]);

        $unit->update($request->only(['name', 'code', 'course_id', 'level_id']));
        $unit->lecturers()->sync($request->lecturers);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->lecturers()->detach();

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}