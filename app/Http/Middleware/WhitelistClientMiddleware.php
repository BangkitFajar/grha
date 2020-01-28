<?php

namespace App\Http\Middleware;

use Closure;

class WhitelistClientMiddleware
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
        if(!in_array($request->header('x-api-key'), ['tes'])){
            return response()->json([
                'error' => 'You doesnt have authority.',
                'ini' => $request->header('x-api-key')
            ], 401);
        }
        return $next($request);
    }
}
