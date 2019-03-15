<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Administrator
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
        $auth = Auth::user();
        $auth_roles = Auth::user()->roles()->select('code')->get()->pluck('code')->toArray();

        if(!in_array("003",$auth_roles))
            return redirect()->route('home');

        return $next($request);
    }
}
