@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-8xl">
        <h2 class="flex items-center gap-2 text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-green-600 bg-clip-text text-transparent mb-8">
            <span class="text-indigo-600">✏️</span>
            Grade Subjective Answers
        </h2>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                Exam: {{ $examResult->exam->title }} | Student: {{ $examResult->user->name }}
            </h3>
            <form method="POST" action="{{ route('admin.results.update', $examResult->id) }}">
                @csrf
                @method('PUT')

                @if($subjectiveAnswers->isEmpty())
                    <p class="text-gray-600">No subjective answers found for this result.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-base mb-6">
                        <thead class="bg-gradient-to-r from-indigo-700 to-indigo-600 text-white uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 text-left">Question</th>
                                <th class="px-6 py-4 text-left">Student Answer</th>
                                <th class="px-6 py-4 text-left">Max Marks</th>
                                <th class="px-6 py-4 text-left">Marks Obtained</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($subjectiveAnswers as $answer)
                                <tr>
                                    <td class="px-6 py-4 text-gray-700">{{ $answer->question->title }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $answer->answer }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $answer->question->marks }}</td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="marks_obtained[{{ $answer->id }}]"
                                               value="{{ $answer->marks_obtained }}"
                                               min="0" max="{{ $answer->question->marks }}"
                                               class="w-24 border border-gray-300 px-3 py-2 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300"
                                               required>
                                        @error("marks_obtained.{$answer->id}")
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit"
                            class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 hover:scale-105 transition-all duration-300">
                        Save Marks
                    </button>
                @endif
            </form>
        </div>
    </div>
@endsection