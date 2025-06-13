@extends('layouts.app')

@section('title', 'Exam Dashboard')

@section('content')
@php
use Illuminate\Support\Facades\Auth;
@endphp
<div class="w-full min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-2xl border border-gray-200 p-6 sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                @if (Auth::check())
                    <p class="text-lg font-semibold text-gray-700">Welcome, {{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                @else
                    <p class="text-lg font-semibold text-gray-700">Welcome, Guest</p>
                @endif
            </div>
            <div>
                <a href="{{ route('admin.exams.index') }}"
                   class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-full shadow-lg hover:shadow-xl transition-transform transform hover:scale-105">
                    View All Exams
                </a>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Total Exams -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-book text-3xl" aria-hidden="true"></i>
                    <div>
                        <p class="text-sm font-medium">Total Exams</p>
                        <h2 class="text-2xl font-bold" aria-label="Total exams count">{{ $totalExams }}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-gradient-to-br from-green-600 to-emerald-700 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-3xl" aria-hidden="true"></i>
                    <div>
                        <p class="text-sm font-medium">Total Students</p>
                        <h2 class="text-2xl font-bold" aria-label="Total students count">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>

            <!-- Results Published -->
            <div class="bg-gradient-to-br from-pink-600 to-rose-500 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-check-circle text-3xl" aria-hidden="true"></i>
                    <div>
                        <p class="text-sm font-medium">Results Published</p>
                        <h2 class="text-2xl font-bold" aria-label="Results published count">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Exams -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-md">
            <h2 class="text-xl font-semibold text-indigo-700 mb-4">Upcoming Exams</h2>
            @if($upcomingExams->isEmpty())
                <div class="text-center text-gray-500 py-10">No upcoming exams scheduled.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($upcomingExams as $exam)
                        <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-xl shadow-sm hover:shadow-md transition duration-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-indigo-500 text-lg" aria-hidden="true"></i>
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
@endsection