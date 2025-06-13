@extends('layouts.user')

@section('title', 'Register')

@section('content')
@php
use Illuminate\Support\Facades\Auth;
@endphp
<div class="min-h-screen bg-gradient-to-b from-indigo-50 to-teal-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border-2 border-transparent bg-clip-border bg-gradient-to-r from-indigo-200 to-teal-200">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-wide">Create Your Account</h2>
            <p class="mt-2 text-sm text-slate-600">Join as a Teacher or Student</p>
        </div>
        <!-- Register Form -->
        <form class="mt-8 space-y-6" action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                <div class="mt-1 relative">
                    <input id="name" name="name" type="text"  value="{{ old('name') }}"
                           class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('name') ? 'border-red-500' : '' }}"
                           placeholder="Enter your full name">
                    <i class="fas fa-user absolute right-3 top-2.5 text-slate-400"></i>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                <div class="mt-1 relative">
                    <input id="email" name="email" type="email"  value="{{ old('email') }}"
                           class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('email') ? 'border-red-500' : '' }}"
                           placeholder="Enter your email">
                    <i class="fas fa-envelope absolute right-3 top-2.5 text-slate-400"></i>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" 
                           class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('password') ? 'border-red-500' : '' }}"
                           placeholder="Enter your password">
                    <i class="fas fa-lock absolute right-3 top-2.5 text-slate-400"></i>
                </div>
            </div>

            <!-- Mobile -->
            <div>
                <label for="mobile" class="block text-sm font-medium text-slate-700">Mobile Number</label>
                <div class="mt-1 relative">
                    <input id="mobile" name="mobile" type="number"  value="{{ old('mobile') }}"
                           class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('mobile') ? 'border-red-500' : '' }}"
                           placeholder="Enter your mobile number">
                    <i class="fas fa-phone absolute right-3 top-2.5 text-slate-400"></i>
                </div>
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
                <div class="mt-1 relative">
                    <select id="role" name="role" 
                            class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $errors->has('role') ? 'border-red-500' : '' }}">
                        <option value="" {{ old('role') ? '' : 'selected' }} disabled>Select your role</option>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-2.5 text-slate-400"></i>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-slate-700">Remember me</label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                    Create Account
                </button>
            </div>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}"
                   class="font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                    Sign In
                </a>
            </p>
        </div>
    </div>
</div>
@endsection