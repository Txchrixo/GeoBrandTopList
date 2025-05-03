<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Enums\Messages;
use App\Exceptions\BaseApiException;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new BaseApiException(
            Messages::UNAUTHORIZED->value, 
            Response::HTTP_UNAUTHORIZED
        );
    }
}
