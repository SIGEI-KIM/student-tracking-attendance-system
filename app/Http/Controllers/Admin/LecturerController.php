<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecturer; // Make sure this model exists and is correctly configured
use App\Enums\Role; // Assuming this enum is correctly defined
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // Already there
use Illuminate\Validation\Rule; // Added this import for clarity on unique rule usage if needed elsewhere

class LecturerController extends Controller
{
    public function index()
    {
        // Ensure that the 'user' relationship is defined in your Lecturer model
        // e.g., public function user() { return $this->belongsTo(User::class); }
        $lecturers = Lecturer::with('user')->latest()->get();
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        return view('admin.lecturers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'staff_id' => ['required', 'string', 'max:255', 'unique:lecturers'], // Added max:255 for consistency
            'department' => ['required', 'string', 'max:255'], // Added max:255 for consistency
            'faculty' => ['required', 'string', 'max:255'],     // Added max:255 for consistency
            'specialization' => ['nullable', 'string', 'max:255'], // Added validation for specialization
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::LECTURER->value, // Assuming Role enum is correctly used with ->value
        ]);

        // Create lecturer profile linked to the user
        Lecturer::create([
            'user_id' => $user->id,
            'staff_id' => $request->staff_id,
            'department' => $request->department,
            'faculty' => $request->faculty,
            'specialization' => $request->specialization, // This will be null if not provided in the form
        ]);

        // Redirect to the dashboard with a success message for the toast
        return redirect()->route('admin.dashboard')
            ->with('success', 'Lecturer registered successfully!');
    }

    // You would typically have edit, update, show, destroy methods here as well
}