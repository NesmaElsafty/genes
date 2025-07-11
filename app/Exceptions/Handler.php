<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Access denied. You do not have the required role or permission.',
                    'error' => 'Unauthorized',
                    'required_role' => $e->getRequiredRoles(),
                    'required_permission' => $e->getRequiredPermissions(),
                ], 403);
            }
        });
    }
} 