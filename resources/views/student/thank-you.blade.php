@extends('layouts.user')

@section('title', 'Thank You')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200 max-w-lg w-full text-center">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-4">Thank You!</h2>
        <p class="text-slate-600 text-base mb-6">Your exam has been submitted successfully. You can check your results once they are published by the admin.</p>
        <a href="{{ route('student.exams') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 rounded-lg transition-all duration-200">
            <i class="fas fa-arrow-left"></i>
            Back to Exams
        </a>
    </div>
</div>
@endsection