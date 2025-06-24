<?php

// app/Http/Controllers/Lecturer/CourseReportController.php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Unit;
use App\Models\CourseReportSubmission; // Make sure to import
use Illuminate\Support\Facades\Storage; // For file storage

class CourseReportController extends Controller
{
    /**
     * Show the form for submitting a course report.
     */
    public function create()
    {
        $lecturer = auth()->user()->lecturer;

        // Get units and courses associated with the lecturer
        $units = $lecturer->units()->with('course')->get();
        // Get unique courses from the lecturer's units
        $courses = $units->pluck('course')->unique('id');

        return view('lecturer.course_reports.create', compact('units', 'courses'));
    }

    /**
     * Store a newly created course report submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'unit_id' => 'nullable|exists:units,id', // Make nullable if not always required
            'report_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
            'remarks' => 'nullable|string|max:1000',
        ]);

        $lecturer = auth()->user()->lecturer;

        // Handle file upload
        $filePath = $request->file('report_file')->store('course_reports/' . $lecturer->id, 'public');
        $fileName = $request->file('report_file')->getClientOriginalName();

        $submission = CourseReportSubmission::create([
            'lecturer_id' => $lecturer->id,
            'course_id' => $request->course_id,
            'unit_id' => $request->unit_id,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'remarks' => $request->remarks,
            'submitted_at' => now(),
        ]);

        // --- Optional: Send email to Admin ---
        // You would typically set up a Mail notification here
        // For example:
        // Mail::to('admin@example.com')->send(new NewCourseReportSubmitted($submission));
        // Make sure to configure your mail driver in .env

        return redirect()->route('lecturer.dashboard')->with('success', 'Course report submitted successfully!');
    }
}
