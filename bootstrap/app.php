<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'payments/mpesa/callback',
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:send-expiry-reminders')->dailyAt('09:00');
        $schedule->command('app:send-expiry-reminders')->dailyAt('14:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
