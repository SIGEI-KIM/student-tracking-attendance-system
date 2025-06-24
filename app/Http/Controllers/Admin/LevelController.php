<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level; // Make sure to import the Level model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Make sure to import the DB facade

class LevelController extends Controller
{
    /**
     * Display a listing of the resource (Levels).
     */
    public function index()
    {
        $levels = Level::latest()->paginate(10); // Paginate for better performance on large datasets
        return view('admin.levels.index', compact('levels'));
    }

    /**
     * Show the form for creating a new Level.
     */
    public function create()
    {
        return view('admin.levels.create');
    }

    /**
     * Store a newly created Level in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:levels',
            'year_number' => 'required|integer|min:1',
            'semester_number' => 'required|integer|in:1,2,3', // Assuming semesters are 1, 2, or 3
        ]);

        Level::create($request->only([
            'name',
            'code',
            'year_number',
            'semester_number'
        ]));

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Show the form for editing the specified Level.
     */
    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    /**
     * Update the specified Level in storage.
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:levels,code,' . $level->id, // Exclude current level's ID
            'year_number' => 'required|integer|min:1',
            'semester_number' => 'required|integer|in:1,2,3',
        ]);

        $level->update($request->only([
            'name',
            'code',
            'year_number',
            'semester_number'
        ]));

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified Level from storage.
     */
    public function destroy(Level $level)
    {
        // Use a database transaction to ensure all operations succeed or fail together
        DB::transaction(function () use ($level) {
            // 1. Get all units associated with this level
            //    This assumes your Level model has a 'hasMany' relationship named 'units'.
            //    e.g., in App\Models\Level.php:
            //    public function units(): HasMany { return $this->hasMany(Unit::class); }
            $units = $level->units;

            // 2. For each unit, detach it from any lecturers
            //    This assumes your Unit model has a 'belongsToMany' relationship with Lecturer
            //    and the pivot table is 'lecturer_unit'.
            //    e.g., in App\Models\Unit.php:
            //    public function lecturers(): BelongsToMany { return $this->belongsToMany(Lecturer::class, 'lecturer_unit', 'unit_id', 'lecturer_id'); }
            foreach ($units as $unit) {
                $unit->lecturers()->detach(); // Removes entries from the lecturer_unit pivot table
            }

            // 3. Delete the units that belong to this level.
            //    This assumes your Unit model has a 'belongsTo' relationship with Level.
            //    e.g., in App\Models\Unit.php:
            //    public function level(): BelongsTo { return $this->belongsTo(Level::class); }
            $level->units()->delete(); // Deletes all units where level_id matches this level's ID

            // 4. Finally, delete the level itself.
            $level->delete();
        });

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}