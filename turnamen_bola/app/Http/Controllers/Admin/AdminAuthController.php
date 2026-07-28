<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $loginInput = trim($request->input('username') ?? $request->input('email') ?? '');
        $password = $request->input('password', '');

        if (empty($loginInput) || empty($password)) {
            return back()->with('error', 'Username dan password wajib diisi.');
        }

        // Find user by username, email, or name
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->orWhere('name', 'like', "%{$loginInput}%")
            ->first();

        // Fallback for default admin
        if (! $user && in_array(strtolower($loginInput), ['admin', 'superadmin', 'admin@disdikpora.id'])) {
            $user = User::where('role', 'super_admin')->first();
        }

        if ($user) {
            if (Hash::check($password, $user->password) || $user->password === $password) {
                if ($user->password === $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
                Auth::login($user);
                $request->session()->regenerate();

                if ($user->isSuperAdmin()) {
                    return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Super Admin!');
                }
            }
        }

        return back()->with('error', 'Username/Email atau Password administrator salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil keluar sistem.');
    }
}
