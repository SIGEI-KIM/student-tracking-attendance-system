<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role; 

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isStudent()) { 
            if (!Auth::user()->profile_completed) {
                if ($request->route()->getName() !== 'student.profile.complete' && $request->route()->getName() !== 'student.profile.save') {
                    return redirect()->route('student.profile.complete')->with('warning', 'Please complete your profile to proceed.');
                }
            } else {
                if ($request->route()->getName() === 'student.profile.complete' || $request->route()->getName() === 'student.profile.save') {
                     return redirect()->route('student.dashboard')->with('info', 'Your profile is already complete.');
                }
            }
        }
        return $next($request);
    }
}