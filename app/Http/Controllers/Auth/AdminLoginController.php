<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    private const int MAX_ATTEMPTS = 3;

    private const int LOCKOUT_SECONDS = 60;

    public function create(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->is_admin ? redirect()->route('admin.dashboard') : abort(403);
        }

        $lockoutKey = $this->lockoutKey($request);
        $retryAfter = RateLimiter::tooManyAttempts($lockoutKey, 1)
            ? RateLimiter::availableIn($lockoutKey)
            : 0;

        return view('auth.admin-login', compact('retryAfter'));
    }

    public function store(Request $request): RedirectResponse
    {
        $failedAttemptsKey = $this->failedAttemptsKey($request);
        $lockoutKey = $this->lockoutKey($request);

        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            return $this->lockoutResponse($lockoutKey);
        }

        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'is_admin' => true,
            'password' => $validated['password'],
        ])) {
            $failedAttempts = RateLimiter::hit($failedAttemptsKey, self::LOCKOUT_SECONDS);

            if ($failedAttempts >= self::MAX_ATTEMPTS) {
                RateLimiter::clear($failedAttemptsKey);
                RateLimiter::hit($lockoutKey, self::LOCKOUT_SECONDS);

                return $this->lockoutResponse($lockoutKey);
            }

            return back()->withErrors(['password' => 'Kata sandi tidak cocok.']);
        }

        RateLimiter::clear($failedAttemptsKey);
        RateLimiter::clear($lockoutKey);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar dari dashboard admin.');
    }

    private function failedAttemptsKey(Request $request): string
    {
        return 'admin-login-attempts:'.$request->ip();
    }

    private function lockoutKey(Request $request): string
    {
        return 'admin-login-lockout:'.$request->ip();
    }

    private function lockoutResponse(string $lockoutKey): RedirectResponse
    {
        $retryAfter = RateLimiter::availableIn($lockoutKey);

        return back()->withErrors([
            'password' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$retryAfter} detik.",
        ]);
    }
}
