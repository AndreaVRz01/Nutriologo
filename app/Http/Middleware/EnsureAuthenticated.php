<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAuthenticated {
    public function handle(Request $request, Closure $next) {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder.');
        }
        return $next($request);
    }
}