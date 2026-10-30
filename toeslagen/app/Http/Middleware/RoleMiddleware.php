<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$rollen)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->rol, $rollen), 403, 'Geen toegang.');

        return $next($request);
    }
}
