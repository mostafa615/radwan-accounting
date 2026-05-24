<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserReturnStatus
{
    public function handle($request, Closure $next) {
        $user = auth()->user();

        if($user && $user->has_returns == 1) {
            return $next($request);
        }

        return abort(404, 'Not found');
    }
}