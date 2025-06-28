<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement; // Assuming you have an Announcement model

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements for the student.
     */
    public function index()
    {
        // You might want to filter announcements relevant to the student's course/level
        // For now, let's fetch all active announcements, ordered by creation date
        $announcements = Announcement::where('is_active', true) // Assuming 'is_active' column
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('student.announcements.index', compact('announcements'));
    }
}