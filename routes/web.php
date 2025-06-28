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
use App\Http\Controllers\Admin\ScheduleController;

// Student Controllers
use App\Http\Controllers\Student\GradeController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentUnitsController;
use App\Http\Controllers\Student\UnitCatalogController as StudentUnitCatalogController; 
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController; 
use App\Http\Controllers\Student\StudentAcademicController; 

// Lecturer Controllers
use App\Http\Controllers\Lecturer\CourseReportController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\ReportController as LecturerReportController;
use App\Http\Controllers\Lecturer\UnitController as LecturerUnitController;
use App\Http\Controllers\Lecturer\AttendanceController as LecturerAttendanceController;
use App\Http\Controllers\Lecturer\AttendanceCodeController; 
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Lecturer\AnnouncementController; 


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

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

// Lecturer Routes
Route::prefix('lecturer')
    ->name('lecturer.')
    ->middleware(['auth', 'verified', 'lecturer'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/attendance', [LecturerAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/unit/{unit}', [LecturerAttendanceController::class, 'viewUnitAttendances'])->name('attendance.unit.view');
        Route::post('/attendance/unit/{unit}/student/{user}/mark-present', [LecturerAttendanceController::class, 'markStudentPresent'])->name('attendance.mark.student.present');
        Route::post('/attendance/unit/{unit}/student/{user}/mark-absent', [LecturerAttendanceController::class, 'markStudentAbsent'])->name('attendance.mark.student.absent');

        // Attendance Code Generation Routes (flattened)
        Route::get('/attendance/generate', [AttendanceCodeController::class, 'create'])->name('attendance.create');
        Route::post('/attendance/generate', [AttendanceCodeController::class, 'store'])->name('attendance.store');
        Route::put('/attendance/{attendanceCode}/invalidate', [AttendanceCodeController::class, 'invalidate'])->name('attendance.invalidate');


        Route::get('/reports', [LecturerReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate-pdf', [LecturerReportController::class, 'generateUnitReportPdf'])->name('reports.generateUnitReportPdf');

        Route::get('/course-report/submit', [CourseReportController::class, 'create'])->name('report_submission.create');
        Route::post('/course-report/submit', [CourseReportController::class, 'store'])->name('report_submission.store');

        Route::get('/units', [LecturerUnitController::class, 'index'])->name('units.index');
        Route::get('/attendance-records', [LecturerAttendanceController::class, 'index'])->name('attendance_records.index');

        // Routes for Announcements - Corrected namespace (implicitly handled by use statement)
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });


// STUDENT ROUTES
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/complete', [StudentProfileController::class, 'create'])->name('profile.complete');
    Route::post('/profile/complete', [StudentProfileController::class, 'store'])->name('profile.store');


    Route::get('/grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades.index');
    Route::get('/registration', [App\Http\Controllers\Student\RegistrationController::class, 'index'])->name('registration.index');
    Route::get('/timetable', [App\Http\Controllers\Student\TimetableController::class, 'index'])->name('timetable.index');
    Route::get('/fees', [App\Http\Controllers\Student\FeeController::class, 'index'])->name('fees.index');
    Route::get('/announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/library', function() { return view('student.library'); })->name('library.index'); 
    Route::get('/it-support', function() { return view('student.it-support'); })->name('it-support.index'); 

    // Routes that require profile completion
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/enroll', [StudentEnrollmentController::class, 'index'])->name('enroll.index');
        Route::post('/enroll', [StudentEnrollmentController::class, 'store'])->name('enroll.store');
        Route::get('/my-units', [StudentUnitsController::class, 'index'])->name('my-units');
        Route::get('/academic', [StudentAcademicController::class, 'index'])->name('academic.index'); 

        // Unit Catalog Route
        Route::get('/units/catalog', [StudentUnitCatalogController::class, 'index'])->name('units.catalog.index'); 

        // Student Attendance Routes 
        Route::get('/attendance/mark', [AttendanceController::class, 'showEnterCodeForm'])->name('attendance.enter_code');
        Route::post('/attendance/submit', [AttendanceController::class, 'submitCode'])->name('attendance.submit_code');
        Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/{unit}/mark', [StudentAttendanceController::class, 'mark'])->name('attendance.mark');
    });
});
require __DIR__.'/auth.php';