<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'login_error' => 'Username atau password salah.'
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:users,name',
            'password' => 'required|string',
            'email'    => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
        ]);

        // Redirect ke login dengan flash message
        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
