<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements created by the logged-in lecturer.
     */
    public function index()
    {
        // Fetch announcements made by the currently authenticated lecturer
        $announcements = Announcement::where('user_id', Auth::id())
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('lecturer.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('lecturer.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['sometimes'], // CHANGED: from ['boolean'] to ['sometimes']
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->has('is_active'), // This correctly converts 'on' or presence to true, and absence to false
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('lecturer.announcements.index')->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        // Ensure the lecturer can only edit their own announcements
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('lecturer.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Ensure the lecturer can only update their own announcements
        if ($announcement->user_id !== Auth::id()) { // Authorization check
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['sometimes'], // CHANGED: from ['boolean'] to ['sometimes']
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->has('is_active'), // This correctly converts 'on' or presence to true, and absence to false
        ]);

        return redirect()->route('lecturer.announcements.index')->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $announcement->delete();

        return redirect()->route('lecturer.announcements.index')->with('success', 'Announcement deleted successfully!');
    }
}