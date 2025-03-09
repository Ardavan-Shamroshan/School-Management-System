<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return to_route('login');
        }

        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access Denied! Your client does not have access to get URL.');
        }

        if (! auth()->user()->hasAnyRole([RoleEnum::ADMIN, RoleEnum::SUPER_ADMIN, RoleEnum::DEVELOPER])) {
            abort(403, 'Access Denied! Your client does not have permission to get URL.');
        }
        return $next($request);
    }
}
