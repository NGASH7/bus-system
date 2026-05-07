<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\SystemActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $redirectTo = $request->string('redirect_to')->toString();
        if ($this->isSafeInternalPath($redirectTo)) {
            $request->session()->put('url.intended', url($redirectTo));
            $request->session()->put('auth_redirect_to', $redirectTo);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $redirectTo = (string) $request->input('redirect_to', '');
        if ($this->isSafeInternalPath($redirectTo)) {
            $request->session()->put('url.intended', url($redirectTo));
        }

        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('auth_redirect_to');

        $user = Auth::user();
        SystemActivity::record('auth.login', 'User logged in successfully.', $user);

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        if ($user->isDriver()) {
            return redirect()->intended(route('driver.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function isSafeInternalPath(string $path): bool
    {
        if ($path === '' || !Str::startsWith($path, '/')) {
            return false;
        }

        return !Str::startsWith($path, ['//', '/\\']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
