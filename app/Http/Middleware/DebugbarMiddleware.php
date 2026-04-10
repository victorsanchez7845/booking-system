<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DebugbarMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $hasDebugbar = class_exists(\Debugbar::class);

        if ($hasDebugbar) {
            $excludedRoutes = ['login', 'register', 'password.request'];
            $routeName = optional($request->route())->getName();

            if (in_array($routeName, $excludedRoutes, true)) {
                \Debugbar::disable();
            }

            if (auth()->check()) {
                $roles = session()->get('roles.roles', []);

                if (!in_array(1, $roles, true)) {
                    \Debugbar::disable();
                }
            } else {
                \Debugbar::disable();
            }
        }

        return $next($request);
    }
}
