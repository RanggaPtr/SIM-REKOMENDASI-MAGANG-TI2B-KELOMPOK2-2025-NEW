<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .register-container { max-width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
        .register-container input, .register-container select { width: 100%; padding: 8px; margin: 5px 0; }
        .register-container button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        @if ($message = Session::get('error'))
            <div class="error">{{ $message }}</div>
        @endif
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('postregister') }}">
            @csrf
            <div>
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required>
            </div>
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>