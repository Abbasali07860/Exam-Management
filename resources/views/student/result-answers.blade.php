@extends('layouts.user')

@section('title', 'View Answers - ' . $examResult->exam->title)

@section('content')
<div class="min-h-screen p-6 bg-gray-100">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ $examResult->exam->title }}</h2>
                <p class="text-slate-600 text-base mt-2 tracking-wide">Submitted on: {{ $examResult->end_time->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        <!-- Answers Section -->
        <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
            <h3 class="text-2xl font-semibold text-slate-800 mb-6">Your Answers</h3>
            @if ($examResult->answers->isEmpty())
                <p class="text-slate-600 text-lg text-center">No answers submitted for this exam.</p>
            @else
                @foreach ($examResult->answers as $index => $answer)
                    <div class="mb-6 p-6 bg-slate-50 rounded-lg shadow-sm border border-slate-200">
                        <p class="text-base font-medium text-slate-800 mb-3">
                            {{ $index + 1 }}. {{ $answer->question->question_text }}
                            <span class="text-sm text-slate-600">({{ $answer->question->marks }} marks)</span>
                        </p>
                        <div class="ml-4">
                            <p class="text-sm text-slate-600 mb-1">
                                <span class="font-medium">Your Answer:</span>
                                @if ($answer->question->type === 'mcq')
                                    {{ $answer->question->options[$answer->answer] ?? 'Not answered' }}
                                @else
                                    {{ $answer->answer ?: 'Not answered' }}
                                @endif
                            </p>
                            @if ($answer->question->type === 'mcq')
                                <p class="text-sm text-slate-600 mb-1">
                                    <span class="font-medium">Correct Answer:</span>
                                    {{ $answer->question->options[$answer->question->correct_answer] ?? 'N/A' }}
                                </p>
                            @endif
                            <p class="text-sm">
                                <span class="font-medium">Marks Obtained:</span>
                                <span class="{{ $answer->marks_obtained > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $answer->marks_obtained }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-start">
            <a href="{{ route('student.results.show', $examResult) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-100 transition-all duration-200">
                <i class="fas fa-arrow-left"></i>
                Back to Result
            </a>
        </div>
    </div>
</div>
@endsection