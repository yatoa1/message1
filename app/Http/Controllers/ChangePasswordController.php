<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        // 确保我们操作的是当前登录的用户
        if (Auth::id() !== (int)$id) {
            return back()->withErrors(['message' => '您只能修改自己的资料']);
        }

        // 验证输入的数据
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'nullable|string|min:6|confirmed',
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
                $user->password_hash = Hash::make($validatedData['new_password']);
            }

            // 处理新上传的头像
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;

                // 记录头像路径是否已更新
                Log::info('Avatar path before save:', ['user_id' => $id, 'new_avatar_path' => $user->avatar]);
            }

            // 在保存用户信息之前记录脏字段
            Log::info('Dirty fields before save:', ['dirty_fields' => $user->getDirty()]);

            // 保存用户信息
            $user->save();

            // 再次记录头像路径以确认是否保存成功
            Log::info('Avatar path after save:', ['user_id' => $id, 'saved_avatar_path' => $user->avatar]);
            // 记录日志
            Log::info('用户资料已成功更新', ['user_id' => $id, 'updated_fields' => $user->getDirty()]);

            // 使用 POST-REDIRECT-GET 模式
            return redirect()->route('profile.index')->with('status', '用户资料已成功更新！');
        } catch (\Exception $e) {
            // 记录异常信息
            Log::error('无法更新用户资料', ['exception' => $e->getMessage(), 'user_id' => $id]);
            return back()->withInput()->withErrors(['message' => '无法更新用户资料，请稍后再试。']);
        }
    }
}
