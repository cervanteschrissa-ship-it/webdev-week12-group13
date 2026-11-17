<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
    $middleware->redirectGuestsTo(fn (Request $request) => $request->is('api/*') ? null : '/login');

    $middleware->alias([
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability'   => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        // Always answer /api/* with JSON, even when there is no Accept header
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $e) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(fn (AuthenticationException $e, Request $request) =>
            response()->json(['message' => 'Unauthenticated.'], 401));

        $exceptions->render(fn (MissingAbilityException $e, Request $request) =>
            response()->json(['message' => 'This token is not allowed to do that.'], 403));

        $exceptions->render(fn (ModelNotFoundException|NotFoundHttpException $e, Request $request) =>
            response()->json(['message' => 'Resource not found.'], 404));
    })->create();