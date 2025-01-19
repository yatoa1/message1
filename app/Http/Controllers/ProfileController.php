<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index(Request $request)
    {

        // 获取编辑模式下的留言ID
        $editId = $request->input('edit_id');
        $editMode = !empty($editId);
        $message = null;

        if ($editMode) {
            // 确保当前用户是留言的所有者
            $message = Message::where('id', $editId)->where('user_id', Auth::id())->firstOrFail();
        }


        // 分页设置
        $perPage = 5;
        $page = $request->input('page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        // 查询分页留言
        $totalMessages = Message::count();
        $totalPages = ceil($totalMessages / $perPage);

        $messages = Message::select('message.*', 'user.username')
            ->join('user', 'message.user_id', '=', 'user.id')
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($perPage)
            ->get();

        return view('auth.profile', compact('messages', 'totalPages', 'page', 'editMode', 'message'));
    }

    public function store(Request $request)
    {
        // 验证输入的数据
        $validatedData = $request->validate([
            'message' => 'required|string|max:65535',
        ]);

        try {
            // 创建新留言
            $message = new Message();
            $message->user_id = Auth::id();
            $message->message = $validatedData['message'];
            $message->save();

            Log::info('New message created successfully with ID: ' . $message->id);
        } catch (\Exception $e) {
            Log::error('Error creating message:', ['message' => $e->getMessage()]);
            return back()->withInput()->withErrors(['message' => '无法创建留言，请稍后再试。']);
        }

        // 使用 POST-REDIRECT-GET 模式
        return redirect()->route('profile.index')->with('status', '留言已成功创建！');
    }



    public function update(Request $request, $id)
    {
        // 验证输入的数据
        $validatedData = $request->validate([
            'message' => 'required|string|max:65535',
        ]);

        try {


            // 找到要编辑的留言并确保当前用户是留言的所有者
            $message = Message::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            // 更新留言
            $message->update([
                'message' => $validatedData['message'],
            ]);

            Log::info('Message updated successfully with ID: ' . $id);
        } catch (\Exception $e) {
            Log::error('Error updating message:', ['id' => $id, 'message' => $e->getMessage()]);
            return back()->withInput()->withErrors(['message' => '无法更新留言，请稍后再试。']);
        }

        // 使用 POST-REDIRECT-GET 模式
        return redirect()->route('profile.index')->with('status', '留言已成功更新！');
    }

    public function destroy($id)
    {
        // 确保用户已登录
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            // 找到要删除的留言并确保当前用户是留言的所有者
            $message = Message::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $message->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting message:', ['id' => $id, 'message' => $e->getMessage()]);
            return back()->withErrors(['message' => '无法删除留言，请稍后再试。']);
        }

        // 使用 POST-REDIRECT-GET 模式
        return redirect()->route('profile.index')->with('status', '留言已成功删除！');
    }
}
