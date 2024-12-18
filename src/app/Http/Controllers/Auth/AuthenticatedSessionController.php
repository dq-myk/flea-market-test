<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
{
    // 認証の失敗時に特定のエラーメッセージを追加
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'login_error' => 'ログイン情報が登録されていません。',
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // 初回ログインの場合プロフィール編集画面へ
    if ($user->first_login) {
        $user->update(['first_login' => false]);

        return redirect('/mypage/profile');
    }

    if (!$user->hasVerifiedEmail()) {
        return redirect('/email/verify');
    }

    return redirect()->intended('/');
}

}
