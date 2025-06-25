<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers (KEEP AS IS)
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CourseReportSubmissionController;

// Student Controllers (ADD/UPDATE IMPORTS)
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentUnitsController; // <-- This is the main one for units
// use App\Http\Controllers\Student\StudentAttendanceController; // <-- COMMENTED OUT for now as per request

// Lecturer Controllers (KEEP AS IS, assuming you've handled them correctly already)
use App\Http\Controllers\Lecturer\CourseReportController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\ReportController as LecturerReportController;
use App\Http\Controllers\Lecturer\UnitController as LecturerUnitController;
use App\Http\Controllers\Lecturer\AttendanceController as LecturerAttendanceController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLecturer()) {
            return redirect()->route('lecturer.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');
});

// Profile routes (for all authenticated users to manage their own profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ADMIN ROUTES (KEEP AS IS)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('lecturers', LecturerController::class)->only(['create','index','store']);

    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('levels', LevelController::class);
    Route::resource('units', UnitController::class);

    // ADMIN ROUTES FOR LECTURER COURSE REPORT SUBMISSIONS
    Route::get('/lecturer-reports', [CourseReportSubmissionController::class, 'index'])->name('course_report_submissions.index');
    Route::get('/lecturer-reports/{id}/download', [CourseReportSubmissionController::class, 'download'])->name('course_report_submissions.download');
    Route::put('/lecturer-reports/{id}/status', [CourseReportSubmissionController::class, 'updateStatus'])->name('course_report_submissions.update_status');
    Route::delete('/lecturer-reports/{id}', [CourseReportSubmissionController::class, 'destroy'])->name('course_report_submissions.destroy');
});

// Lecturer Routes (KEEP AS IS)
Route::prefix('lecturer')
    ->name('lecturer.')
    ->middleware(['auth', 'verified', 'lecturer'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])
            ->name('dashboard');

        // Attendance (now handled by LecturerDashboardController)
        Route::prefix('attendance')->name('attendance.')->group(function() {
            Route::get('/', [LecturerDashboardController::class, 'attendancesIndex'])
                ->name('index');

            Route::get('/unit/{unit}', [LecturerDashboardController::class, 'viewUnitAttendances'])
                ->name('unit.view');
        });

        // Reports (Keep this as it was, assuming it uses LecturerReportController)
        Route::get('/reports', [LecturerReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate-pdf', [LecturerReportController::class, 'generateUnitReportPdf'])->name('reports.generateUnitReportPdf');

        // Course Report Submission Routes
        Route::get('/course-report/submit', [CourseReportController::class, 'create'])->name('report_submission.create');
        Route::post('/course-report/submit', [CourseReportController::class, 'store'])->name('report_submission.store');

        // UPDATED LECTURER-SPECIFIC ROUTES FOR UNITS AND ATTENDANCE
        Route::get('/units', [LecturerUnitController::class, 'index'])->name('units.index');
        Route::get('/attendance-records', [LecturerAttendanceController::class, 'index'])->name('attendance_records.index');
    });

// STUDENT ROUTES - >>> ALL CHANGES ARE HERE <<<
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Route for completing student profile
    // This route displays the profile completion form.
    // It should point to the 'create' method in your controller.
    Route::get('/profile/complete', [StudentProfileController::class, 'create'])->name('profile.complete');

    // This route handles the POST submission of the profile form.
    // It should point to the 'store' method in your controller.
    // The URI for the form's action is currently 'student.profile.store' (which maps to /student/profile/complete)
    // which is fine, as long as the method is correct.
    Route::post('/profile/complete', [StudentProfileController::class, 'store'])->name('profile.store');

    // Student Enrollment Routes (Keep these for now, though initial course selection is now in profile form)
    // These routes would typically be for managing additional enrollments or levels.
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/enroll', [StudentEnrollmentController::class, 'index'])->name('enroll.index');
        Route::post('/enroll', [StudentEnrollmentController::class, 'store'])->name('enroll.store');
    });

    // **IMPORTANT: CONSOLIDATED STUDENT UNITS ROUTES**
    // 1. General "My Units" page (from Sidebar, with filters)
    Route::get('/my-units', [StudentUnitsController::class, 'index'])->name('my-units');

    // 2. Specific Course/Level Units (from Dashboard "View Units" link)
    Route::get('/units/{course}/{level}', [StudentUnitsController::class, 'index'])
        ->name('view-enrolled-units');

    // **REMOVE/COMMENT OUT ATTENDANCE ROUTE FOR NOW**
    // Route::get('/units/{unit}/mark-attendance', [StudentAttendanceController::class, 'showMarkAttendanceForm'])->name('units.mark-attendance');

});
require __DIR__.'/auth.php';