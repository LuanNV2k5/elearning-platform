<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký user mới
     */
    public function store(Request $request): RedirectResponse
    {
        // 1️⃣ Validate dữ liệu đầu vào
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'role'     => ['required', 'in:student,teacher'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2️⃣ Chuyển role (string) → role_id (FK)
        $roleId = Role::where('name', $request->role)->value('id');

        // Phòng trường hợp roles chưa seed
        if (!$roleId) {
            abort(500, 'Role chưa tồn tại trong database');
        }

        // 3️⃣ Tạo user (CHỈ DÙNG role_id)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role_id'  => $roleId, // 👈 QUAN TRỌNG
            'password' => Hash::make($request->password),
        ]);

        // 4️⃣ Phát sự kiện & đăng nhập
        event(new Registered($user));
        Auth::login($user);

        // 5️⃣ Redirect
        return redirect()->intended('/');
    }
}
