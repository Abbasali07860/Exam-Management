@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<style>
    .password-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        background-color: #f5f6f8;
        padding: 20px;
    }

    .password-card {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        max-width: 400px;
        width: 150%;
    }

    .password-card h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 10px 40px 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-group .toggle-password {
        position: absolute;
        top: 36px;
        right: 12px;
        cursor: pointer;
        font-size: 16px;
        color: #555;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #4f46e5;
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #4338ca;
    }

    .alert-success {
        background: #d1e7dd;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        color: #0f5132;
    }

    .error {
        color: red;
        font-size: 13px;
    }
</style>

<div class="password-container">
    <div class="password-card">
        <h2>Change Password</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" required>
                <span toggle="#current_password" class="toggle-password fa-solid fa-eye"></span>
                @error('current_password')<small class="error">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>
                <span toggle="#new_password" class="toggle-password fa-solid fa-eye"></span>
                @error('new_password')<small class="error">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                <span toggle="#new_password_confirmation" class="toggle-password fa-solid fa-eye"></span>
            </div>

            <button type="submit" class="btn-submit">Update Password</button>
        </form>
    </div>
</div>
@endsection
