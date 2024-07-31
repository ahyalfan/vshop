<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // auth()->check() untuk melakukan pengecekan apakah user sudah admin
        if(\auth()->check() && \auth()->user()->isAdmin == 1){
            return $next($request);
        }
        return \redirect()->route("admin.login")->with("errors","Access danied, You are not an Admin");
    }
}
