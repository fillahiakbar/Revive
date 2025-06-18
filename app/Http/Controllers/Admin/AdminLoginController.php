<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (auth('admin')->attempt($credentials)) {
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}

public function showLoginForm()
{
    return view('admin.login');
}

}
