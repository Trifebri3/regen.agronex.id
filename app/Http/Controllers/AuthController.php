<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(array_merge($credentials, ['is_active' => true]), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial tidak sesuai atau akun tidak aktif.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $auditLogger->record('auth.login', $user, ['role' => $user?->role->value], $user);

        if ($user !== null && $user->role->canAccessAdminPanel()) {
            return redirect()->intended(route('admin.overview'));
        }

        if ($user !== null && $user->role->canAccessEnterpriseDashboard()) {
            return redirect()->intended(route('enterprise.dashboard'));
        }

        return redirect()->intended(route('app.dashboard'));
    }

    public function logout(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $user = $request->user();
        if ($user !== null) {
            $auditLogger->record('auth.logout', $user, [], $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Anda telah keluar dari sesi portal REGEN.');
    }
}
