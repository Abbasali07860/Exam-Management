@extends('layouts.user')

@section('title', 'Student Dashboard')

@section('content')
<div class="min-h-screen">
    <div class="max-w-5xl mx-auto animate-fade-in">
        <!-- Welcome Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-wide">Welcome, {{ $user->name }}</h2>
            <p class="text-slate-600 text-sm mt-2 tracking-wide">Here’s an overview of your exam activities.</p>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <!-- Total Available Exams -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-6 rounded-lg text-white shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <i class="fas fa-book text-3xl text-amber-200 animate-bounce"></i>
                    <div>
                        <div class="text-sm font-medium text-indigo-100 tracking-wide">Total Available Exams</div>
                        <div class="text-4xl font-extrabold text-white tracking-tight">{{ $totalAvailableExams }}</div>
                    </div>
                </div>
            </div>

            <!-- Total Attempted Exams -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6 rounded-lg text-white shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <i class="fas fa-check-circle text-3xl text-amber-200 animate-bounce"></i>
                    <div>
                        <div class="text-sm font-medium text-teal-100 tracking-wide">Total Attempted Exams</div>
                        <div class="text-4xl font-extrabold text-white tracking-tight">{{ $totalAttemptedExams }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-transparent bg-clip-border bg-gradient-to-r from-indigo-200 to-teal-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-slate-900 relative tracking-wide">
                    Recent Results
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-indigo-600 to-teal-500 rounded-full"></span>
                </h2>
            </div>
            @if($recentResults->isEmpty())
                <div class="flex flex-col items-center justify-center py-8">
                    <svg class="w-16 h-16 mb-4 text-slate-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-9-8h.01M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                    <p class="text-center text-sm bg-gradient-to-r from-slate-500 to-slate-600 bg-clip-text text-transparent">
                        No recent results found.
                    </p>
                </div>
            @else
                <ul class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                    @foreach($recentResults as $result)
                        <li class="flex items-center justify-between bg-slate-50 p-4 rounded-lg shadow-sm border border-slate-100 hover:shadow-md hover:bg-slate-100 transition-all duration-300">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-alt text-indigo-500 text-sm animate-pulse"></i>
                                <span class="text-sm font-medium text-slate-900 tracking-wide">{{ $result->exam->title }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-500 tracking-wide">Score: {{ $result->score ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-500 tracking-wide">({{ $result->created_at->format('M d, Y') }})</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection