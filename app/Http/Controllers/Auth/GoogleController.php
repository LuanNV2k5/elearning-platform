<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirect()
    {
        // Nếu vẫn bị InvalidStateException do session, có thể bật stateless() ở cả đây + callback
        // Nhưng nếu bạn đã đồng bộ domain (127.0.0.1) thì KHÔNG cần stateless().
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google.
     */
    public function callback()
    {
        // Nếu bạn đã đồng bộ domain localhost/127.0.0.1 chuẩn thì dùng user() bình thường
        // Nếu vẫn lỗi state thì đổi thành: Socialite::driver('google')->stateless()->user();
        $googleUser = Socialite::driver('google')->user();

        $studentRoleId = 3; // TODO: sửa đúng id role student của bạn

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'password' => bcrypt(str()->random(32)),
                'role_id' => $studentRoleId,
            ]
        );

        // Nếu user tồn tại nhưng chưa có role_id thì set
        if (empty($user->role_id)) {
            $user->role_id = $studentRoleId;
            $user->save();
        }

        Auth::login($user, true);

        return redirect('/');
    }
}
