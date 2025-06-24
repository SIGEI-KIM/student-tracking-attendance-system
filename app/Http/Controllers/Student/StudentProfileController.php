<?php

namespace App\Http\Controllers\Student; // CORRECTED NAMESPACE

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller; // <--- ADD THIS LINE

// Make sure you have your Course and Level models imported if you plan to use them here later
// use App\Models\Course;
// use App\Models\Level;

class StudentProfileController extends Controller // This now correctly extends the base Controller
{
    public function update(Request $request) // Or store if it's a create form
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:users,registration_number,' . $user->id,
            'id_number' => 'required|string|max:255|unique:users,id_number,' . $user->id,
            'gender' => 'required|in:Male,Female,Other',
        ]);

        $user->full_name = $request->full_name;
        $user->registration_number = $request->registration_number;
        $user->id_number = $request->id_number;
        $user->gender = $request->gender;
        $user->profile_completed = true;
        $user->save();

        // THIS IS THE KEY CHANGE: Redirect directly to the course enrollment page
        return redirect()->route('student.enroll.index')
                         ->with('success', 'Your profile has been successfully completed! Please choose your course and level.');
    }

    // You might also have a show/edit method for the form itself
    public function complete()
    {
        // Ensure that only incomplete profiles can access this form
        if (Auth::user()->profile_completed) {
            return redirect()->route('student.dashboard')->with('info', 'Your profile is already complete.');
        }
        return view('student.profile_completion_form');
    }
}