<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    // If you want, you can leave the default properties as they are
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register(): void
    {
        //
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
