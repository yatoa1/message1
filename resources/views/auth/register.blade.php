<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>用户注册</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="main">
    <div class="container">
        <h2 style="margin-bottom: 10px; text-align:center;">用户注册</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>

            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('register') }}" method="POST" id="submit_form">
            @csrf
            <ul>
                <li class="line">
                    <span class="title">用户名：</span>
                    <input type="text" name="username" required>
                </li>
                <li class="line">
                    <span class="title">密码：</span>
                    <input type="password" name="password" required>
                </li>
                <li class="line">
                    <span class="title">确认密码：</span>
                    <input type="password" name="password_confirmation" required>
                </li>
                <li class="line submit_line" style="margin-top:45px;">
                    <input type="submit" value=' 注册 ' style="margin-left: 95px;">
                    <a href="{{ route('login') }}">已有账号？去登录</a>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>
</html>