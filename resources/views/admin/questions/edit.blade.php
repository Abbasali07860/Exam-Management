@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-14 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl shadow-2xl max-w-8xl">
    <h2 class="text-4xl font-black bg-gradient-to-r from-teal-600 to-teal-800 bg-clip-text text-black tracking-tight mb-10">
        Edit Question
    </h2>

    <form method="POST" action="{{ route('admin.questions.update', $question->id) }}" class="bg-white p-8 rounded-2xl shadow-md border border-slate-200">
        @csrf
        @method('PUT')

        <!-- Subject Selection -->
        <div class="mb-6">
            <label for="subject_id" class="block text-sm font-semibold text-slate-800 mb-2">Subject (Optional)</label>
            <select name="subject_id" id="subject_id" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                <option value="">Select a Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question Type -->
        <div class="mb-6">
            <label for="type" class="block text-sm font-semibold text-slate-800 mb-2">Question Type</label>
            <select name="type" id="type" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700" onchange="toggleFields(this.value)">
                <option value="">Select Type</option>
                <option value="mcq" {{ old('type', $question->type) == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>True/False</option>
                <option value="descriptive" {{ old('type', $question->type) == 'descriptive' ? 'selected' : '' }}>Descriptive</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question Text -->
        <div class="mb-6">
            <label for="question_text" class="block text-sm font-semibold text-slate-800 mb-2">Question Text</label>
            <textarea name="question_text" id="question_text" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700" rows="4">{{ old('question_text', $question->question_text) }}</textarea>
            @error('question_text')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Options (MCQ Only) -->
        <div id="mcq-options" class="mb-6 {{ $question->type !== 'mcq' ? 'hidden' : '' }}">
            <label class="block text-sm font-semibold text-slate-800 mb-2">Options</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach(['A', 'B', 'C', 'D'] as $index => $label)
                    <div>
                        <label for="options_{{ $index }}" class="block text-sm font-medium text-slate-700 mb-1">Option {{ $label }}</label>
                        <input type="text" name="options[{{ $index }}]" id="options_{{ $index }}" value="{{ old('options.' . $index, $question->options[$index] ?? '') }}"
                               class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                        @error('options.' . $index)
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Correct Answer -->
        <div id="correct-answer-mcq" class="mb-6 {{ $question->type !== 'mcq' ? 'hidden' : '' }}">
            <label for="correct_answer_mcq" class="block text-sm font-semibold text-slate-800 mb-2">Correct Answer (MCQ)</label>
            <select name="correct_answer" id="correct_answer_mcq" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                <option value="">Select Correct Option</option>
                @foreach(['A' => '0', 'B' => '1', 'C' => '2', 'D' => '3'] as $label => $value)
                    <option value="{{ $value }}" {{ old('correct_answer', $question->correct_answer) == $value ? 'selected' : '' }}>Option {{ $label }}</option>
                @endforeach
            </select>
            @error('correct_answer')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div id="correct-answer-true-false" class="mb-6 {{ $question->type !== 'true_false' ? 'hidden' : '' }}">
            <label for="correct_answer_true_false" class="block text-sm font-semibold text-slate-800 mb-2">Correct Answer (True/False)</label>
            <select name="correct_answer" id="correct_answer_true_false" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700">
                <option value="">Select Answer</option>
                <option value="true" {{ old('correct_answer', $question->correct_answer) == 'true' ? 'selected' : '' }}>True</option>
                <option value="false" {{ old('correct_answer', $question->correct_answer) == 'false' ? 'selected' : '' }}>False</option>
            </select>
            @error('correct_answer')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Marks -->
        <div class="mb-6">
            <label for="marks" class="block text-sm font-semibold text-slate-800 mb-2">Marks</label>
            <input type="number" name="marks" id="marks" value="{{ old('marks', $question->marks) }}"
                   class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 text-slate-700" min="1">
            @error('marks')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.questions.index') }}"
               class="bg-gradient-to-r from-slate-200 to-slate-300 text-slate-800 px-7 py-3 rounded-xl text-base font-bold border border-slate-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Cancel
            </a>
            <button type="submit"
                    class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-amber-500 hover:to-amber-600 text-black px-7 py-3 rounded-xl text-base font-bold border border-teal-200 shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-300">
                Update Question
            </button>
        </div>
    </form>
</div>

<script>
function toggleFields(type) {
    const mcqOptions = document.getElementById('mcq-options');
    const correctAnswerMCQWrapper = document.getElementById('correct-answer-mcq');
    const correctAnswerTFWrapper = document.getElementById('correct-answer-true-false');
    const correctAnswerMCQ = document.getElementById('correct_answer_mcq');
    const correctAnswerTF = document.getElementById('correct_answer_true_false');

    // Hide all fields first
    mcqOptions.classList.add('hidden');
    correctAnswerMCQWrapper.classList.add('hidden');
    correctAnswerTFWrapper.classList.add('hidden');

    // Disable all selects
    if (correctAnswerMCQ) correctAnswerMCQ.disabled = true;
    if (correctAnswerTF) correctAnswerTF.disabled = true;

    // Show and enable correct fields
    if (type === 'mcq') {
        mcqOptions.classList.remove('hidden');
        correctAnswerMCQWrapper.classList.remove('hidden');
        correctAnswerMCQ.disabled = false;
    } else if (type === 'true_false') {
        correctAnswerTFWrapper.classList.remove('hidden');
        correctAnswerTF.disabled = false;
    }
}

// Auto-run on page load
document.addEventListener('DOMContentLoaded', function () {
    const currentType = document.getElementById('type').value;
    toggleFields(currentType);
});
</script>



@endsection