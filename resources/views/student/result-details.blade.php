@extends('layouts.user')

@section('title', 'Exam Result - ' . $examResult->exam->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-12 flex flex-col sm:flex-row items-center justify-between">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 tracking-tight">{{ $examResult->exam->title }}</h2>
                <p class="text-gray-600 text-base mt-2 tracking-wide">Submitted on: {{ $examResult->end_time->format('Y-m-d H:i:s') }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="px-5 py-2.5 text-sm font-semibold rounded-lg {{ $passStatus === 'Pass' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} shadow-md">
                    {{ $passStatus }}
                </span>
            </div>
        </div>

        <!-- Result Summary -->
        <div class="bg-white p-8 rounded-xl shadow-xl border border-gray-100 bg-gradient-to-br from-indigo-50 to-teal-50 mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-8">Result Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Total Questions -->
                <div class="flex items-center gap-4">
                    <i class="fas fa-question-circle text-indigo-600 text-3xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Questions</p>
                        <p class="text-xl font-semibold text-gray-800">{{ $totalQuestions }}</p>
                    </div>
                </div>
                <!-- Correct Answers -->
                <div class="flex items-center gap-4">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Correct Answers</p>
                        <p class="text-xl font-semibold text-gray-800">{{ $stats['correct'] }}</p>
                    </div>
                </div>
                <!-- Wrong Answers -->
                <div class="flex items-center gap-4">
                    <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Wrong Answers</p>
                        <p class="text-xl font-semibold text-gray-800">{{ $stats['wrong'] }}</p>
                    </div>
                </div>
                <!-- Score -->
                <div class="flex items-center gap-4">
                    <i class="fas fa-star text-yellow-500 text-3xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Score</p>
                        <p class="text-xl font-semibold text-gray-800">{{ $score }} / {{ $totalMarks }}</p>
                    </div>
                </div>
                <!-- Percentage -->
                <div class="flex items-center gap-4">
                    <i class="fas fa-percentage text-teal-600 text-3xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Percentage</p>
                        <p class="text-xl font-semibold text-gray-800">{{ number_format($percentage, 2) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('student.results') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left"></i>
                Back to Results
            </a>
            @if ($examResult->allow_view_answers)
                <a href="{{ route('student.results.answers', $examResult) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 rounded-lg hover:scale-105 transition-all duration-300 shadow-md">
                    <i class="fas fa-eye"></i>
                    View Answers
                </a>
            @endif
        </div>
    </div>
</div>
@endsection