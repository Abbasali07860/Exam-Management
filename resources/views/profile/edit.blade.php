@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="profile-page">
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
            @csrf
            <div class="form-group">
                <label for="name">Username</label>
                <div class="input-icon">
                    <i class="bi bi-person-circle"></i>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                @error('name')<small class="error">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="text" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                @error('email')<small class="error">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label>Profile Image</label>
                <div class="image-preview">
                    <img src="{{ $user->image ? asset('storage/profile_images/' . $user->image) : asset('default/user.png') }}"
                         alt="User Image">
                </div>
            </div>
            <div class="form-group">
                <label for="image">Upload New Image 
                    <small style="color: red;">(JPG, PNG, GIF – Max 2MB)</small>
                </label>
                <div class="input-icon">
                    <i class="bi bi-upload"></i>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/gif">
                </div>
                @error('image')<small class="error">{{ $message }}</small>@enderror
            </div>

            <button type="submit" class="btn-submit">Update Profile</button>
        </form>
    </div>
</div>
@endsection
