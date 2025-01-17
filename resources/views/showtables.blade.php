<!DOCTYPE html>
<html>
<head>
    <title>数据库中的表</title>
</head>
<body>
    <h1>数据库中的表</h1>
    <ul>
        @foreach($tables as $table)
            <li>{{ array_values((array)$table)[0] }}</li>
        @endforeach
    </ul>
</body>
</html>