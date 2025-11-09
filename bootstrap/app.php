<?php

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle API exceptions
        $exceptions->render(function (ApiException $e, Request $request) {
            if ($request->is('api/*')) {
                return $e->render();
            }
        });

        // Handle ModelNotFoundException for API routes
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'status_code' => 404,
                ], 404);
            }
        });

        // Handle ValidationException for API routes
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                    'status_code' => 422,
                ], 422);
            }
        });

        // Handle AuthenticationException for API routes
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'status_code' => 401,
                ], 401);
            }
        });

        // Handle QueryException for API routes
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                // Log the actual error for debugging
                \Log::error('Database query error: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'A database error occurred',
                    'status_code' => 500,
                ], 500);
            }
        });

        // Handle NotFoundHttpException for API routes
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint not found',
                    'status_code' => 404,
                ], 404);
            }
        });

        // Handle general exceptions for API routes
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                // Log the exception
                \Log::error('API Exception: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString(),
                ]);

                $message = config('app.debug') ? $e->getMessage() : 'An internal server error occurred';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'status_code' => 500,
                ], 500);
            }
        });
    })->create();
