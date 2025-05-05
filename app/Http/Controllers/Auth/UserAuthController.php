<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('user.login');
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $role = Auth::user()->getRoleNames()->first() ?? 'User';
                return view('user.dashboard.index', compact('role'));
            }

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat login.');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'Berhasil logout.');
    }
}
