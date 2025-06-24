<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CourseReportSubmissionController;

// Student Controllers
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentUnitsController;
use App\Http\Controllers\Student\StudentAttendanceController;

// Lecturer Controllers
use App\Http\Controllers\Lecturer\CourseReportController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\ReportController as LecturerReportController;
// >>> ADD THESE IMPORTS FOR THE NEW LECTURER ROUTES <<<
use App\Http\Controllers\Lecturer\UnitController as LecturerUnitController; // Assuming you have this controller
use App\Http\Controllers\Lecturer\AttendanceController as LecturerAttendanceController; // Assuming you have this controller
// >>> END ADDITIONS <<<


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


// ADMIN ROUTES
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

// Lecturer Routes
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

        // >>> UPDATED LECTURER-SPECIFIC ROUTES FOR UNITS AND ATTENDANCE <<<
        // Corrected route paths: no leading 'lecturer/' as it's already prefixed
        Route::get('/units', [LecturerUnitController::class, 'index'])->name('units.index'); // Route will be /lecturer/units
        Route::get('/attendance-records', [LecturerAttendanceController::class, 'index'])->name('attendance_records.index'); // Route will be /lecturer/attendance-records
        // >>> END UPDATED LECTURER-SPECIFIC ROUTES <<<
    });

// STUDENT ROUTES
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile/complete', [StudentProfileController::class, 'complete'])->name('profile.complete');

    Route::get('/units/{unit}/mark-attendance', [StudentAttendanceController::class, 'showMarkAttendanceForm'])->name('units.mark-attendance');
    
    Route::post('/profile/complete', [StudentProfileController::class, 'update'])->name('profile.save');

    Route::get('/my-units', [StudentUnitsController::class, 'index'])->name('units.index');

    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/enroll', [StudentEnrollmentController::class, 'index'])->name('enroll.index');
        Route::post('/enroll', [StudentEnrollmentController::class, 'store'])->name('enroll.store');

        Route::get('/courses/{course}/levels/{level}/units', [StudentDashboardController::class, 'viewUnits'])
            ->name('courses.levels.units');
    });
});

require __DIR__.'/auth.php';