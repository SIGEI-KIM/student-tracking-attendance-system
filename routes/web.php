<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LevelController as AdminLevelController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CourseReportSubmissionController;

// Student Controllers
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentUnitsController;
use App\Http\Controllers\Student\UnitCatalogController as StudentUnitCatalogController; // Corrected import
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController; // Renamed for clarity
use App\Http\Controllers\Student\StudentAcademicController; // Ensure this is imported

// Lecturer Controllers
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


// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('lecturers', LecturerController::class)->only(['create','index','store']);

    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('levels', AdminLevelController::class);
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

        Route::prefix('attendance')->name('attendance.')->group(function() {
            // Changed from LecturerDashboardController if LecturerAttendanceController is specific for this
            Route::get('/', [LecturerAttendanceController::class, 'index']) // Assuming LecturerAttendanceController handles this now
                ->name('index');

            Route::get('/unit/{unit}', [LecturerAttendanceController::class, 'viewUnitAttendances']) // Assuming method is in LecturerAttendanceController
                ->name('unit.view');
            // Add other lecturer attendance routes here, e.g., mark student present
            Route::post('/unit/{unit}/student/{user}/mark-present', [LecturerAttendanceController::class, 'markStudentPresent'])->name('mark.student.present');
            Route::post('/unit/{unit}/student/{user}/mark-absent', [LecturerAttendanceController::class, 'markStudentAbsent'])->name('mark.student.absent');
        });


        Route::get('/reports', [LecturerReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate-pdf', [LecturerReportController::class, 'generateUnitReportPdf'])->name('reports.generateUnitReportPdf');

        Route::get('/course-report/submit', [CourseReportController::class, 'create'])->name('report_submission.create');
        Route::post('/course-report/submit', [CourseReportController::class, 'store'])->name('report_submission.store');

        Route::get('/units', [LecturerUnitController::class, 'index'])->name('units.index');
        Route::get('/attendance-records', [LecturerAttendanceController::class, 'index'])->name('attendance_records.index');
    });

// STUDENT ROUTES
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/complete', [StudentProfileController::class, 'create'])->name('profile.complete');
    Route::post('/profile/complete', [StudentProfileController::class, 'store'])->name('profile.store');

    // Routes that require profile completion
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/enroll', [StudentEnrollmentController::class, 'index'])->name('enroll.index');
        Route::post('/enroll', [StudentEnrollmentController::class, 'store'])->name('enroll.store');
        Route::get('/my-units', [StudentUnitsController::class, 'index'])->name('my-units');
        Route::get('/academic', [StudentAcademicController::class, 'index'])->name('academic.index'); // Corrected Controller usage

        // Unit Catalog Route
        Route::get('/units/catalog', [StudentUnitCatalogController::class, 'index'])->name('units.catalog.index'); // Corrected Controller usage

        // Student Attendance Routes - corrected
        Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
        // CORRECTED LINE BELOW:
        Route::post('/attendance/{unit}/mark', [StudentAttendanceController::class, 'mark'])->name('attendance.mark');
    });
});
require __DIR__.'/auth.php';