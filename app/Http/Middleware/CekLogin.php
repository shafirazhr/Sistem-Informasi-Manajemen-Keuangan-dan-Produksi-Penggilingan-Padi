<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('login')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}