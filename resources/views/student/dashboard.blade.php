@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-10 bg-gray-100 rounded-xl shadow-2xl max-w-7xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Student Dashboard</h2>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Welcome, {{ $user->name }}!</h3>
        <p class="text-gray-600 mb-4">This is your student dashboard. You can view and update your profile below.</p>
        <a href="{{ route('student.profile') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            View/Edit Profile
        </a>
    </div>
</div>
@endsection