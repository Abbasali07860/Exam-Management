@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-container max-w-md w-full mx-auto">
    <div class="login-card bg-white p-8 rounded-lg shadow-lg border border-gray-200 animate-fade-in">
        <h2 class="text-2xl font-bold text-zinc-900 text-center mb-6">Admin Login</h2>
        <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
            @csrf
            <div class="flex items-center border border-gray-300 rounded-md p-3 focus-within:ring-2 focus-within:ring-indigo-500">
                <i class="fas fa-envelope text-gray-500 mr-3"></i>
                <input type="email" name="email" placeholder="Email" class="w-full border-none outline-none text-zinc-700" required>
            </div>

            <div class="flex items-center border border-gray-300 rounded-md p-3 focus-within:ring-2 focus-within:ring-indigo-500">
                <i class="fas fa-lock text-gray-500 mr-3"></i>
                <input type="password" name="password" placeholder="Password" class="w-full border-none outline-none text-zinc-700" required>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-md font-semibold hover:scale-105 hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300">
                Login
            </button>
        </form>
        <div class="login-links mt-6 text-center">
            <a href="{{ route('password.request') }}" class="text-coral-500 hover:text-coral-600 font-medium transition-colors duration-200">
                Forgot Password?
            </a>
        </div>
    </div>
</div>
@endsection