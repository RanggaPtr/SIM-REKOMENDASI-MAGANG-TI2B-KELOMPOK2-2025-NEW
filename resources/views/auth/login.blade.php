<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .login-container { max-width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
        .login-container input { width: 100%; padding: 8px; margin: 5px 0; }
        .login-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        @if ($message = Session::get('error'))
            <div class="error">{{ $message }}</div>
        @endif
        <form method="POST" action="{{ route('postlogin') }}">
            @csrf
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>