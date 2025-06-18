<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminRegisterController extends Controller
{
    /**
     * Tampilkan halaman form register admin.
     */
    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    /**
     * Proses penyimpanan data register admin.
     */
public function register(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $admin = Admin::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
]);
auth('admin')->login($admin);
return redirect()->route('admin.dashboard');

    // Redirect ke dashboard admin
    return redirect()->route('admin.dashboard')->with('status', 'Registrasi Admin berhasil!');
}
}
