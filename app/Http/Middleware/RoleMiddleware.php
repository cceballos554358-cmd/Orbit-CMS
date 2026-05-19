<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Treat contributor as author
        $userRole = auth()->user()->role === 'contributor'
            ? 'author'
            : auth()->user()->role;

        $normalizedRoles = array_map(
            fn($r) => $r === 'contributor' ? 'author' : $r,
            $roles
        );

        if (!in_array($userRole, $normalizedRoles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}