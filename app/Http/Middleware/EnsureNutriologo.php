<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNutriologo {
    public function handle(Request $request, Closure $next) {
        if (!Auth::check() || !Auth::user()->isNutriologo()) {
            return redirect()->route('citas.index')->with('error', 'Acceso restringido al superusuario.');
        }
        return $next($request);
    }
}