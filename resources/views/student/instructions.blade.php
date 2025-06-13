@extends('layouts.user')

@section('title', 'Exam Instructions - ' . $exam->title)

@section('content')
<div class="min-h-screen">
    <div class="max-w-5xl mx-auto animate-fade-in">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-wide">Instructions for {{ $exam->title }}</h2>
            <p class="text-slate-600 text-sm mt-2 tracking-wide">Please read the instructions carefully before starting the exam.</p>
        </div>

        <!-- Instructions Content -->
        <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-transparent bg-clip-border bg-gradient-to-r from-indigo-200 to-teal-200">
            <h3 class="text-xl font-semibold text-slate-900 mb-4">Exam Details</h3>
            <p class="text-slate-700 mb-4"><strong>Subject:</strong> {{ $exam->subject->name ?? 'N/A' }}</p>
            <p class="text-slate-700 mb-4"><strong>Duration:</strong> {{ $exam->duration }} minutes</p>
            <p class="text-slate-700 mb-4"><strong>Start Date:</strong> {{ $exam->formatted_start_date }}</p>
            <p class="text-slate-700 mb-6"><strong>End Date:</strong> {{ $exam->formatted_end_date }}</p>

            <h3 class="text-xl font-semibold text-slate-900 mb-4">Instructions</h3>
            @if($exam->instructions)
                <div class="prose text-slate-700">
                    {!! nl2br(e($exam->instructions)) !!}
                </div>
            @else
                <p class="text-slate-600 italic">No instructions provided.</p>
            @endif

            @if($exam->instructions_pdf)
                <div class="mt-6">
                    <a href="{{ Storage::url($exam->instructions_pdf) }}" target="_blank" class="inline-block px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg hover:from-indigo-700 hover:to-indigo-800 hover:scale-105 transition-all duration-300">
                        Download Instructions PDF
                    </a>
                </div>
            @endif

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('student.exams') }}" class="inline-block px-4 py-2 text-sm font-semibold text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition-all duration-200">
                    Back to Exams
                </a>
            </div>
        </div>
    </div>
</div>
@endsection