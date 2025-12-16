<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { width: 400px; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="login-card">
    <h3 class="text-center mb-4">Login Admin</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@gmail.com" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
</div>
</body>
</html>
