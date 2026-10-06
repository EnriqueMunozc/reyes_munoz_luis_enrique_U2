<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->to($this->authorizedDestination($request));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function authorizedDestination(Request $request): string
    {
        $user = $request->user();
        $fallback = $user->isAdministrator()
            ? route('admin.dashboard')
            : route('catalog.index');
        $intended = $request->session()->pull('url.intended');

        if (! is_string($intended) || ! $this->isAllowedDestination($intended, $request)) {
            return $fallback;
        }

        return $intended;
    }

    private function isAllowedDestination(string $destination, Request $request): bool
    {
        $parts = parse_url($destination);

        if ($parts === false || isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        if (isset($parts['host']) && $parts['host'] !== $request->getHost()) {
            return false;
        }

        if (isset($parts['scheme']) && ! in_array($parts['scheme'], ['http', 'https'], true)) {
            return false;
        }

        $path = $parts['path'] ?? '/';

        if (! str_starts_with($path, '/') || in_array($path, [route('login', false), route('register', false)], true)) {
            return false;
        }

        return $path !== route('admin.dashboard', false) || $request->user()->isAdministrator();
    }
}
