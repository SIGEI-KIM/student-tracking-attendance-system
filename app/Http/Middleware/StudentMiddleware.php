<?php

// app/Http/Middleware/StudentMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isStudent()) {
            return $next($request);
        }

        return redirect('/');
    }
}