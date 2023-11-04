<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        $level = backpack_auth()->user()->level;
        if($level == 'admin'){
            return $next($request);
        }

        return redirect('/');
    }
}
