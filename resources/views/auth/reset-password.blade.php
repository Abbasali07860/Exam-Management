<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
<body>
    <div class="card">
    @include('layouts.toastr')
        <h2>Reset Your Password</h2>

        <form method="POST" action="{{ route('password.ResetNew') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label>Email Address:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label>New Password:</label>
            <input type="password" name="password" placeholder="Enter new password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <label>Confirm New Password:</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
