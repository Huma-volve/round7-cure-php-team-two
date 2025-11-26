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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->hasRole('doctor')){
              return  redirect()->route('doctor-dashboard');
        }
        elseif($request->user()->hasRole('admin')||$request->user()->hasRole('helper')){
            return  redirect()->route('admin-dashboard');
        }
        return $next($request);

    }
}
