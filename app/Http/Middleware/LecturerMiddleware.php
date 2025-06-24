<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Lecturer; // <--- ONLY THIS LINE should be here for the model

class LecturerMiddleware // This is the middleware class itself
{
    public function handle(Request $request, Closure $next): Response
    {
        // You can now use the Lecturer model like this:
        // $lecturer = Lecturer::find(1);
        // etc.

        return $next($request);
    }
}