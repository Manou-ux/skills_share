<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('is_admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin') {
            $request->session()->put('is_admin', true);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Identifiants administrateur incorrects.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }
}
