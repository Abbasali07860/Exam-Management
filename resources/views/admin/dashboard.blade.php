@extends('layouts.app')

@section('title', 'Exam Dashboard')

@section('content')
<div class="w-full min-h-screen bg-[#f2f6ff] p-6">
    <div class="w-full bg-white rounded-3xl shadow-xl border border-indigo-100 p-8">

        <!-- Header -->
        <div class="flex justify-between items-start flex-wrap gap-4 mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-[#4b27c7]">Exam Dashboard</h1>
                <p class="text-md text-gray-500 mt-1">Welcome, {{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-400">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <a href="{{ route('admin.exams.index') }}"
                   class="inline-block bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-6 py-2 rounded-full shadow hover:shadow-md transition duration-300">
                    View All Exams
                </a>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
    <!-- Total Exams -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition duration-300">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-book text-4xl"></i>
                    <div>
                        <p class="text-sm font-medium">Total Exams</p>
                        <h2 class="text-3xl font-bold">{{ $totalExams }}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Students (FIXED) -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition duration-300">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-4xl"></i>
                    <div>
                        <p class="text-sm font-medium">Total Students</p>
                        <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>

            <!-- Results Published -->
            <div class="bg-gradient-to-r from-pink-500 to-pink-400 text-white p-6 rounded-2xl shadow-xl hover:scale-105 transition duration-300">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-check-circle text-4xl"></i>
                    <div>
                        <p class="text-sm font-medium">Results Published</p>
                        <h2 class="text-3xl font-bold">0</h2>
                    </div>
                </div>
            </div>
        </div>



        <!-- Upcoming Exams -->
        <div class="bg-white border border-indigo-100 rounded-2xl p-6 shadow-md">
            <h2 class="text-2xl font-semibold text-[#4b27c7] mb-4">Upcoming Exams</h2>

            @if($upcomingExams->isEmpty())
                <div class="text-center text-gray-500 py-10">No upcoming exams scheduled.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($upcomingExams as $exam)
                        <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-indigo-500 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $exam->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $exam->start_date->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            <span class="text-xs bg-indigo-600 text-white px-3 py-1 rounded-full">{{ $exam->subject }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
