<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();
dd($user);
        if ($user && $user->role === $role) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    }
    