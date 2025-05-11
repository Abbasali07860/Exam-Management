<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
@include('layouts.toastr')
    <div class="container">
        <div class="card">
        <h2>Forgot Password</h2>
        <p>Enter your email address and we'll send you a link to reset your password.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Email address</label>
            <div class="input-group">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" placeholder="Your Email" required />
            </div>

            <button type="submit" class="btn-primary">
            <i class="bi bi-send"></i> Send Reset Link
            </button>
            <a href="/login" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </form>
        </div>
    </div>
</body>
</html>
