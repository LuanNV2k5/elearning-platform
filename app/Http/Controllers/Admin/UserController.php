<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function destroy(User $user)
{
    // Không cho xoá chính mình
    if ($user->id === auth()->id()) {
        return back()->withErrors('Không thể xoá chính tài khoản của bạn');
    }

    // ❌ Không cho xoá admin
    if ($user->role && strtoupper($user->role->name) === 'ADMIN') {
        return back()->withErrors('Không thể xoá tài khoản admin');
    }

    $user->delete();

    return redirect()
        ->route('admin.users.index')
        ->with('success', '🗑️ Xoá tài khoản thành công');
}

}
