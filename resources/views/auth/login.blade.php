<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>用户登录</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="main">
    <div class="container">
        <h2 style="margin-bottom: 10px; text-align:center;">用户登录</h2>
        @if ($errors->has('username'))
            <p>{{ $errors->first('username') }}</p>
        @endif
        <form action="{{ route('login.submit') }}" method="POST" id="submit_form">
            @csrf
            <ul>
                <li class="line">
                    <span class="title">用户名：</span>
                    <input type="text" name="username" value="{{ old('username') }}" required>
                </li>
                <li class="line">
                    <span class="title">密码：</span>
                    <input type="password" name="password" required>
                </li>
                <li class="line submit_line" style="margin-top:45px;">
                    <input type="submit" value=' 登录 ' style="margin-left: 95px;">
                    <a href="/register">没有账号？去注册</a>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>
</html>