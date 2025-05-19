@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="flex items-center gap-2 text-3xl font-extrabold tracking-tight">
            <span class="text-indigo-600">📝</span>
            <span class="bg-gradient-to-r from-indigo-600 to-green-600 bg-clip-text text-transparent">
                Exam Instructions: {{ $exam->title }}
            </span>
        </h2>
        <a href="{{ route('admin.exams.index') }}"
           class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-5 py-2 rounded-xl text-sm font-semibold hover:from-gray-300 hover:to-gray-400 hover:scale-105 transition-all duration-300">
            ← Back to Exams
        </a>
    </div>

    <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Current Instructions</h3>
        @if ($exam->instructions_pdf)
            <div class="mb-4">
                <p class="text-gray-700">Instructions PDF:</p>
                <a href="{{ asset('storage/' . $exam->instructions_pdf) }}" target="_blank"
                   class="text-indigo-600 hover:underline font-semibold">
                    View/Download PDF
                </a>
            </div>
        @elseif ($exam->instructions)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200 prose prose-sm max-w-none"
                 id="instructions-preview">
                {!! $exam->instructions !!}
            </div>
        @else
            <p class="text-gray-500 italic">No instructions provided yet.</p>
        @endif
    </div>

    <div class="mt-6 bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Update Instructions</h3>
        <form method="POST" action="{{ route('admin.exams.update-instructions', $exam->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="instructions" class="block mb-2 text-sm font-semibold text-gray-700">
                    Write Instructions                </label>
                <textarea name="instructions" id="instructions" class="w-full h-64 rounded-lg border-2 border-gray-300 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300">{!! old('instructions', $exam->instructions) !!}</textarea>
            </div>

            <div class="mb-6">
                <label for="instructions_pdf" class="block mb-2 text-sm font-semibold text-gray-700">
                    Upload Instructions PDF
                </label>
                <input type="file" name="instructions_pdf" id="instructions_pdf"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500 hover:border-indigo-400 transition-all duration-300 text-gray-700">
                @error('instructions_pdf')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Max file size: 10MB. Supported format: PDF.</p>
            </div>

            @if ($exam->instructions_pdf)
                <div class="mb-6">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <input type="checkbox" name="remove_pdf" value="1"
                               class="rounded focus:ring-teal-500">
                        Remove Existing PDF
                    </label>
                </div>
            @endif

            <div class="flex justify-end space-x-4">
                <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:from-green-600 hover:to-green-700 hover:scale-105 transition-all duration-300">
                    Update Instructions
                </button>
                <a href="{{ route('admin.exams.index') }}"
                   class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-5 py-2 rounded-xl text-sm font-semibold hover:from-gray-300 hover:to-gray-400 hover:scale-105 transition-all duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('instructions', {
        height: 300,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Blockquote'] },
            { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize'] },
        ],
    });
</script>

@endsection