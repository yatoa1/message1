<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 

class ChangePasswordController extends Controller
{
    // 显示修改资料的表单
    public function showUpdateForm()
    {
        return view('auth.update');
    }

    // 处理更新资料的请求
    public function update(Request $request, $id)
    {
        // 验证输入的数据
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 限制文件类型和大小
        ]);

        try {
            $user = User::findOrFail($id);

            // 检查当前密码是否匹配
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => '当前密码不正确']);
            }

            // 更新密码
            if ($request->filled('new_password')) {
                $user->password = Hash::make($validatedData['new_password']);
            }

            // 处理新上传的头像
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            // 保存用户信息
            $user->save();

            return redirect()->route('profile.update')->with('status', '用户资料已成功更新！');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => '无法更新用户资料，请稍后再试。']);
        }
    }
}