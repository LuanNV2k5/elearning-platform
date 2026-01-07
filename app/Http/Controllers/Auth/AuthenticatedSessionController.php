<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng',
            ]);
        }

        $request->session()->regenerate();

        // ✅ LẤY USER (KHÔNG LOAD ROLE)
        $user = Auth::user();

        // ✅ REDIRECT THEO role_id
        return match ($user->role_id) {
            1       => redirect()->route('admin.dashboard'),
            2       => redirect()->route('teacher.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    }
    
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
