<?php

use App\Http\Middleware\ForceHttps;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceHttps::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'checkUser' => \App\Http\Middleware\checkUser::class,
            'session_timeout' => \App\Http\Middleware\SessionTimeout::class,
            'userAkses' => \App\Http\Middleware\userAkses::class,
            'proses' => \App\Http\Middleware\Proses::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
