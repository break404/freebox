<?php

namespace App\Http\Middleware;

use Closure;
use session;

class UserMiddleware
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
        //检查登陆
        $status = app('session')->get('auth.status','');
        if($status !='on'){
            return redirect('/login');
        }

        return $next($request);
    }
}
