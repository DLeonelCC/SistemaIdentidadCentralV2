<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
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
    }

    /**
     * Override unauthenticated response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Captura la URL desde donde vino la solicitud
        $returnUrl = $request->fullUrl();

        // Redirige al login en `/` con el return_url codificado
        $frontendUrl = rtrim(env('APP_FRONTEND_URL'), '/'); // elimina / al final si lo hay
        return redirect($frontendUrl . '/sso/login?return_url=' . urlencode($returnUrl));
    }
}
