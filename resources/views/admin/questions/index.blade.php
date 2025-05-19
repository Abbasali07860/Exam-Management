@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-16 bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 rounded-3xl shadow-2xl max-w-7xl">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-12">
        <h2 class="flex items-center gap-2 text-3xl font-extrabold tracking-tight relative">
            <span class="text-indigo-600">📚</span> <!-- Emoji kept with visible color -->
            <span class="bg-gradient-to-r from-indigo-600 to-green-600 bg-clip-text text-transparent">
                Question Bank
            </span>
            <span class="absolute -bottom-2 left-0 w-1/3 h-1 bg-gradient-to-r from-indigo-600 to-green-600 rounded-full"></span>
        </h2>
        <div class="flex gap-4">
            <a href="{{ route('admin.questions.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-amber-400 hover:to-amber-500 text-black font-semibold px-5 py-3 rounded-xl shadow-lg transition-transform transform hover:scale-105">
                ➕ Add Question
            </a>
            <a href="{{ route('admin.questions.bulk-upload') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-amber-400 hover:to-amber-500 text-black font-semibold px-5 py-3 rounded-xl shadow-lg transition-transform transform hover:scale-105">
                ⬆️ Bulk Upload
            </a>
        </div>
    </div>

    <!-- Filter -->
    <form id="filter-form" method="GET" action="{{ route('admin.questions.index') }}" class="bg-white p-6 rounded-xl shadow-md border border-slate-200 hover:border-indigo-400 mb-12 transition">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
            <div>
                <label for="subject_filter" class="block text-sm font-semibold text-slate-800 mb-2">Select Subject</label>
                <select id="subject_filter" name="subject_filter" onchange="validateForm()" class="w-full border-2 border-slate-300 px-4 py-3 rounded-xl text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subjectFilter == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <p id="subject_filter_error" class="mt-2 text-sm text-red-600 bg-red-100 p-2 rounded hidden">Please select a subject.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="resetForm()" class="w-full bg-gradient-to-r from-slate-200 to-slate-300 text-slate-800 font-semibold px-5 py-3 rounded-xl shadow hover:scale-105 transition">
                    🔁 Reset
                </button>
                <button type="submit" id="filter-button" class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-purple-400 hover:to-purple-500 text-white font-semibold px-5 py-3 rounded-xl shadow hover:scale-105 transition">
                    🔍 Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl shadow border border-slate-300">
        <table class="min-w-full divide-y divide-slate-200 text-base">
            <thead class="bg-gradient-to-r from-indigo-700 to-purple-700 text-white uppercase text-sm font-bold">
                <tr>
                    <th class="px-6 py-5 text-left">Question</th>
                    <th class="px-6 py-5 text-left">Type</th>
                    <th class="px-6 py-5 text-left">Subject</th>
                    <th class="px-6 py-5 text-left">Marks</th>
                    <th class="px-6 py-5 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($questions as $question)
                    <tr class="bg-white hover:bg-indigo-50 transition">
                        <td class="px-6 py-5 font-medium text-slate-800">
                            <button onclick="alert(`{{ $question->question_text }}`)" class="text-indigo-600 hover:underline">
                                {{ Str::limit($question->question_text, 50, '...') }}
                            </button>
                        </td>
                        <td class="px-6 py-5 text-slate-700">
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-slate-700">{{ $question->subject->name ?? 'None' }}</td>
                        <td class="px-6 py-5 text-slate-700 font-semibold">{{ $question->marks }}</td>
                        <td class="px-6 py-5 flex gap-3">
                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-semibold shadow">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('admin.questions.destroy', $question->id) }}" onsubmit="return confirm('Are you sure you want to delete this question?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-semibold shadow">
                                    🗑️ Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center text-slate-600">
                            <div class="flex flex-col items-center">
                                <svg class="w-14 h-14 text-indigo-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M15 10h.01M9 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xl font-semibold">No questions found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($questions->isNotEmpty())
    <div class="mt-12 flex justify-center">
        <div class="bg-white px-4 py-2 rounded-xl shadow border border-slate-200">
            {{ $questions->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<script>
function validateForm() {
    const subjectFilter = document.getElementById('subject_filter').value;
    const error = document.getElementById('subject_filter_error');
    const filterBtn = document.getElementById('filter-button');

    if (!subjectFilter) {
        error.classList.remove('hidden');
        filterBtn.disabled = true;
    } else {
        error.classList.add('hidden');
        filterBtn.disabled = false;
    }
}

function resetForm() {
    document.getElementById('subject_filter').value = '';
    validateForm();
    document.getElementById('filter-form').submit();
}

document.addEventListener('DOMContentLoaded', validateForm);
</script>
@endsection
