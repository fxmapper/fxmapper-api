<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;

use Closure;

class OnlyAllowHttpsRequests
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
        if(Request::secure() == false && App::environment() == 'production'){
            return response(['This endpoint can only be accessed with an HTTPS connection'], 403);
        }

        return $next($request);
    }
}
