<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureClientRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isClient()) {
            return response()->json([
                'message' => 'Acceso no autorizado. Se requiere rol de cliente.',
            ], 403);
        }

        return $next($request);
    }
}
