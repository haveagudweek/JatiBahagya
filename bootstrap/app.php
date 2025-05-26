<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\TrustProxies;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(
        headers: TrustProxies::HEADER_X_FORWARDED_FOR |
         TrustProxies::HEADER_X_FORWARDED_HOST |
         TrustProxies::HEADER_X_FORWARDED_PORT |
         TrustProxies::HEADER_X_FORWARDED_PROTO|
         TrustProxies::HEADER_X_FORWARDED_AWS_ELB);
        $middleware->web(append: [
            \App\Http\Middleware\CheckVerified::class,
        ]);

        // Atau sebagai alias middleware
        $middleware->alias([
            'verified' => \App\Http\Middleware\CheckVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
