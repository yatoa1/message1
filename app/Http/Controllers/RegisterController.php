<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 验证输入数据
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:user'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Log::info('Validated data:', $validatedData);
        // 创建新用户
        $user = User::create([
            'username' => $validatedData['username'],
            'password_hash' => Hash::make($validatedData['password']),
        ]);
        Log::info('User created:', [
            'id' => $user->id,
            'username' => $user->username,
        ]);

        // 注册成功后重定向到登录页面
        return redirect()->route('login')->with('success', '注册成功，请登录账号');
    }
}