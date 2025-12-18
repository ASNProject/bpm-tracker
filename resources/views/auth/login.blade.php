<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        {{-- Error Message --}}
        @if ($errors->has('login_error'))
            <div class="error-message">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <label for="name">Username</label>
            <input type="text" id="name" name="name" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        {{-- Tombol Cek BPM --}}
        <form action="{{ route('cek.bpm') }}" method="GET" style="margin-top: 10px;">
            <button type="submit" class="cek-bpm-button">Manual</button>
        </form>
        
        <p>Belum punya akun? <a href="{{ route('register.form') }}">Register</a></p>

    </div>
</body>
</html>