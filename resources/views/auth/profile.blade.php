<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户中心</title>
    <link rel="stylesheet" href="{{ asset('css/center.css') }}">
</head>
<body>
    <div class="header">
        <div class="container-full">
            <div class="sitename">
                欢迎进入留言板
            </div>
            <div class="user-info" style="display: flex; align-items: center;">
                @auth
                    <!-- 用户头像 -->
                    <div class="user-avatar">
                        @php
                            $user = Auth::user()->fresh();
                        @endphp
                        <img 
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default.png') }}" 
                            alt="{{ $user->avatar ? 'User Avatar' : 'Default Avatar' }}" 
                            class="rounded-circle" 
                            width="30" 
                            height="30"
                        >
                    </div>
                    <!-- 用户名 -->
                    <div class="user-greeting">
                        {{ auth()->user()->username }}
                    </div>
                    <!-- 退出按钮 -->
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">退出</button>
                    </form>
                @else
                    <a href="/login">请登录</a>
                @endauth
            </div>
        </div>
    </div>
    

    <div class="main">
        <div class="container-full">
            <div class="sider">
                <ul>
                    <li class="menu"><a href="/profile" class="link">用户信息</a></li>
                    <li class="menu"><a href="/profile/update" class="link">资料修改</a></li>
                    
                </ul>
            </div>

            <div class="main-content">
                <div id="messages-container">
                    @if(isset($editMode) && $editMode)
                    
                
                    <!-- 编辑留言表单 -->
                    <form action="{{ route('profile.update', ['id' => $message->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <p>
                            <label>编辑留言信息</label><br />
                            <textarea 
                                cols="120" rows="5" name="message" id="message" class="message">{{ old('message', $message->message) }}
                            </textarea>
                        </p>
                        <p>
                            <input name="submit" id="submitted" value="更新" class="submit" type="submit" />
                        </p>
                    </form>

                    @else

                    <!-- 创建新留言表单 -->
                    <form action="{{ route('profile.store') }}" method="POST">
                    @csrf
                    <p>
                        <label>留言信息</label><br />
                        <textarea cols="120" rows="5" name="message" id="message" class="message">{{ old('message') }}</textarea>
                    </p>
                    <p>
                        <input name="submit" id="submitted" value="提交" class="submit" type="submit" />
                    </p>
                </form>

                @endif

                    <div class="message-box">
                        <h3>最新留言</h3>
                        <div class="testlist">
                            @foreach ($messages as $value)
                                <div class="lists">
                                    <div>留言人：{{ $value->username ?? "未知" }}</div>
                                    <div>留言信息：{{ $value->message }}</div>
                                    <div>留言时间：{{ $value->created_at }}</div>

                                    @if (Auth::check() && Auth::id() == $value->user_id)
                                        <form action="{{ route('profile.destroy', $value->id) }}" method="post" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" name="delete_message" onclick="return confirm('确定要删除这条留言吗？')">删除</button>
                                        </form>
                                        <button type="button" onclick="location.href='?edit_id={{ $value->id }}'" class="btn btn-warning btn-sm">编辑</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pagination-container">
                        <div class="pagination">
                            @if ($page > 1)
                                <a href="?page={{ $page - 1 }}" class="page-link">上一页</a>
                            @endif
                        
                            @for ($i = 1; $i <= $totalPages; $i++)
                                <a href="?page={{ $i }}" class="page-link {{ ($i == $page) ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        
                            @if ($page < $totalPages)
                                <a href="?page={{ $page + 1 }}" class="page-link">下一页</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>