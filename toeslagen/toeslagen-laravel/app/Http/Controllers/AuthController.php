<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $c['email'])->first();
        if ($user && $user->geblokkeerd) {
            return back()->withErrors(['email' => 'Dit account is geblokkeerd. Neem contact op met een beheerder.'])->onlyInput('email');
        }
        if (!Auth::attempt($c)) {
            return back()->withErrors(['email' => 'E-mailadres of wachtwoord is onjuist.'])->onlyInput('email');
        }

        $r->session()->regenerate();
        return redirect()->route('home');
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // nieuwe accounts zijn altijd 'burger' (default in de database)
        $user = User::create($d);
        Auth::login($user);
        $r->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('login');
    }
}
