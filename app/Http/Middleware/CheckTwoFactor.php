<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || is_null($user->google2fa_secret)) {
            return $next($request);
        }

        if ($request->session()->has('2fa_verified')) {
            return $next($request);
        }

        if ($request->routeIs('2fa.verify')) {
            return $next($request);
        }

        return redirect()->route('2fa.verify');
    }
}
