<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->hasRole(['super_admin', 'store_admin', 'staff'])) {
            return redirect()->route('dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if (! $user->hasRole(['super_admin', 'store_admin', 'staff'])) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'username' => 'You do not have permission to access the admin panel.',
            ])->withInput();
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
