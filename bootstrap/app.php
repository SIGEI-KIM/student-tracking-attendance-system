<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'lecturer' => \App\Http\Middleware\LecturerMiddleware::class,
            'student' => \App\Http\Middleware\StudentMiddleware::class,
            'profile.complete' => \App\Http\Middleware\ProfileCompleted::class, 
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();