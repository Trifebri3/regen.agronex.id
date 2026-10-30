<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalAccess
{
    /**
     * Enforce role-based portal and section permissions server-side.
     */
    public function handle(Request $request, Closure $next, string $scope, ?string $section = null): Response
    {
        $user = $request->user();

        if ($user === null) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(401, 'Unauthenticated.');
            }

            return redirect()->guest(route('login'));
        }

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Akun pengguna tidak aktif.');
        }

        $authorized = match ($scope) {
            'admin' => $section !== null
                ? ($user->role->canAccessAdminPanel() && $user->role->canAccessAdminSection($section))
                : $user->role->canAccessAdminPanel(),
            'farm' => $user->role->canAccessFarmApp(),
            'farm_manage' => $user->role->canManageFarmStructure(),
            'enterprise' => $user->role->canAccessEnterpriseDashboard(),
            default => false,
        };

        if (! $authorized) {
            abort(403, 'Akses ditolak: peran akun Anda tidak memiliki izin untuk operasi ini.');
        }

        return $next($request);
    }
}
