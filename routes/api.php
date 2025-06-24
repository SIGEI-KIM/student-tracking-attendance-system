<?php

use App\Http\Controllers\Api\AttendanceController; // Ensure this path is correct
use App\Http\Controllers\Student\StudentAttendanceController; // Ensure this path is correct
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/attendances/mark', [StudentAttendanceController::class, 'markAttendance']); // This should be the ONLY POST
    Route::get('/attendances/student', [AttendanceController::class, 'getStudentAttendances']);
    Route::get('/attendances/unit/{unitId}', [AttendanceController::class, 'getUnitAttendances']);
});