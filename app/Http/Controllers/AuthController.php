<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // 记录提交的凭证
        Log::info('Login attempt:', $credentials);


        if (Auth::attempt($credentials)) {
            // 认证通过，重定向到用户主页
            return redirect()->intended('profile');
        } else {
            // 认证失败，返回登录页面并显示错误消息

            Log::error('Login failed:', $credentials);
            return redirect()->back()->withInput($request->only('username'))->withErrors(['username' => '用户名或密码错误']);
        }
    }




    public function logout(Request $request)
    {
        // 注销用户并销毁会话数据
        Auth::logout();

        // 清除用户认证的会话数据
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 记录日志
        Log::info('User logged out successfully.');

        // 重定向到登录页面或主页
        return redirect()->route('login')->with('status', '您已成功登出！');
    }
}
