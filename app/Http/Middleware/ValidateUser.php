<?php

use App\Exceptions\InvalidUserException;

class ValidateUser
{
    public function handle($request, Closure $next)
    {
        if ($request->header('x-user-id')) {
            return $next($request);
        }

        throw new InvalidUserException;
    }
}
