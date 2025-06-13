@extends('layouts.user')

@section('title', 'Available Exams')

@section('content')
@php
use Illuminate\Support\Facades\Auth;
@endphp
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in">
        <!-- Header -->
        <div class="mb-10">
            <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Available Exams</h2>
            <p class="text-gray-600 text-base mt-3 tracking-wide">Explore and attempt the exams available to you.</p>
        </div>
        <!-- Exams List -->
        @if($exams->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 bg-white rounded-xl shadow-xl border border-gray-100 bg-gradient-to-br from-indigo-50 to-teal-50">
                <svg class="w-20 h-20 mb-4 text-gray-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-9-8h.01M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                </svg>
                <p class="text-center text-base font-medium bg-gradient-to-r from-gray-600 to-gray-700 bg-clip-text text-transparent">
                    No active exams are available at this time.
                </p>
            </div>
        @else
            <div class="bg-white p-8 rounded-xl shadow-xl border border-gray-100 bg-gradient-to-br from-indigo-50 to-teal-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Exam Title</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Subject</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Duration</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Instructions</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-900">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $exam)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-all duration-300">
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $exam->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $exam->subject->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $exam->duration }} minutes</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('student.exams.instructions', $exam) }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200 hover:underline">
                                            View Instructions
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(Auth::check() && $exam->results->where('user_id', Auth::user()->id)->isEmpty())
                                            <span class="inline-block px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">
                                                Not Attempted
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 text-xs font-semibold text-teal-700 bg-teal-100 rounded-full">
                                                Completed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(Auth::check() && $exam->results->where('user_id', Auth::user()->id)->isEmpty())
                                            <a href="{{ route('student.exams.start', $exam) }}" class="inline-block px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg hover:from-teal-600 hover:to-teal-700 hover:scale-105 transition-all duration-300 shadow-md">
                                                Start Exam
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $exams->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection