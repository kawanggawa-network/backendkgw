<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->roles->pluck('name')->contains('administrator')) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'You dont have access!'
            ]);
        }

        return $next($request);
    }
}
