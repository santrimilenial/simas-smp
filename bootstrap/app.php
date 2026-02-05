<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuruMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\BendaharaMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'guru' => GuruMiddleware::class,
            'staff' => StaffMiddleware::class,
            'bendahara' => BendaharaMiddleware::class,
        ]);
        
        // Trust all proxies for ngrok
        $middleware->trustProxies(at: '*', headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR | \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST | \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT | \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();