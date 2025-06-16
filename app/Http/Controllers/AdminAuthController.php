<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin'; // pastikan hanya admin bisa login di sini

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah atau Anda bukan admin.',
        ]);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }


    public function showRegisterForm()
{
    return view('auth.admin.admin-register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
    ]);

    $admin = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'admin',
    ]);

    Auth::login($admin);

    return redirect()->route('admin.dashboard');
}


}
