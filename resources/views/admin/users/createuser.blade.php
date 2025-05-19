@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 flex justify-center">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-2xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New User</h2>

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                       required>
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                       required>
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                       class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                @error('mobile')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                       required>
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role"
                        class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                        required>
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                <input type="file" name="image" id="image"
                       class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                       accept="image/*">
                <div id="image-preview" class="mt-3 flex justify-center">
                    <img id="preview-img" src="{{ asset('default/user.png') }}"
                         alt="Profile Image Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 hidden">
                </div>
                <p class="text-xs text-red-500 mt-1">Allowed Types: JPG, PNG, GIF</p>
                @error('image')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="submit"
                        class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 transition-all duration-200 transform hover:scale-105">
                    Add User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection