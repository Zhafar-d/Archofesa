<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role ?? 'user';
        $normalizedRole = match ($userRole) {
            'customer' => 'user',
            default => $userRole,
        };

        $allowedRoles = array_map(static fn (string $role): string => match ($role) {
            'customer' => 'user',
            default => $role,
        }, $roles);

        if ($roles === [] || in_array($normalizedRole, $allowedRoles, true)) {
            return $next($request);
        }

        return match ($normalizedRole) {
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}
