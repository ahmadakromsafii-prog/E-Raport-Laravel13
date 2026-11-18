<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $exists = \App\Models\User::where('username', $credentials['username'])->exists();
            return back()->withErrors(['username' => $exists ? 'Password salah.' : 'Username tidak terdaftar.'])->onlyInput('username');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}