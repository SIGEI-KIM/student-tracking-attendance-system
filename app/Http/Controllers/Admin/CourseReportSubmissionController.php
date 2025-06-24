<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseReportSubmission;
use Illuminate\Support\Facades\Storage; 

class CourseReportSubmissionController extends Controller
{
    /**
     * Display a listing of the course report submissions.
     */
    public function index()
    {
        // Fetch all submissions, eager load lecturer, course, and unit for display
        $submissions = CourseReportSubmission::with('lecturer.user', 'course', 'unit')
                                            ->orderBy('submitted_at', 'desc')
                                            ->paginate(10); // Paginate for large number of reports

        return view('admin.course_report_submissions.index', compact('submissions'));
    }

    /**
     * Download the submitted PDF file.
     */
    public function download($id)
    {
        $submission = CourseReportSubmission::findOrFail($id);

        // Security check: Ensure the file exists and is readable
        if (!Storage::disk('public')->exists($submission->file_path)) {
            return redirect()->back()->with('error', 'Report file not found.');
        }

        // Generate a clean download name
        // Use the stored file_name, or fall back to the generated original
        $downloadFileName = $submission->file_name ?: 'report_' . $submission->id . '.pdf';

        return Storage::disk('public')->download($submission->file_path, $downloadFileName);
    }

    /**
     * Mark a report as reviewed (or add feedback).
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'is_reviewed' => 'boolean',
        'admin_feedback' => 'nullable|string|max:1000',
    ]);

    $submission = CourseReportSubmission::findOrFail($id);

    $submission->update([
        'is_reviewed' => $request->boolean('is_reviewed'),
        'admin_feedback' => $request->admin_feedback,
    ]);

    return redirect()->back()->with('success', 'Report status updated successfully.');
}

    /**
     * Delete a report submission.
     */
    public function destroy($id)
    {
        $submission = CourseReportSubmission::findOrFail($id);

        // Delete the associated file from storage
        if (Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->back()->with('success', 'Report submission deleted successfully.');
    }
}
