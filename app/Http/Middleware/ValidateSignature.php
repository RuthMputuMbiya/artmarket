<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ValidateSignature
{
    public function handle(Request $request, Closure $next)
    {
        if (!URL::hasValidSignature($request)) {
            abort(401, 'Invalid signature.');
        }

        return $next($request);
    }
}