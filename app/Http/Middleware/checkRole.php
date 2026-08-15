<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next , string $role): Response
    {
        if(!$request->user()  || $request->usser()->role != $role ){
            return response()->json([
                'message'  => "unauthorized",
             ],403);
        }
        return $next($request);
    }
}
