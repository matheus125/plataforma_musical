<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            HandleCors::class,
        ]);

        $middleware->redirectGuestsTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn () => true);

        $exceptions->render(function (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Dados invalidos.',
                'errors' => $exception->errors(),
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Nao autenticado.',
                'errors' => null,
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: 'Acesso negado.',
                'errors' => null,
            ], 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: 'Acesso negado.',
                'errors' => null,
            ], 403);
        });
    })->create();
