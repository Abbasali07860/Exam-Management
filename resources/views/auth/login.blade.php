@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="login-container">
    <div class="login-card">
        <h2>Login</h2>
        <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email">

                <input type="password" name="password" placeholder="Password">

                <button type="submit">Login</button>
            </form>
            <div class="extras">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
                <a href="{{ route('register.form') }}">Register here</a>
            </div>
        </div>
    </div>
@endsection