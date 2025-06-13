@extends('layouts.user')

@section('title', 'Exam Results')

@section('content')
<div class="min-h-screen p-6 bg-gray-100">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-6">Your Exam Results</h2>

        @if ($results->isEmpty())
            <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 text-center">
                <p class="text-slate-600 text-lg">No results available. Results will be visible once published by the admin.</p>
                <a href="{{ route('student.exams') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 rounded-lg transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Back to Exams
                </a>
            </div>
        @else
            <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-800">Exam Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-800">Score</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-800">Date Attempted</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-800">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($results as $result)
                            <tr>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $result->exam->title }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $result->score }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $result->end_time->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('student.results.show', $result) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 rounded-lg transition-all duration-200">
                                        <i class="fas fa-eye"></i>
                                        View Result
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection